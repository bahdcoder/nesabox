<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Auth\Events\Registered;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
        /**
     * Obtain the user information from provider.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        $userDetails = Socialite::driver($provider)
            ->stateless()
            ->user();

        // Find a matching user from our database
        $user = User::where('email', $userDetails->email)->first();

        // TODO: remove when we're out of BETA
        if (! in_array($user->email, [
            'bahdcoder@gmail.com'
        ])) {
            return response()->json([
                'message' => 'We are still in beta, and it is still invite only. Please visit nesabox.com to get notified when we launch.'
            ], 400);
        }

        if (! $user) {
            $user = User::create([
                'name' => $userDetails->name,
                'email' => $userDetails->email,
                'photo_url' => $userDetails->avatar,
                'auth_token' => $userDetails->token,
                'auth_provider' => 'github',
                'providers' => [
                    'digital-ocean' => [],
                    'vultr' => [],
                    'aws' => [],
                    'linode' => []
                ],
                'source_control' => [
                    'github' => null,
                    'bitbucket' => null,
                    'gitlab' => null
                ],
            ]);

            event(new Registered((
                $user
            )));

            $user->rollApiKey();
        }

        $data = [];
        $data[$provider] = $userDetails->token;

        $user->update([
            'source_control' => array_merge($user->source_control, $data)
        ]);

        return new UserResource($user->fresh());
    }
}
