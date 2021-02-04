<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        return $this->middleware('jwt.auth');
    }

    public function show()
    {
        $user = User::find(Auth::id());

        return response()->json([
            'status' => 'success',
            'message' => 'data berhasil dimuat',
            'data'  => $user
        ]);
    }
}
