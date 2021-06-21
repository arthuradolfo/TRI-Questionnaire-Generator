<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\Student as StudentResource;
use Symfony\Component\HttpKernel\Exception\HttpException;

class StudentController extends Controller
{

    /**
     * @queryParam moodle_id int
     */
    public function index(Request $request)
    {
        if($request->input('moodle_id'))
        {
            return new StudentResource(Student::where([
                ['moodle_id', $request->input('moodle_id')],
                ['user_id', $request->user()->id]
            ])->firstOrFail());
        }
        else {
            return StudentResource::collection(Student::where('user_id', $request->user()->id)->get());
        }
    }

    public function show(Request $request, $id)
    {
        $question = Student::where([
            ['id', $id],
            ['user_id', $request->user()->id]
        ])->firstOrFail();
        return new StudentResource($question);
    }

    /**
     * @bodyParam Student object
     */
    public function store(Request $request)
    {
        $aux_request = $request->all();
        if(isset($aux_request['username'])) {
            $aux_request['user_id'] = $request->user()->id;
            if(!is_null($aux_request['moodle_id']) && Student::where([
                    ['moodle_id', $aux_request['moodle_id']],
                    ['user_id', $aux_request['user_id']]
                ])->first()) {
                $student = Student::where([
                    ['moodle_id', $aux_request['moodle_id']],
                    ['user_id', $aux_request['user_id']]
                ])->first();
                $student->update($aux_request);
                return $student;
            }
            return Student::create($aux_request);
        }
        else {
            $students = array();
            foreach ($aux_request as $one_request) {
                $one_request['user_id'] = $request->user()->id;
                if(!is_null($one_request['moodle_id']) && Student::where([
                        ['moodle_id', $one_request['moodle_id']],
                        ['user_id', $one_request['user_id']]
                    ])->first()) {
                    $student = Student::where([
                        ['moodle_id', $one_request['moodle_id']],
                        ['user_id', $one_request['user_id']]
                    ])->first();
                    $student->update($one_request);
                    $students[] = $student;
                }
                else {
                    $students[] = Student::create($one_request);
                }
            }
            return $students;
        }
    }

    /**
     * @bodyParam Student object
     */
    public function update(Request $request, $id)
    {
        $student = Student::where([['id', $id], ['user_id', $request->user()->id]])->firstOrFail();
        $student->update($request->all());

        return $student;
    }

    public function delete(Request $request, $id)
    {
        $student = Student::where([['id', $id], ['user_id', $request->user()->id]])->firstOrFail();
        $student->delete();

        return response(json_encode(['message' => 'Deleted.']), 204);
    }
}
