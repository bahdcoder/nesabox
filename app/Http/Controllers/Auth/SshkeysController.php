<?php

namespace App\Http\Controllers\Auth;

use App\Sshkey;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\Users\AddSshkeyRequest;
use Illuminate\Support\Facades\Log;

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
    public function destroy($key)
    {
        $sshkey = Sshkey::where([
            'id' => $key,
            'user_id' => auth()->user()->id
        ])->firstOrFail();

        $sshkey->delete();

        return new UserResource(
            auth()
                ->user()
                ->fresh()
        );
    }
}
