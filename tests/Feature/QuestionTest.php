<?php

namespace Tests\Feature;

use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QuestionTest extends TestCase
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

    public function testGetQuestionsSuccessfully()
    {
        $this->getToken();

        $response = $this->getJson('api/questions',
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'category_id',
                    'type',
                    'name',
                    'questiontext',
                    'questiontext_format',
                    'generalfeedback',
                    'generalfeedback_format',
                    'defaultgrade',
                    'penalty',
                    'hidden',
                    'idnumber',
                    'single',
                    'shuffleanswers',
                    'answernumbering',
                    'showstandardinstruction',
                    'correctfeedback',
                    'correctfeedback_format',
                    'partiallycorrectfeedback',
                    'partiallycorrectfeedback_format',
                    'incorrectfeedback',
                    'incorrectfeedback_format'
                ]
            ],
        ]);
    }

    public function testGetQuestionsUnauthorizedFailed()
    {
        $response = $this->getJson('api/questions');
        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Unauthenticated.'
        ]);
    }

    public function testGetQuestionSuccessfully()
    {
        $this->getToken();

        $response = $this->getJson('api/questions',
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'category_id',
                    'type',
                    'name',
                    'questiontext',
                    'questiontext_format',
                    'generalfeedback',
                    'generalfeedback_format',
                    'defaultgrade',
                    'penalty',
                    'hidden',
                    'idnumber',
                    'single',
                    'shuffleanswers',
                    'answernumbering',
                    'showstandardinstruction',
                    'correctfeedback',
                    'correctfeedback_format',
                    'partiallycorrectfeedback',
                    'partiallycorrectfeedback_format',
                    'incorrectfeedback',
                    'incorrectfeedback_format'
                ]
            ],
        ]);

        $id = $response->json('data')[0]['id'];

        $response = $this->getJson('api/questions/'.$id,
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'category_id',
                'type',
                'name',
                'questiontext',
                'questiontext_format',
                'generalfeedback',
                'generalfeedback_format',
                'defaultgrade',
                'penalty',
                'hidden',
                'idnumber',
                'single',
                'shuffleanswers',
                'answernumbering',
                'showstandardinstruction',
                'correctfeedback',
                'correctfeedback_format',
                'partiallycorrectfeedback',
                'partiallycorrectfeedback_format',
                'incorrectfeedback',
                'incorrectfeedback_format'
            ],
        ]);
    }

    public function testCreateQuestionSuccessfully()
    {
        $this->getToken();

        $question = Question::factory()->make([
            'category_id' => '91b0106b-4280-4162-9797-d1b423236c7c',
        ]);

        $response = $this->postJson('api/questions', $question->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'category_id',
            'type',
            'name',
            'questiontext',
            'questiontext_format',
            'generalfeedback',
            'generalfeedback_format',
            'defaultgrade',
            'penalty',
            'hidden',
            'idnumber',
            'single',
            'shuffleanswers',
            'answernumbering',
            'showstandardinstruction',
            'correctfeedback',
            'correctfeedback_format',
            'partiallycorrectfeedback',
            'partiallycorrectfeedback_format',
            'incorrectfeedback',
            'incorrectfeedback_format'
        ]);
    }

    public function testCreateQuestionsSuccessfully()
    {
        $this->getToken();

        $question_1 = Question::factory()->make([
            'category_id' => '91b0106b-4280-4162-9797-d1b423236c7c',
        ]);

        $question_2 = Question::factory()->make([
            'category_id' => '91b0106b-4280-4162-9797-d1b423236c7c',
        ]);

        $response = $this->postJson('api/questions', [$question_1->toArray(), $question_2->toArray()],
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            [
                'id',
                'category_id',
                'type',
                'name',
                'questiontext',
                'questiontext_format',
                'generalfeedback',
                'generalfeedback_format',
                'defaultgrade',
                'penalty',
                'hidden',
                'idnumber',
                'single',
                'shuffleanswers',
                'answernumbering',
                'showstandardinstruction',
                'correctfeedback',
                'correctfeedback_format',
                'partiallycorrectfeedback',
                'partiallycorrectfeedback_format',
                'incorrectfeedback',
                'incorrectfeedback_format'
            ]
        ]);
    }

    public function testCreateQuestionWithoutTokenFailed()
    {
        $question = Question::factory()->make([
            'category_id' => '91b0106b-4280-4162-9797-d1b423236c7c',
        ]);

        $response = $this->postJson('api/questions', $question->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Unauthenticated.'
        ]);
    }

    public function testCreateAndDeleteQuestionSuccessfully()
    {
        $this->getToken();

        $question = Question::factory()->make([
            'category_id' => '91b0106b-4280-4162-9797-d1b423236c7c',
        ]);

        $response = $this->postJson('api/questions', $question->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'category_id',
            'type',
            'name',
            'questiontext',
            'questiontext_format',
            'generalfeedback',
            'generalfeedback_format',
            'defaultgrade',
            'penalty',
            'hidden',
            'idnumber',
            'single',
            'shuffleanswers',
            'answernumbering',
            'showstandardinstruction',
            'correctfeedback',
            'correctfeedback_format',
            'partiallycorrectfeedback',
            'partiallycorrectfeedback_format',
            'incorrectfeedback',
            'incorrectfeedback_format'
        ]);

        $id = $response->json('id');
        $response = $this->deleteJson('api/questions/'.$id,
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(204);

    }

    public function testCreateQuestionWithInvalidQuestionIdFailed()
    {
        $this->getToken();

        $question = Question::factory()->make([
            'category_id' => '91b0106b-4280-4162-9797-d1b523236c7c',
        ]);

        $response = $this->postJson('api/questions', $question->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(404);
        $response->assertJson([
            'error' => 'Resource not found'
        ]);
    }

    public function testCreateQuestionWithOtherUserQuestionIdFailed()
    {
        $this->getToken('admin1@test.com');

        $question = Question::factory()->make([
            'category_id' => '91b0106b-4280-4162-9797-d1b423236c7c',
        ]);

        $response = $this->postJson('api/questions', $question->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(401);
        $response->assertJson([
            'error' => 'Category ID does not exist for this user.',
        ]);
    }

    public function testGetQuestionFromOtherUserFailed()
    {
        $this->getToken('admin1@test.com');

        $response = $this->getJson('api/questions/91b0106b-4280-4162-9797-d1b429236c7c',
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(404);
        $response->assertJson([
            'error' => 'Resource not found',
        ]);
    }

    public function testGetQuestionsWithOtherUser()
    {
        $this->getToken('admin1@test.com');

        $response = $this->getJson('api/questions',
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
        ]);
        self::assertTrue(sizeof($response->json('data')) == 0);
    }

    public function testUpdateQuestionSuccessfully()
    {
        $this->getToken();

        $question = Question::find('91b0106b-4280-4162-9797-d1b429236c7c');
        $question->questiontext = 'Question Updated';

        $response = $this->putJson('api/questions/91b0106b-4280-4162-9797-d1b429236c7c', $question->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'category_id',
            'type',
            'name',
            'questiontext',
            'questiontext_format',
            'generalfeedback',
            'generalfeedback_format',
            'defaultgrade',
            'penalty',
            'hidden',
            'idnumber',
            'single',
            'shuffleanswers',
            'answernumbering',
            'showstandardinstruction',
            'correctfeedback',
            'correctfeedback_format',
            'partiallycorrectfeedback',
            'partiallycorrectfeedback_format',
            'incorrectfeedback',
            'incorrectfeedback_format'
        ]);
        $response->assertJsonFragment([
            'questiontext' => 'Question Updated'
        ]);
    }

    public function testUpdateQuestionOfOtherUserFailed()
    {
        $this->getToken('admin1@test.com');

        $question = Question::find('91b0106b-4280-4162-9797-d1b429236c7c');
        $question->text = 'Question Updated';

        $response = $this->putJson('api/questions/91b0106b-4280-4162-9797-d1b429236c7c', $question->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(404);
        $response->assertJson([
            'error' => 'Resource not found'
        ]);
    }

    public function testCreateQuestionWithCategoryMoodleIDSuccessfully()
    {
        $this->getToken();

        $question = Question::factory()->make([
            'category_moodle_id' => 2,
        ]);

        $response = $this->postJson('api/questions', $question->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'category_id',
            'type',
            'name',
            'questiontext',
            'questiontext_format',
            'generalfeedback',
            'generalfeedback_format',
            'defaultgrade',
            'penalty',
            'hidden',
            'idnumber',
            'single',
            'shuffleanswers',
            'answernumbering',
            'showstandardinstruction',
            'correctfeedback',
            'correctfeedback_format',
            'partiallycorrectfeedback',
            'partiallycorrectfeedback_format',
            'incorrectfeedback',
            'incorrectfeedback_format'
        ]);
    }

    public function testCreateQuestionWithMoodleIdAlreadyExistsFailed()
    {
        $this->getToken();

        $question = Question::factory()->make([
            'category_id' => '91b0106b-4280-4162-9797-d1b423236c7c',
            'moodle_id' => 1,
        ]);

        $response = $this->postJson('api/questions', $question->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'category_id',
            'type',
            'name',
            'questiontext',
            'questiontext_format',
            'generalfeedback',
            'generalfeedback_format',
            'defaultgrade',
            'penalty',
            'hidden',
            'idnumber',
            'single',
            'shuffleanswers',
            'answernumbering',
            'showstandardinstruction',
            'correctfeedback',
            'correctfeedback_format',
            'partiallycorrectfeedback',
            'partiallycorrectfeedback_format',
            'incorrectfeedback',
            'incorrectfeedback_format'
        ]);

        $question = Question::factory()->make([
            'category_id' => '91b0106b-4280-4162-9797-d1b423236c7c',
            'moodle_id' => 1,
        ]);

        $response = $this->postJson('api/questions', $question->toArray(),
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(401);
        $response->assertJsonStructure([
            'error',
        ]);
    }

    public function testCreateQuestionsWithCategoryMoodleIdSuccessfully()
    {
        $this->getToken();

        $question_1 = Question::factory()->make([
            'category_moodle_id' => 2,
        ]);

        $question_2 = Question::factory()->make([
            'category_moodle_id' => 2,
        ]);

        $response = $this->postJson('api/questions', [$question_1->toArray(), $question_2->toArray()],
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            [
                'id',
                'category_id',
                'type',
                'name',
                'questiontext',
                'questiontext_format',
                'generalfeedback',
                'generalfeedback_format',
                'defaultgrade',
                'penalty',
                'hidden',
                'idnumber',
                'single',
                'shuffleanswers',
                'answernumbering',
                'showstandardinstruction',
                'correctfeedback',
                'correctfeedback_format',
                'partiallycorrectfeedback',
                'partiallycorrectfeedback_format',
                'incorrectfeedback',
                'incorrectfeedback_format'
            ]
        ]);
    }

    public function testCreateQuestionsWithMoodleIdAlreadyExistsFailed()
    {
        $this->getToken();

        $question_1 = Question::factory()->make([
            'category_moodle_id' => 2,
            'moodle_id' => 1,
        ]);

        $question_2 = Question::factory()->make([
            'category_moodle_id' => 2,
            'moodle_id' => 1,
        ]);

        $response = $this->postJson('api/questions', [$question_1->toArray(), $question_2->toArray()],
            ['Authorization' => 'Bearer '.$this->token]);
        $response->assertStatus(401);
        $response->assertJsonStructure([
            'error',
        ]);
    }

    public function testGetQuestionByMoodleIdSuccessfully()
    {
        $this->getToken();

        $response = $this->getJson('api/questions?moodle_id=2',
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'category_id',
                'type',
                'name',
                'questiontext',
                'questiontext_format',
                'generalfeedback',
                'generalfeedback_format',
                'defaultgrade',
                'penalty',
                'hidden',
                'idnumber',
                'single',
                'shuffleanswers',
                'answernumbering',
                'showstandardinstruction',
                'correctfeedback',
                'correctfeedback_format',
                'partiallycorrectfeedback',
                'partiallycorrectfeedback_format',
                'incorrectfeedback',
                'incorrectfeedback_format'
            ],
        ]);
    }

    public function testGetQuestionByMoodleIdFailed()
    {
        $this->getToken();

        $response = $this->getJson('api/questions?moodle_id=1',
            ['Authorization' => 'Bearer '.$this->token]);

        $response->assertStatus(404);
        $response->assertJsonStructure([
            'error',
        ]);
    }
}
