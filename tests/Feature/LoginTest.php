<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{

    public function testPageNotFound()
    {
        $response = $this->postJson('invalid');

        $response->assertStatus(404);
        $response->assertJson([
            'error' => 'Page not found',
        ]);
    }

    public function testRequiresEmailAndLogin()
    {
        $response = $this->postJson('api/login');

        $response->assertStatus(422);
        $response->assertJson([
            'errors' => ['The email field is required.', 'The password field is required.'],
        ]);
    }


    public function testUserLoginsSuccessfully()
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
    }


    public function testPasswordsMismatch()
    {

        $payload = ['email' => 'test@user.com', 'password' => 'password', 'password_confirmation' => 'password1'];

        $response = $this->postJson('api/login', $payload);

        $response->assertStatus(422);
        $response->assertJson([
            'errors' => ['The password confirmation does not match.'],
        ]);
    }


    public function testUserDoesNotExist()
    {

        $payload = ['email' => 'test123@user.com', 'password' => 'password', 'password_confirmation' => 'password'];

        $response = $this->postJson('api/login', $payload);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'User does not exist',
        ]);
    }


    public function testWrongAnswer()
    {
        $user = User::factory()->create([
            'email' => 'test@user.com',
            'password' => bcrypt('password'),
        ]);

        $payload = ['email' => 'test@user.com', 'password' => 'password1', 'password_confirmation' => 'password1'];

        $response = $this->postJson('api/login', $payload);

        $response->assertStatus(422);
        $response->assertJson([ 'message' => 'Password mismatch' ]);
    }
}
