<?php

namespace Tests\Feature;

use App\Models\StudentGrade;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StudentGradeTest extends TestCase
{
    protected $token;

    public function getToken($email = 'admin@test.com', $password = 'admin')
    {
        $payload = ['email' => $email, 'password' => $password, 'password_confirmation' => $password];

        $response = $this->postJson('api/login', $payload);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token',
        ]);

        $this->token = $response->json('token');
    }

    public function testGetStudentGradesSuccessfully()
    {
        $this->getToken();

        $response = $this->getJson('api/student_grades',
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'student_id',
                    'question_id',
                    'grade'
                ]
            ],
        ]);
    }

    public function testGetStudentGradesUnauthorizedFailed()
    {
        $response = $this->getJson('api/student_grades');
        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Unauthenticated.'
        ]);
    }

    public function testGetStudentGradeSuccessfully()
    {
        $this->getToken();

        $response = $this->getJson('api/student_grades',
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'student_id',
                    'question_id',
                    'grade'
                ]
            ],
        ]);

        $response = $this->getJson('api/student_grades/'.$response->json('data')[0]['id'],
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'student_id',
                'question_id',
                'grade'
            ],
        ]);
    }

    public function testCreateStudentGradeSuccessfully()
    {
        $this->getToken();

        $student_grade = StudentGrade::factory()->make();

        $response = $this->postJson('api/student_grades', $student_grade->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'student_id',
            'question_id',
            'grade'
        ]);
    }

    public function testCreateStudentGradesSuccessfully()
    {
        $this->getToken();

        $student_grade_1 = StudentGrade::factory()->make();

        $student_grade_2 = StudentGrade::factory()->make([
            'student_id' => '91c35879-c9b1-4876-a50f-e37a79a97cbb'
        ]);

        $response = $this->postJson('api/student_grades', [$student_grade_1->toArray(), $student_grade_2->toArray()],
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            [
                'id',
                'student_id',
                'question_id',
                'grade'
            ]
        ]);
    }

    public function testCreateStudentGradesWithSameStudentAndQuestionFailed()
    {
        $this->getToken();

        $student_grade_1 = StudentGrade::factory()->make();

        $student_grade_2 = StudentGrade::factory()->make();

        $response = $this->postJson('api/student_grades', [$student_grade_1->toArray(), $student_grade_2->toArray()],
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(401);
        $response->assertJsonStructure([
            'error'
        ]);
    }

    public function testCreateStudentGradeWithoutTokenFailed()
    {
        $student_grade = StudentGrade::factory()->make();

        $response = $this->postJson('api/student_grades', $student_grade->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Unauthenticated.'
        ]);
    }

    public function testCreateAndDeleteStudentGradeSuccessfully()
    {
        $this->getToken();

        $student = StudentGrade::factory()->make();

        $response = $this->postJson('api/student_grades', $student->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'student_id',
            'question_id',
            'grade'
        ]);

        $id = $response->json('id');
        $response = $this->deleteJson('api/student_grades/'.$id,
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(204);

    }

    public function testGetStudentGradeFromOtherUserFailed()
    {
        $this->getToken('admin1@test.com');

        $response = $this->getJson('api/student_grades/9cb35879-c5b1-4876-a50f-e37a79a98cbb',
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(404);
        $response->assertJson([
            'error' => 'Resource not found',
        ]);
    }

    public function testGetStudentGradesWithOtherUser()
    {
        $this->getToken('admin1@test.com');

        $response = $this->getJson('api/student_grades',
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
        ]);
        self::assertTrue(sizeof($response->json('data')) == 0);
    }

    public function testUpdateStudentGradeSuccessfully()
    {
        $this->getToken();

        $student_grade = StudentGrade::find('9cb35879-c5b1-4876-a50f-e37a79a98cbb');
        $student_grade->grade = 10;

        $response = $this->putJson('api/student_grades/9cb35879-c5b1-4876-a50f-e37a79a98cbb', $student_grade->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'student_id',
            'question_id',
            'grade'
        ]);
        $response->assertJsonFragment([
            'grade' => 10
        ]);
    }

    public function testUpdateStudentGradeOfOtherUserFailed()
    {
        $this->getToken('admin1@test.com');

        $student_grade = StudentGrade::find('9cb35879-c5b1-4876-a50f-e37a79a98cbb');
        $student_grade->grade = 10;

        $response = $this->putJson('api/student_grades/9cb35879-c5b1-4876-a50f-e37a79a98cbb', $student_grade->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(404);
        $response->assertJson([
            'error' => 'Resource not found'
        ]);
    }

    public function testCreateStudentGradeWithQuestionMoodleIDSuccessfully()
    {
        $this->getToken();

        $student_grade = StudentGrade::factory()->make([
            'question_moodle_id' => 2,
        ]);

        $response = $this->postJson('api/student_grades', $student_grade->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'student_id',
            'question_id',
            'grade'
        ]);
    }

    public function testCreateStudentGradesWithQuestionMoodleIdSuccessfully()
    {
        $this->getToken();

        $student_grade_1 = StudentGrade::factory()->make([
            'question_moodle_id' => 2,
        ]);

        $student_grade_2 = StudentGrade::factory()->make([
            'question_moodle_id' => 2,
            'student_id' => '91c35879-c9b1-4876-a50f-e37a79a97cbb'
        ]);

        $response = $this->postJson('api/student_grades', [$student_grade_1->toArray(), $student_grade_2->toArray()],
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            [
                'id',
                'student_id',
                'question_id',
                'grade'
            ]
        ]);
    }

    public function testCreateStudentGradeWithStudentMoodleIDSuccessfully()
    {
        $this->getToken();

        $student_grade = StudentGrade::factory()->make([
            'student_moodle_id' => 2,
        ]);

        $response = $this->postJson('api/student_grades', $student_grade->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'student_id',
            'question_id',
            'grade'
        ]);
    }

    public function testCreateStudentGradesWithStudentMoodleIdSuccessfully()
    {
        $this->getToken();

        $student_grade_1 = StudentGrade::factory()->make([
            'student_moodle_id' => 2,
        ]);

        $student_grade_2 = StudentGrade::factory()->make([
            'student_moodle_id' => 4,
        ]);

        $response = $this->postJson('api/student_grades', [$student_grade_1->toArray(), $student_grade_2->toArray()],
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            [
                'id',
                'student_id',
                'question_id',
                'grade'
            ]
        ]);
    }
}
