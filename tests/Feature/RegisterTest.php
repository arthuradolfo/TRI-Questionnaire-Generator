<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    public function testRequiresFields()
    {
        $response = $this->postJson('api/register');

        $response->assertStatus(422);
        $response->assertJson([
            'errors' => ['The username field is required.',
                         'The email field is required.',
                         'The password field is required.'],
        ]);
    }


    public function testEmailAlreadyTaken()
    {
        User::factory()->create([
            'email' => 'test@user.com',
            'password' => bcrypt('password'),
        ]);

        $payload = ['email' => 'test@user.com',
                    'username' => 'usertest',
                    'password' => 'password',
                    'password_confirmation' => 'password'];

        $response = $this->postJson('api/register', $payload);

        $response->assertStatus(422);
        $response->assertJson([
            'errors' => ['The email has already been taken.'],
        ]);
    }

    public function testRegisterSuccessful()
    {
        $payload = ['email' => 'admin3@test.com',
                    'username' => 'usertest',
                    'password' => 'password',
                    'password_confirmation' => 'password'];

        $response = $this->postJson('api/register', $payload);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token',
        ]);
    }

    public function testChangePasswordFailure()
    {
        $payload = ['new_password' => 'password123',
            'new_password_confirmation' => 'password123'];

        $response = $this->postJson('api/password', $payload);

        $response->assertStatus(401);
        $response->assertJsonStructure([
            'message',
        ]);
    }

    public function testChangePasswordValidatorFailure()
    {
        User::factory()->create([
            'email' => 'test@user.com',
            'password' => bcrypt('password'),
        ]);

        $payload = ['email' => 'test@user.com', 'password' => 'password', 'password_confirmation' => 'password'];

        $response = $this->postJson('api/login', $payload);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token',
        ]);

        $token = $response->json('token');

        $payload = ['new_password' => '1234',
            'new_password_confirmation' => '1234'];

        $response = $this->postJson('api/password', $payload, ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'errors',
        ]);;
    }

    public function testChangePasswordSuccessfully()
    {
        User::factory()->create([
            'email' => 'test@user.com',
            'password' => bcrypt('password'),
        ]);

        $payload = ['email' => 'test@user.com', 'password' => 'password', 'password_confirmation' => 'password'];

        $response = $this->postJson('api/login', $payload);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token',
        ]);

        $token = $response->json('token');

        $payload = ['new_password' => 'password123',
            'new_password_confirmation' => 'password123'];

        $response = $this->postJson('api/password', $payload, ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token',
        ]);

        $payload = ['email' => 'test@user.com', 'password' => 'password123', 'password_confirmation' => 'password123'];

        $response = $this->postJson('api/login', $payload);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token',
        ]);
    }
}
