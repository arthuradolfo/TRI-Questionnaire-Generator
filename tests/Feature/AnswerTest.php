<?php

namespace Tests\Feature;

use App\Models\Answer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AnswerTest extends TestCase
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

    public function testGetAnswersSuccessfully()
    {
        $this->getToken();

        $response = $this->getJson('api/answers',
                                    ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'question_id',
                    'fraction',
                    'format',
                    'text',
                    'feedback',
                    'feedback_format',
                    'created_at',
                    'updated_at'
                ]
            ],
        ]);
    }

    public function testGetAnswersUnauthorizedFailed()
    {
        $response = $this->getJson('api/answers');
        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Unauthenticated.'
        ]);
    }

    public function testGetAnswerSuccessfully()
    {
        $this->getToken();

        $response = $this->getJson('api/answers',
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'question_id',
                    'fraction',
                    'format',
                    'text',
                    'feedback',
                    'feedback_format',
                    'created_at',
                    'updated_at'
                ]
            ],
        ]);

        $response = $this->getJson('api/answers/'.$response->json('data')[0]['id'],
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'question_id',
                'fraction',
                'format',
                'text',
                'feedback',
                'feedback_format',
                'created_at',
                'updated_at'
            ],
        ]);
    }

    public function testCreateAnswerSuccessfully()
    {
        $this->getToken();

        $answer = Answer::factory()->make([
            'question_id' => '91b0106b-4280-4162-9797-d1b429236c7c',
        ]);

        $response = $this->postJson('api/answers', $answer->toArray(),
                                    ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'question_id',
            'fraction',
            'format',
            'text',
            'feedback',
            'feedback_format',
            'created_at',
            'updated_at'
        ]);
    }

    public function testCreateAnswersSuccessfully()
    {
        $this->getToken();

        $answer_1 = Answer::factory()->make([
            'question_id' => '91b0106b-4280-4162-9797-d1b429236c7c',
        ]);

        $answer_2 = Answer::factory()->make([
            'question_id' => '91b0106b-4280-4162-9797-d1b429236c7c',
        ]);

        $response = $this->postJson('api/answers', [$answer_1->toArray(), $answer_2->toArray()],
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            [
                'id',
                'question_id',
                'fraction',
                'format',
                'text',
                'feedback',
                'feedback_format',
                'created_at',
                'updated_at'
            ]
        ]);
    }

    public function testCreateAnswerWithoutTokenFailed()
    {
        $answer = Answer::factory()->make([
            'question_id' => '91b0106b-4280-4162-9797-d1b429236c7c',
        ]);

        $response = $this->postJson('api/answers', $answer->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Unauthenticated.'
        ]);
    }

    public function testCreateAndDeleteAnswerSuccessfully()
    {
        $this->getToken();

        $answer = Answer::factory()->make([
            'question_id' => '91b0106b-4280-4162-9797-d1b429236c7c',
        ]);

        $response = $this->postJson('api/answers', $answer->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'question_id',
            'fraction',
            'format',
            'text',
            'feedback',
            'feedback_format',
            'created_at',
            'updated_at'
        ]);

        $id = $response->json('id');
        $response = $this->deleteJson('api/answers/'.$id,
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(204);

    }

    public function testCreateAnswerWithInvalidQuestionIdFailed()
    {
        $this->getToken();

        $answer = Answer::factory()->make([
            'question_id' => '91b0109b-4280-4162-9797-d1b429236c7c',
        ]);

        $response = $this->postJson('api/answers', $answer->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(404);
        $response->assertJson([
            'error' => 'Resource not found'
        ]);
    }

    public function testCreateAnswerWithOtherUserQuestionIdFailed()
    {
        $this->getToken('admin1@test.com');

        $answer = Answer::factory()->make([
            'question_id' => '91b0106b-4280-4162-9797-d1b429236c7c',
        ]);

        $response = $this->postJson('api/answers', $answer->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(401);
        $response->assertJson([
            'error' => 'Question ID does not exist for this user.',
        ]);
    }

    public function testGetAnswerFromOtherUserFailed()
    {
        $this->getToken('admin1@test.com');

        $response = $this->getJson('api/answers/91b0106b-4280-4162-9797-d1b429236c7f',
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(404);
        $response->assertJson([
            'error' => 'Resource not found',
        ]);
    }

    public function testGetAnswersWithOtherUser()
    {
        $this->getToken('admin1@test.com');

        $response = $this->getJson('api/answers',
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
        ]);
        self::assertTrue(sizeof($response->json('data')) == 0);
    }

    public function testUpdateAnswerSuccessfully()
    {
        $this->getToken();

        $answer = Answer::find('91b0106b-4280-4162-9797-d1b429236c7f');
        $answer->text = 'Answer Updated';

        $response = $this->putJson('api/answers/91b0106b-4280-4162-9797-d1b429236c7f', $answer->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'question_id',
            'fraction',
            'format',
            'text',
            'feedback',
            'feedback_format',
            'created_at',
            'updated_at'
        ]);
        $response->assertJsonFragment([
            'text' => 'Answer Updated'
        ]);
    }

    public function testUpdateAnswerOfOtherUserFailed()
    {
        $this->getToken('admin1@test.com');

        $answer = Answer::find('91b0106b-4280-4162-9797-d1b429236c7f');
        $answer->text = 'Answer Updated';

        $response = $this->putJson('api/answers/91b0106b-4280-4162-9797-d1b429236c7f', $answer->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(404);
        $response->assertJson([
            'error' => 'Resource not found'
        ]);
    }
}
