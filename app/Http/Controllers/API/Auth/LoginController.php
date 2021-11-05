<?php

namespace App\Http\Controllers\API\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    /**
     * make new instance of the class
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['logout', 'me']);
    }

    /**
     * login and authenticate user
     * 
     * @param \App\Http\Requests\Auth\LoginRequest $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'token' => $user->createToken($request->device_name)->plainTextToken,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    /**
     * logout user
     * 
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        request()->user()->currentAccessToken()->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * get authenticated user
     * 
     * @return \Illuminate\Http\Response
     */
    public function me()
    {
        return response()->json(['user' => auth()->user()]);
    }
}
