<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Log;
class UserTest extends TestCase
{
    public function testRegisterSuccess(): void
    {
        $this->post('/api/users',[
            'username' => "alfons",
            'password' => "password",
            "name" => "alfonsus setiawan jacub"
            ])->assertStatus(201)
            ->assertJson([
                "data" => [
                    "username" => "alfons",
                    "name" => "alfonsus setiawan jacub"
                    ]
        ]);
    }

    public function testRegisterFailed(): void{

        $response = $this->post('/api/users',[
            'username' => "",
            'password' => "",
            "name" => ""
        ]);
        $response->assertStatus(400);
        Log::info(json_encode($response));
        $response->assertJson([
            "error" => [
                "username" => ["The username field is required."],
                "password" => ["The password field is required."],
                "name" => ["The name field is required."]
            ]
        ]);
    }

    public function testLoginSuccess()
    {
        $this->seed([UserSeeder::class]);
        $this->post('api/users/login',[
            "username" => "test",
            "password" => "test"
        ])->assertStatus(200)
        ->assertJson([
            "data" => [
                "username" => "test",
            ]
            ]);

        $user = User::where("username", "test")->first();
        self::assertNotNull($user->token);
    }
}
