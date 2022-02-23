<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentAbilityLog as StudentAbilityLogResource;
use App\Models\Student;
use App\Models\StudentAbilityLog;
use Illuminate\Http\Request;

class StudentAbilityController extends Controller
{
    public function index(Request $request)
    {
        return StudentAbilityLogResource::collection(StudentAbilityLog::where('user_id', $request->user()->id)->get());
    }

    public function show(Request $request, $id)
    {
        return StudentAbilityLogResource::collection(StudentAbilityLog::where([
            ['student_id', $id],
            ['user_id', $request->user()->id]
        ])->get());
    }

    /**
     * @bodyParam StudentAbility object
     */
    public function store(Request $request)
    {
        $aux_request = $request->all();
        if(isset($aux_request['ability'])) {
            $aux_request['user_id'] = $request->user()->id;
            if(isset($aux_request['student_moodle_id'])) {
                $student = Student::where([['moodle_id', $aux_request['student_moodle_id']], ['user_id', $request->user()->id]])->first();
                if($student !== null)
                {
                    $aux_request['student_id'] = $student->id;
                }
            }
            return StudentAbilityLog::create($aux_request);
        }
        else {
            $student_grades = array();
            foreach ($aux_request as $one_request) {
                $one_request['user_id'] = $request->user()->id;
                if(isset($one_request['student_moodle_id'])) {
                    $student = Student::where([['moodle_id', $one_request['student_moodle_id']], ['user_id', $request->user()->id]])->first();
                    if($student !== null)
                    {
                        $one_request['student_id'] = $student->id;
                    }
                }
                $student_abilities[] = StudentAbilityLog::create($one_request);
            }
            return $student_abilities;
        }
    }
}
