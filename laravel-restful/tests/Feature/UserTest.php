<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use function PHPUnit\Framework\assertTrue;

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


    public function testLoginFailedPasswordWrong()
    {
        $this->seed([UserSeeder::class]);
        $this->post('api/users/login',[
            "username" => "test",
            "password" => "apa bagus ?"
        ])->assertStatus(401)
        ->assertJson([
            "errors" => [
                "message" => ["username or password wrong"],
            ]
            ]);

    }

    public function testGetSuccess()
    {
        $this->seed([UserSeeder::class]);
        $this->get("api/users/current",[
            "Authorization" => "test"
        ])->assertStatus(200);
    }


    public function testUnAuthorized()
    {
        $this->seed([UserSeeder::class]);
        $this->get("api/users/current",[])->assertStatus(401);
    }

    //* test update

    public function testUpdateSuccess()
    {
        $this->seed([UserSeeder::class]);

        $oldData = user::where("username", "test")->first();

        $this->patch("api/users/update",["password" => "baru"],[
            "Authorization" => "test"
        ])->assertStatus(200);
        $newData = user::where("username", "test")->first();
        Log::info($newData["password"]);

        $this->assertTrue(Hash::check("baru",$newData["password"]));
    }


}
