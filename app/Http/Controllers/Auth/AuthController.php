<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     *
     */
    public function login(Request $request, User $user)
    {
        $credentials = $request->only('email', 'password');

        $user = User::where('email', request('email'))->first();

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Username atau Password Salah'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $user->update([
            'api_token' => $token
        ]);

        return response()->json(compact('user', 'token'), 202);
    }

    /**
     * Function for register new user
     *
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required'
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        return $this->sendResponse('success', 'User Berhasil dibuat', $user, 201);
    }

    // Login chect api_token from db
    // /**
    //  * Function for login
    //  *
    //  */

    // public function login(Request $request)
    // {
    //     $user = User::where('email', $request->input('email'))->firstOrFail();

    //     if (Hash::check($request->input('password'), $user->password)) {
    //         $user->update([
    //             'api_token' => base64_encode(Str::random(40))
    //         ]);

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Login Berhasil',
    //             'data' => [
    //                 'user' => $user,
    //                 'token' => $user->api_token
    //             ]
    //         ], 200)->header('Accept', 'application/json');
    //     } else {
    //         return response()->json([
    //             'status' => 'failed',
    //             'message' => 'Login Gagal Password Anda Salah',
    //         ], 400)->header('Accept', 'application/json');
    //     }
    // }
}
