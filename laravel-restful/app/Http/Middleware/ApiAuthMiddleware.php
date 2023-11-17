<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class ApiAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header("Authorization");
        $authenticate = true;

        $user = User::where("token", $token)->first();
        if(!$user)
        {
            $authenticate = false;
        }

        Auth::login($user); //* login need authenticable
        //Auth::user() --> get who is login 

        if(!$token)
        {
            $authenticate = false;
        }
        if($authenticate)
        {
            return $next($request);
        }else{
            return response()->json([
                    'errors' =>[
                        "message" =>["unauthorized"]
                    ]
            ])->setStatusCode(200);
        }
    }
}
