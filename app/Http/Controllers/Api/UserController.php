<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $data = $user->find(Auth::id());

        return $this->sendResponse('success', 'Profile berhasil dimuat', $data, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request)
    {
        $user = User::find(Auth::id());

        if ($request->input('email')) {
            $user->update(['email' => 'update']);
        }

        $this->validate($request, ['email' => 'email|unique:users']);

        $user->update([
            'name' => $request->input('name') != null ? request('name')  : $user->name,
            'email' => $request->input('email') != null ? request('email') : $user->email,
        ]);

        return $this->sendResponse('success', 'Data user berhasil diupdate', $user, 202);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function updatePassword(Request $request)
    {
        $this->validate($request, ['oldPassword' => 'required', 'newPassword' => 'required']);

        $user = User::find(Auth::id());

        if (Hash::check($request->input('oldPassword'), $user->password)) {

            // jika password baru sama dengan password lama
            if ($request->input('oldPassword') == $request->input('newPassword')) {
                return $this->sendResponse('failed', 'Password baru tidak boleh sama dengan password lama', null, 400);
            }

            $user->update([
                'password' => Hash::make($request->input('newPassword'))
            ]);
        } else {
            return $this->sendResponse('failed', 'Password lama tidak cocok', null, 400);
        }

        return $this->sendResponse('success', 'Password berhasil diubah', $user, 202);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $this->validate($request, [
            'password' => 'required'
        ]);

        $user = User::find(Auth::id());

        if (Hash::check($request->input('password'), $user->password)) {
            $user->delete();
        } else {
            return $this->sendResponse('failed', 'Password tidak cocok', null, 400);
        }

        return $this->sendResponse('success', 'User Berhasil dihapus', null, 200);
    }
}
