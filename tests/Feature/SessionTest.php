<?php

namespace Tests\Feature;

use App\Models\Session;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SessionTest extends TestCase
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

    public function testGetSessionsSuccessfully()
    {
        $this->getToken();

        $response = $this->getJson('api/sessions',
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'student_id',
                    'category_id',
                    'tqg_id',
                    'number_questions',
                    'status',
                    'time_started',
                    'created_at',
                    'updated_at'
                ]
            ],
        ]);
    }

    public function testGetSessionsUnauthorizedFailed()
    {
        $response = $this->getJson('api/sessions');
        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Unauthenticated.'
        ]);
    }

    public function testGetSessionSuccessfully()
    {
        $this->getToken();

        $response = $this->getJson('api/sessions',
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'student_id',
                    'category_id',
                    'tqg_id',
                    'number_questions',
                    'status',
                    'time_started',
                    'created_at',
                    'updated_at'
                ]
            ],
        ]);

        $response = $this->getJson('api/sessions/'.$response->json('data')[0]['id'],
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'student_id',
                'category_id',
                'tqg_id',
                'number_questions',
                'status',
                'time_started',
                'created_at',
                'updated_at'
            ],
        ]);
    }

    public function testCreateSessionSuccessfully()
    {
        $this->getToken();

        $session = Session::factory()->make([
            'student_id' => '91c35879-c9b1-4876-a50f-e37a79a96cbb',
            'category_id' => '91b0106b-4280-4162-9797-d1b423236c7c',
        ]);

        $response = $this->postJson('api/sessions', $session->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'student_id',
            'category_id',
            'tqg_id',
            'number_questions',
            'status',
            'time_started',
            'created_at',
            'updated_at'
        ]);
    }

    public function testCreateSessionWithoutTokenFailed()
    {
        $session = Session::factory()->make([
            'student_id' => '91c35879-c9b1-4876-a50f-e37a79a96cbb',
            'category_id' => '91b0106b-4280-4162-9797-d1b423236c7c',
        ]);

        $response = $this->postJson('api/sessions', $session->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Unauthenticated.'
        ]);
    }

    public function testCreateAndDeleteSessionSuccessfully()
    {
        $this->getToken();

        $session = Session::factory()->make([
            'student_id' => '91c35879-c9b1-4876-a50f-e37a79a96cbb',
            'category_id' => '91b0106b-4280-4162-9797-d1b423236c7c',
        ]);

        $response = $this->postJson('api/sessions', $session->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'student_id',
            'category_id',
            'tqg_id',
            'number_questions',
            'status',
            'time_started',
            'created_at',
            'updated_at'
        ]);

        $id = $response->json('id');
        $response = $this->deleteJson('api/sessions/'.$id,
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(204);

    }

    public function testCreateAnswerWithInvalidStudentIdFailed()
    {
        $this->getToken();

        $session = Session::factory()->make([
            'student_id' => '91f35879-c9b1-4876-a50f-e37a79a96cbb',
            'category_id' => '91b8106b-4280-4162-9797-d1b423236c7c',
        ]);

        $response = $this->postJson('api/sessions', $session->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(404);
        $response->assertJson([
            'error' => 'Resource not found'
        ]);
    }

    public function testCreateAnswerWithOtherUserStudentIdFailed()
    {
        $this->getToken('admin1@test.com');

        $session = Session::factory()->make([
            'student_id' => '91c35879-c9b1-4876-a50f-e37a79a96cbb',
            'category_id' => '91b8106b-4280-4162-9797-d1b423236c7c',
        ]);

        $response = $this->postJson('api/sessions', $session->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(401);
        $response->assertJson([
            'error' => 'Student ID does not exist for this user.',
        ]);
    }

    public function testGetSessionsFromOtherUserFailed()
    {
        $this->getToken('admin1@test.com');

        $response = $this->getJson('api/sessions/91b0106b-4280-4162-9797-d1b429236c7f',
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(404);
        $response->assertJson([
            'error' => 'Resource not found',
        ]);
    }

    public function testGetSessionsWithOtherUser()
    {
        $this->getToken('admin1@test.com');

        $response = $this->getJson('api/sessions',
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
        ]);
        self::assertTrue(sizeof($response->json('data')) == 0);
    }

    public function testUpdateSessionSuccessfully()
    {
        $this->getToken();

        $session = Session::find('91b0106b-4280-4162-9797-d1b429236c7f');
        $session->status = Session::FINISHED;

        $response = $this->putJson('api/sessions/91b0106b-4280-4162-9797-d1b429236c7f', $session->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'student_id',
            'category_id',
            'tqg_id',
            'number_questions',
            'status',
            'time_started',
            'created_at',
            'updated_at'
        ]);
        $response->assertJsonFragment([
            'status' => Session::FINISHED
        ]);
    }

    public function testUpdateSessionOfOtherUserFailed()
    {
        $this->getToken('admin1@test.com');

        $session = Session::find('91b0106b-4280-4162-9797-d1b429236c7f');
        $session->status = Session::FINISHED;

        $response = $this->putJson('api/sessions/91b0106b-4280-4162-9797-d1b429236c7f', $session->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(404);
        $response->assertJson([
            'error' => 'Resource not found'
        ]);
    }

    public function testCreateSessionWithStudentMoodleIDSuccessfully()
    {
        $this->getToken();

        $session = Session::factory()->make([
            'student_moodle_id' => '3',
            'category_id' => '91b0106b-4280-4162-9797-d1b423236c7c',
        ]);

        $response = $this->postJson('api/sessions', $session->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'student_id',
            'category_id',
            'tqg_id',
            'number_questions',
            'status',
            'time_started',
            'created_at',
            'updated_at'
        ]);
    }

    public function testCreateSessionWithCategoryMoodleIDSuccessfully()
    {
        $this->getToken();

        $session = Session::factory()->make([
            'student_moodle_id' => '3',
            'category_moodle_id' => '2',
        ]);

        $response = $this->postJson('api/sessions', $session->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'student_id',
            'category_id',
            'tqg_id',
            'number_questions',
            'status',
            'time_started',
            'created_at',
            'updated_at'
        ]);
    }
}
