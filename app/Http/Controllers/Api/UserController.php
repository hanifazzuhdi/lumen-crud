<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash};
use App\Http\Controllers\Controller;

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
        $data = $user->findOrFail(10);

        return $this->sendResponse('success', 'profile successfully loaded', $data, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        if ($request->email) {
            $user->update(['email' => 'update']);
        }

        $this->validate($request, ['email' => 'email|unique:users']);

        $user->update([
            'name' => $request->name != null ? request('name')  : $user->name,
            'email' => $request->email != null ? request('email') : $user->email,
        ]);

        return $this->sendResponse('success', 'data has been updated successfully', $user, 200);
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

        $user = User::findOrFail(Auth::id());

        if (Hash::check($request->oldPassword, $user->password)) {

            // jika password baru sama dengan password lama
            if ($request->oldPassword == $request->newPassword) {
                return $this->sendResponse('failed', 'The new password cannot be the same as the old password', null, 400);
            }

            $user->update([
                'password' => Hash::make($request->newPassword)
            ]);
        } else {
            return $this->sendResponse('failed', "old passwords don't match", null, 400);
        }

        return $this->sendResponse('success', 'password changed successfully', $user, 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     */

    public function destroy(Request $request)
    {
        $this->validate($request, [
            'password' => 'required'
        ]);

        $user = User::findOrFail(Auth::id());

        if (Hash::check($request->password, $user->password)) {
            $user->delete();
        } else {
            return $this->sendResponse('failed', "password don't match", null, 400);
        }

        return $this->sendResponse('success', 'account deleted successfully', $user, 200);
    }
}
