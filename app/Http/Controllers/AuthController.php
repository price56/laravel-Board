<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\MeRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //register
    public function register(RegisterRequest $request): string
    {
        return User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password'),),
        ]);
    }
    // login

    /**
     * @throws AuthenticationException
     */
    public function login(LoginRequest $request): string
    {
        $user = User::where('email',$request->get('email'))->firstOrFail();
        if (!Hash::check($request->get('password'), $user->password)) {
            throw new AuthenticationException('password not match');
        }
        $token = $user->createToken('');

        return $token->plainTextToken;
    }

    public function me(MeRequest $request)
    {
        return $request->user();
    }

    public function logout(MeRequest $request)
    {
        $request->user()->tokens()->delete();

        return 'success';
    }
}
