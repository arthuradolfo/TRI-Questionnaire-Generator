<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AnswerTest extends TestCase
{
    public function testGetAnswersSuccesfully()
    {
        $payload = ['email' => 'admin@test.com', 'password' => 'admin', 'password_confirmation' => 'admin'];

        $response = $this->postJson('api/login', $payload);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token',
        ]);

        $response = $this->getJson('api/answers',
                                    ['Authorization' => 'Bearer '.$response->json('token')]);

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
}
