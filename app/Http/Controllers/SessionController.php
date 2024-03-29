<?php

namespace App\Http\Controllers;

use App\Http\Resources\Session as SessionResource;
use App\Http\Resources\Question as QuestionResource;
use App\Models\Category;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Session;
use App\Models\Student;
use App\Models\StudentAbilityLog;
use App\Models\StudentGrade;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    const ABILITY_THRESHOLD = 0.4;
    const MINIMUM_STANDARD_ERROR = 0.315;

    public function index(Request $request)
    {
		return SessionResource::collection(Session::where('user_id', $request->user()->id)
													->orWhere('student_id', $request->user()->student_id)
													->get());
    }

    public function show(Request $request, $id)
    {
        $session = Session::where([
            ['id', $id],
            ['user_id', $request->user()->id]
		])
		->orWhere([
			['id', $id],
			['student_id', $request->user()->student_id]
		])
		->firstOrFail();
        return new SessionResource($session);
    }

    /**
     * @bodyParam Session object
     */
    public function store(Request $request)
    {
		$aux_request = $request->all();
		if(isset($aux_request['category_id']))
		{
			$aux_request['user_id'] = $request->user()->id;
			if(isset($aux_request['category_moodle_id'])) {
				$category = Category::where([['moodle_id', $aux_request['category_moodle_id']], ['user_id', $request->user()->id]])->firstOrFail();
				$aux_request['category_id'] = $category->id;
			}
			if(isset($aux_request['student_moodle_id'])) {
				$student = Student::where([['moodle_id', $aux_request['student_moodle_id']], ['user_id', $request->user()->id]])->firstOrFail();
				$aux_request['student_id'] = $student->id;
			}
			if(isset($aux_request['number_questions']) && $aux_request['number_questions'] < 15) {
				$aux_request['number_questions'] = 15;
			}
			else if(isset($aux_request['number_questions']) && $aux_request['number_questions'] > 20) {
				$aux_request['number_questions'] = 20;
			}
			return Session::create($aux_request);
		}
		else
		{
			$sessions = array();
			foreach ($aux_request as $one_request)
			{
				$one_request['user_id'] = $request->user()->id;
				if(isset($one_request['number_questions']) && $one_request['number_questions'] < 15) {
					$one_request['number_questions'] = 15;
				}
				else if(isset($one_request['number_questions']) && $one_request['number_questions'] > 20) {
					$one_request['number_questions'] = 20;
				}
				$sessions[] = Session::create($one_request);
			}
			return $sessions;
		}
    }

    private function calculate_standard_error($session) {
        $student = Student::where('id', $session->student_id)->firstOrFail();
        $temp_var = 0;
        foreach (explode(',',  $session->questions) as $question_id)
        {
            $question = Question::where('id', $question_id)->firstOrFail();
            $success = 1 / (1 + exp($question->ability - $student->ability));
            $temp_var += $success * (1 - $success);
        }
        $session->standard_error = sqrt(1 / $temp_var);
        $session->update();
    }

    private function check_ending_condition($session)
    {
        $questions_answered = sizeof(explode(',', $session->questions));
        if($questions_answered >= $session->number_questions && $session->standard_error < self::MINIMUM_STANDARD_ERROR)
        {
            $session->time_finished = date("Y-m-d H:m:s", time());
            $session->status = Session::FINISHED;
            $session->update();
        }
    }

    /**
     * @bodyParam Session object
     */
    public function update(Request $request, $id)
    {
		$session = Session::where([['id', $id], ['user_id', $request->user()->id]])
							->orWhere([['id', $id], ['student_id', $request->user()->student_id]])
							->firstOrFail();
        $session->update($request->all());
        if($session->status === Session::ANSWERED) {
            $student = Student::where('id', $session->student_id)->firstOrFail();
            $question = Question::where('id', $session->current_question)->firstOrFail();
            $answer = Answer::where('id', $session->current_answer_id)->first();
            if ($answer->is_correct == 1) {
                $student->ability = $question->ability;
                $student->update();
                StudentAbilityLog::create([
                    'user_id' => $session->user_id,
                    'student_id' => $student->id,
                    'ability' => $student->ability,
                    'time' => now()
                ]);
            } else if ($student->ability > $question->ability) {
                $student->ability = $question->ability;
                $student->update();
                StudentAbilityLog::create([
                    'user_id' => $session->user_id,
                    'student_id' => $student->id,
                    'ability' => $student->ability,
                    'time' => now()
                ]);
            }
            else {
                StudentAbilityLog::create([
                    'user_id' => $session->user_id,
                    'student_id' => $student->id,
                    'ability' => $student->ability,
                    'time' => now()
                ]);
            }

            $this->calculate_standard_error($session);

            $this->check_ending_condition($session);

            $student_grade = StudentGrade::where([
                ['student_id', $student->id],
                ['question_id', $question->id],
                ['user_id', $session->user_id]
            ])->first();
            if ($student_grade === null) {
                $student_grade = new StudentGrade();
                $student_grade->user_id = $session->user_id;
                $student_grade->student_id = $student->id;
                $student_grade->question_id = $question->id;
                $student_grade->grade = $answer->is_correct;
                $student_grade->save();
            } else {
                $student_grade->grade = $answer->is_correct;
                $student_grade->update();
            }
        }

        return $session;
    }

    public function delete(Request $request, $id)
    {
        $session = Session::where([['id', $id], ['user_id', $request->user()->id]])->firstOrFail();
        $session->delete();

        return response(json_encode(['message' => 'Deleted.']), 204);
    }

    public function get_category_ids($category_id)
    {
        $category_ids = array();
        $category_ids[] = $category_id;
        $categories = Category::where('category_id', $category_id)->get();
        foreach ($categories as $category)
        {
            $category_ids[] = $category->id;
            $category_ids = array_merge($category_ids, $this->get_category_ids($category->id));
        }
        return $category_ids;
    }

    public function get_next_question(Request $request, $id)
    {
		$session = Session::where([['id', $id], ['user_id', $request->user()->id]])
							->orWhere([['id', $id], ['student_id', $request->user()->student_id]])
							->firstOrFail();
        if($session->status === Session::ASKED) {
            return response('{"message":"You should answer the question in use first!", "error": 400}', 400); 
        }
        if($session->status === Session::FINISHED) {
            return response('{"message":"This session is finished. Please create another one.", "error": 400}', 400); 
		}
		
		$session->status = Session::FINISHED;
		$session->update();

        $student = Student::where('id', $session->student_id)->first();
        if (!is_null($session->current_answer_id)) {
            $answer = Answer::where('id', $session->current_answer_id)->first();
            $is_correct = $answer->is_correct;
        }
        else {
            $is_correct = 0;
        }
        $ability_threshold = self::ABILITY_THRESHOLD*(-1.1)*pow(-1, $is_correct);
        $question = Question::where(
            [
                ['user_id', $session->user_id]
            ]
        )
        ->whereBetween('ability', [$student->ability-$ability_threshold, $student->ability+$ability_threshold])
        ->whereIn('category_id', $this->get_category_ids($session->category_id))
        ->whereNotIn('id', explode(',', $session->questions))
        ->orderBy('discrimination', 'DESC')->first();

        if(is_null($question))
        {
            if($is_correct == 1)
            {
                $order_by = "DESC";
            }
            else
            {
                $order_by = "ASC";
            }
            $question = Question::where(
                [
                    ['user_id', $session->user_id]
                ]
            )
                ->whereIn('category_id', $this->get_category_ids($session->category_id))
                ->whereNotIn('id', explode(',', $session->questions))
                ->orderBy('ability', $order_by)->firstOrFail();
        }

        if(is_null($question))
        {
            $session->time_finished = date("Y-m-d H:m:s", time());
            $session->status = Session::FINISHED;
            $session->update();
            return response('', 404);
        }
        else {
            $session->status = Session::ASKED;
            if(empty($session->questions))
            {
                $session->questions = $question->id;
            }
            else {
                $session->questions .= ",".$question->id;
            }
            $session->current_question = $question->id;
            $session->update();
            return new QuestionResource($question);
        }
    }

    public function get_current_question(Request $request, $id)
    {
		$session = Session::where([['id', $id], ['user_id', $request->user()->id]])
							->orWhere([['id', $id], ['student_id', $request->user()->student_id]])
							->firstOrFail();
        $question = Question::where(
            [
                ['id', $session->current_question]
            ]
        )->firstOrFail();
        return new QuestionResource($question);
    }
}
