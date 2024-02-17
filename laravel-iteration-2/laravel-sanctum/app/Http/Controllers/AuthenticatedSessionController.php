<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    public function login(LoginRequest $request){
        $dataUser = $request->authenticate();
        /** @var User $dataUser */
        $token = $dataUser->createToken('token')->plainTextToken;
        Log::info($token);
        return $token;
        // return response()->json(['token' => $token], 200);

    }

    public function logout(){

    }
}
