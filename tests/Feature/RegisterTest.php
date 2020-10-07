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
                         'The firstname field is required.',
                         'The lastname field is required.',
                         'The email field is required.',
                         'The password field is required.'],
        ]);
    }


    public function testEmailAlreadyTaken()
    {
        $user = User::factory()->create([
            'email' => 'test@user.com',
            'password' => bcrypt('password'),
        ]);

        $payload = ['email' => 'test@user.com',
                    'username' => 'usertest',
                    'firstname' => 'user',
                    'lastname' => 'test',
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
                    'firstname' => 'user',
                    'lastname' => 'test',
                    'password' => 'password',
                    'password_confirmation' => 'password'];

        $response = $this->postJson('api/register', $payload);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token',
        ]);
    }
}
