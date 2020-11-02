<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
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

    public function testGetCategoriesSuccessfully()
    {
        $this->getToken();

        $response = $this->getJson('api/categories',
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                    'info',
                    'info_format',
                ]
            ],
        ]);
    }

    public function testGetCategoriesUnauthorizedFailed()
    {
        $response = $this->getJson('api/categories');
        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Unauthenticated.'
        ]);
    }

    public function testGetCategorySuccessfully()
    {
        $this->getToken();

        $response = $this->getJson('api/categories',
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                    'info',
                    'info_format',
                ]
            ],
        ]);

        $id = $response->json('data')[0]['id'];

        $response = $this->getJson('api/categories/'.$id,
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'info',
                'info_format',
            ],
        ]);
    }

    public function testCreateCategorySuccessfully()
    {
        $this->getToken();

        $category = Category::factory()->make();

        $response = $this->postJson('api/categories', $category->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'name',
            'info',
            'info_format',
        ]);
    }

    public function testCreateCategoriesSuccessfully()
    {
        $this->getToken();

        $category_1 = Category::factory()->make();

        $category_2 = Category::factory()->make();

        $response = $this->postJson('api/categories', [$category_1->toArray(), $category_2->toArray()],
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            [
                'id',
                'name',
                'info',
                'info_format',
            ]
        ]);
    }

    public function testCreateCategoryWithoutTokenFailed()
    {
        $category = Category::factory()->make();

        $response = $this->postJson('api/categories', $category->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Unauthenticated.'
        ]);
    }

    public function testCreateAndDeleteCategorySuccessfully()
    {
        $this->getToken();

        $category = Category::factory()->make();

        $response = $this->postJson('api/categories', $category->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'name',
            'info',
            'info_format',
        ]);

        $id = $response->json('id');
        $response = $this->deleteJson('api/categories/'.$id,
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(204);

    }

    public function testGetCategoryFromOtherUserFailed()
    {
        $this->getToken('admin1@test.com');

        $response = $this->getJson('api/categories/91b0106b-4280-4162-9797-d1b423236c7c',
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(404);
        $response->assertJson([
            'error' => 'Resource not found',
        ]);
    }

    public function testGetCategoriesWithOtherUser()
    {
        $this->getToken('admin1@test.com');

        $response = $this->getJson('api/categories',
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
        ]);
        self::assertTrue(sizeof($response->json('data')) == 0);
    }

    public function testUpdateCategorySuccessfully()
    {
        $this->getToken();

        $category = Category::find('91b0106b-4280-4162-9797-d1b423236c7c');
        $category->name = 'Category Updated';

        $response = $this->putJson('api/categories/91b0106b-4280-4162-9797-d1b423236c7c', $category->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'name',
            'info',
            'info_format',
        ]);
        $response->assertJsonFragment([
            'name' => 'Category Updated'
        ]);
    }

    public function testUpdateCategoryOfOtherUserFailed()
    {
        $this->getToken('admin1@test.com');

        $category = Category::find('91b0106b-4280-4162-9797-d1b423236c7c');
        $category->text = 'Category Updated';

        $response = $this->putJson('api/categories/91b0106b-4280-4162-9797-d1b423236c7c', $category->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(404);
        $response->assertJson([
            'error' => 'Resource not found'
        ]);
    }

    public function testCreateCategoryWithParentSuccessfully()
    {
        $this->getToken();

        $category = Category::factory()->make();
        $category->moodle_id = 1;

        $response = $this->postJson('api/categories', $category->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'name',
            'info',
            'info_format',
            'moodle_id',
        ]);

        $category = Category::factory()->make();
        $category->category_moodle_id = 1;

        $response = $this->postJson('api/categories', $category->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'name',
            'info',
            'info_format',
            'moodle_id',
        ]);
    }

    public function testCreateCategoryWithMoodleIdAlreadyExistsFailed()
    {
        $this->getToken();

        $category = Category::factory()->make();
        $category->moodle_id = 1;

        $response = $this->postJson('api/categories', $category->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'name',
            'info',
            'info_format',
            'moodle_id',
        ]);

        $category = Category::factory()->make();
        $category->moodle_id = 1;

        $response = $this->postJson('api/categories', $category->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(401);
        $response->assertJsonStructure([
            'error',
        ]);
    }

    public function testCreateCategoriesWithParentSuccessfully()
    {
        $this->getToken();

        $category_1 = Category::factory()->make();
        $category_1->moodle_id = 1;

        $category_2 = Category::factory()->make();
        $category_2->category_moodle_id = 1;

        $response = $this->postJson('api/categories', [$category_1->toArray(), $category_2->toArray()],
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            [
                'id',
                'name',
                'info',
                'info_format',
                'moodle_id',
            ]
        ]);
    }

    public function testCreateCategoriesWithMoodleIdAlreadyExistsFailed()
    {
        $this->getToken();

        $category_1 = Category::factory()->make();
        $category_1->moodle_id = 1;

        $category_2 = Category::factory()->make();
        $category_2->moodle_id = 1;

        $response = $this->postJson('api/categories', [$category_1->toArray(), $category_2->toArray()],
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(401);
        $response->assertJsonStructure([
            'error',
        ]);
    }

    public function testGetCategoryByMoodleIdSuccessfully()
    {
        $this->getToken();

        $response = $this->getJson('api/categories?moodle_id=2',
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'info',
                'info_format',
            ],
        ]);
    }

    public function testGetCategoryByMoodleIdFailed()
    {
        $this->getToken();

        $response = $this->getJson('api/categories?moodle_id=1',
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(404);
        $response->assertJsonStructure([
            'error',
        ]);
    }
}
