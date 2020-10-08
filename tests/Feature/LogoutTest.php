<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    public function testLogoutSuccessful()
    {
        User::factory()->create([
            'email' => 'test@user.com',
            'password' => bcrypt('password'),
        ]);

        $payload = ['email' => 'test@user.com', 'password' => 'password', 'password_confirmation' => 'password'];

        $response_login = $this->postJson('api/login', $payload);

        $response_login->assertStatus(200);
        $response_login->assertJsonStructure([
            'token',
        ]);

        $response = $this->postJson('api/logout', [],
                                        ['Authorization' => 'Bearer '.$response_login->json('token')]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'You have been successfully logged out!'
        ]);
    }

    public function testLogoutWithoutToken()
    {
        $response = $this->postJson('api/logout', [],
            ['Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5MWI0NTRiZi02NWZlLTQ5ZWQtYWRhNi03YThjMWNhMGZiMjIiLCJqdGkiOiIzZmM5MGNiZDUwYzg0OGNiYWM2ZDQ0MmNmY2NkZWQ2ZDIwMGVhODE4OGJiZTg0YWExNGJmMWVmMGQ3NzMwMDg5NDU2ZGQyODhhZmE0NGZhOCIsImlhdCI6MTYwMjAzNzEwNCwibmJmIjoxNjAyMDM3MTA0LCJleHAiOjE2MzM1NzMxMDMsInN1YiI6IjkxYjM1ODc5LWM1YjEtNDg3Ni1hNTBmLWUzN2E3OWE5OGNiYiIsInNjb3BlcyI6W119.t0G1vfMhxXNmK1oupZh360rcGO8cDwEkLNNurmgn6nZT5Go5P-U7zC0X6bT_V-e_AnEO4wG9qDhuifCCe-Lj0fsq6JoLkM5ZOn9tUrUnN8RZZ055pgVgVD3gn20dhmqiACSRyPz_dqE_5dfKnofdoqgaHP3VxNDIFoF58KVQhil9pNFpCFp45L_jJSAjkkhCQi4DHeRvXcVMox32FNk7M3kGmNY4T30Ul2C7KNoCKh99_AEul1G_xdSXMA0MeRwp8uB48k1lh5V0B81t4y6L3Yr9CJeNM3d8IqG_LZaJAzquAbNYLLmcjZMnwGaRFgLhYzcsSESJ_oVpyHod47oL3r4EO0z8AfMO7K_jp2yk33K0YXo9TBtdH_MZQ-yInU5C1fkitVhubBN9w2YY8Chhdq3Z48HxqkizJoXkD3Z7z3uzsLVsYJ7B53yfWJLMoflBDTM5WkuFJr4K6UqYyiZqk9cOi5yIqTyMOLVtxAaexhh6lY8X0uP4a8nnA0RHWbARjvpn84JE_nMYGPmdKyaFopdItyeaN6HjvKSIUHIBwhmI07h2LHVCcs9AecMD2lFhKqWISbDcBuExkvt_lDt5-2JoabVtmv2IX7PyLJWA8Cv3N6C4a5j4bhQoDenLA5WDggZyfznKI2MPSID85sfDozZY68H28jO96eJpF4v1IDw']);

        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Unauthenticated.'
        ]);
    }
}
