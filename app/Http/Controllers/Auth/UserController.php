<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Users\UpdateProfileRequest;
use App\Http\Requests\Users\ChangePasswordRequest;

class UserController extends Controller
{
    /**
     * Show the authenticated user details
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return new UserResource(auth()->user());
    }

    /**
     * Update the authenticated user details
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProfileRequest $request)
    {
        auth()
            ->user()
            ->update([
                'name' => $request->name,
                'email' => $request->email
            ]);

        return new UserResource(
            auth()
                ->user()
                ->fresh()
        );
    }

    /**
     * Update the authenticated user password
     *
     * @return \Illuminate\Http\Response
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        auth()
            ->user()
            ->update([
                'password' => Hash::make($request->new_password)
            ]);

        return new UserResource(
            auth()
                ->user()
                ->fresh()
        );
    }

    /**
     * Regenerate the authenticated user's api token
     *
     * @return \Illuminate\Http\Response
     */
    public function apiToken()
    {
        auth()->user()->rollApiKey();

        return new UserResource(
            auth()
                ->user()
                ->fresh()
        );
    }
}
