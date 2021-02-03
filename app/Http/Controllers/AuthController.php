<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Function for create new user
     *
     */

    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required'
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User Berhasil dibuat',
            'data' => $user
        ], 201)->header('Accept', 'application/json');
    }

    /**
     * Function for login
     *
     */

    public function login(Request $request)
    {
        $user = User::where('email', $request->input('email'))->firstOrFail();

        if (Hash::check($request->input('password'), $user->password)) {
            $user->update([
                'api_token' => base64_encode(Str::random(40))
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Login Berhasil',
                'data' => [
                    'user' => $user,
                    'token' => $user->api_token
                ]
            ], 200)->header('Accept', 'application/json');
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Login Gagal Password Anda Salah',
            ], 400)->header('Accept', 'application/json');
        }
    }
}
