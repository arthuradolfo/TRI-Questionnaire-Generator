<?php

namespace Tests\Feature;

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StudentTest extends TestCase
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

    public function testGetStudentsSuccessfully()
    {
        $this->getToken();

        $response = $this->getJson('api/students',
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'moodle_id',
                    'username',
                    'email',
                    'firstname',
                    'lastname',
                    'idnumber',
                    'institution',
                    'department',
                    'phone1',
                    'phone2',
                    'city',
                    'url',
                    'icq',
                    'skype',
                    'aim',
                    'yahoo',
                    'msn',
                    'country'
                ]
            ],
        ]);
    }

    public function testGetStudentsUnauthorizedFailed()
    {
        $response = $this->getJson('api/students');
        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Unauthenticated.'
        ]);
    }

    public function testGetStudentSuccessfully()
    {
        $this->getToken();

        $response = $this->getJson('api/students',
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'moodle_id',
                    'username',
                    'email',
                    'firstname',
                    'lastname',
                    'idnumber',
                    'institution',
                    'department',
                    'phone1',
                    'phone2',
                    'city',
                    'url',
                    'icq',
                    'skype',
                    'aim',
                    'yahoo',
                    'msn',
                    'country'
                ]
            ],
        ]);

        $response = $this->getJson('api/students/'.$response->json('data')[0]['id'],
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'moodle_id',
                'username',
                'email',
                'firstname',
                'lastname',
                'idnumber',
                'institution',
                'department',
                'phone1',
                'phone2',
                'city',
                'url',
                'icq',
                'skype',
                'aim',
                'yahoo',
                'msn',
                'country'
            ],
        ]);
    }

    public function testCreateStudentSuccessfully()
    {
        $this->getToken();

        $answer = Student::factory()->make();

        $response = $this->postJson('api/students', $answer->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'moodle_id',
            'username',
            'email',
            'firstname',
            'lastname',
            'idnumber',
            'institution',
            'department',
            'phone1',
            'phone2',
            'city',
            'url',
            'icq',
            'skype',
            'aim',
            'yahoo',
            'msn',
            'country'
        ]);
    }

    public function testCreateStudentsSuccessfully()
    {
        $this->getToken();

        $student_1 = Student::factory()->make();

        $student_2 = Student::factory()->make();

        $response = $this->postJson('api/students', [$student_1->toArray(), $student_2->toArray()],
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            [
                'id',
                'moodle_id',
                'username',
                'email',
                'firstname',
                'lastname',
                'idnumber',
                'institution',
                'department',
                'phone1',
                'phone2',
                'city',
                'url',
                'icq',
                'skype',
                'aim',
                'yahoo',
                'msn',
                'country'
            ]
        ]);
    }

    public function testCreateStudentWithoutTokenFailed()
    {
        $student = Student::factory()->make();

        $response = $this->postJson('api/students', $student->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Unauthenticated.'
        ]);
    }

    public function testCreateAndDeleteStudentSuccessfully()
    {
        $this->getToken();

        $student = Student::factory()->make();

        $response = $this->postJson('api/students', $student->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'moodle_id',
            'username',
            'email',
            'firstname',
            'lastname',
            'idnumber',
            'institution',
            'department',
            'phone1',
            'phone2',
            'city',
            'url',
            'icq',
            'skype',
            'aim',
            'yahoo',
            'msn',
            'country'
        ]);

        $id = $response->json('id');
        $response = $this->deleteJson('api/students/'.$id,
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(204);

    }

    public function testGetStudentFromOtherUserFailed()
    {
        $this->getToken('admin1@test.com');

        $response = $this->getJson('api/students/91b99879-c5b1-4876-a50f-e37a79a98cbb',
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(404);
        $response->assertJson([
            'error' => 'Resource not found',
        ]);
    }

    public function testGetStudentsWithOtherUser()
    {
        $this->getToken('admin2@test.com');

        $response = $this->getJson('api/students',
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
        ]);
        self::assertTrue(sizeof($response->json('data')) == 0);
    }

    public function testUpdateStudentSuccessfully()
    {
        $this->getToken();

        $student = Student::find('91c35879-c9b1-4876-a50f-e37a79a96cbb');
        $student->firstname = 'Student Updated';

        $response = $this->putJson('api/students/91c35879-c9b1-4876-a50f-e37a79a96cbb', $student->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'moodle_id',
            'username',
            'email',
            'firstname',
            'lastname',
            'idnumber',
            'institution',
            'department',
            'phone1',
            'phone2',
            'city',
            'url',
            'icq',
            'skype',
            'aim',
            'yahoo',
            'msn',
            'country'
        ]);
        $response->assertJsonFragment([
            'firstname' => 'Student Updated'
        ]);
    }

    public function testUpdateStudentOfOtherUserFailed()
    {
        $this->getToken('admin1@test.com');

        $student = Student::find('91c35879-c9b1-4876-a50f-e37a79a96cbb');
        $student->firstname = 'Student Updated';

        $response = $this->putJson('api/students/91b99879-c5b1-4876-a50f-e37a79a98cbb', $student->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(404);
        $response->assertJson([
            'error' => 'Resource not found'
        ]);
    }

    public function testCreateStudentWithMoodleIdAlreadyExistsFailed()
    {
        $this->getToken();

        $student = Student::factory()->make([
            'moodle_id' => 1,
        ]);

        $response = $this->postJson('api/students', $student->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'moodle_id',
            'username',
            'email',
            'firstname',
            'lastname',
            'idnumber',
            'institution',
            'department',
            'phone1',
            'phone2',
            'city',
            'url',
            'icq',
            'skype',
            'aim',
            'yahoo',
            'msn',
            'country'
        ]);

        $student = Student::factory()->make([
            'moodle_id' => 1,
        ]);

        $response = $this->postJson('api/students', $student->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(401);
        $response->assertJsonStructure([
            'error',
        ]);
    }

    public function testCreateStudentsWithMoodleIdAlreadyExistsFailed()
    {
        $this->getToken();

        $student_1 = Student::factory()->make([
            'moodle_id' => 1,
        ]);

        $student_2 = Student::factory()->make([
            'moodle_id' => 1,
        ]);

        $response = $this->postJson('api/students', [$student_1->toArray(), $student_2->toArray()],
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(401);
        $response->assertJsonStructure([
            'error',
        ]);
    }

    public function testGetStudentByMoodleIdSuccessfully()
    {
        $this->getToken();

        $response = $this->getJson('api/students?moodle_id=3',
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'moodle_id',
                'username',
                'email',
                'firstname',
                'lastname',
                'idnumber',
                'institution',
                'department',
                'phone1',
                'phone2',
                'city',
                'url',
                'icq',
                'skype',
                'aim',
                'yahoo',
                'msn',
                'country'
            ],
        ]);
    }

    public function testGetStudentByMoodleIdFailed()
    {
        $this->getToken();

        $response = $this->getJson('api/students?moodle_id=1',
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(404);
        $response->assertJsonStructure([
            'error',
        ]);
    }
}
