<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function register(UserRegisterRequest $request) : JsonResponse{
        $data = $request->validated();
        if(User::where('username', $data['username'])->count() == 1){
            throw new HttpResponseException(response([
                "errors" => [
                    "username" =>[
                        "username already registered"
                    ]
                ]
            ],400));
        }

        $user = new User($data);
        $user->password = Hash::make($data['password']);
        $user->save();

        return (new UserResource($user))->response()->setStatusCode(201);
    }

    public function login(UserLoginRequest $request) : UserResource{
        $data = $request->validated();
        $user = User::where("username", $data["username"])->first();
        if(!$user || !Hash::check($data["password"],$user->password)){
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "username or password wrong"
                    ]
                ]
            ],401));
        }

        $user->token = Str::uuid()->toString();
        $user->save();

        return new UserResource($user);
    }

    public function get() : UserResource{
        $User = Auth::user();
        return new UserResource($User);
    }


    public function update(UserUpdateRequest $request) : UserResource{
        $data = $request->validated();

        /** @var User $user */
        $user = Auth::user();

        if(isset($data['name']))
        {
            $user->name = $data['name'];
        }

        if(isset($data['password']))
        {
            $user->password = Hash::make($data["password"]);
        }

        $user->save();
        return new UserResource($user);
    }


    public function logout(Request $requst) : JsonResponse
    {
        $user = Auth::user();
        $user->token = null;
        
        
        /** @var User $user */
        $user->save();

        return response()->json([
            "data" => true
        ]);
    }
}
