<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request){
        $this->validateLogin($request);

        if (Auth::attempt($request->only('email', 'password'))){
            return response()->json([
                "message" => "Login successful",
                "token" => $request->user()->createToken($request->device)->plainTextToken,
            ], 200);
        }

        return response()->json([
            "message" => "Unathorized",
        ], 401);
    }

    public function validateLogin(Request $request){
        $this->validate($request, [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'device' => 'required|string',
        ]);
    }
}
