<?php

namespace App\Http\Controllers\Auth;

use App\Sshkey;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\Users\AddSshkeyRequest;

class SshkeysController extends Controller
{
    /**
     *
     * Store a new sshkey on user account
     *
     * @return \Illuminate\Http\Response
     */
    public function store(AddSshkeyRequest $request)
    {
        auth()
            ->user()
            ->sshkeys()
            ->create([
                'name' => $request->name,
                'key' => $request->key,
                'is_profile_key' => true,
                'status' => STATUS_ACTIVE
            ]);

        return new UserResource(
            auth()
                ->user()
                ->fresh()
        );
    }

    /**
     *
     * Delete an sshkey on user account
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sshkey $key)
    {
        return new UserResource(
            auth()
                ->user()
                ->fresh()
        );

        if ($key->user_id !== auth()->user()->id) {
            abort(401);
        }

        $key->delete();

        return new UserResource(
            auth()
                ->user()
                ->fresh()
        );
    }
}
