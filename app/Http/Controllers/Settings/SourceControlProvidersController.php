<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Auth\SocialiteController;

class SourceControlProvidersController extends Controller
{
    /**
     * Redirect the user to the current provider authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRedirectUrls()
    {
        $github = Socialite::driver('github')
            ->stateless()
            ->scopes($this->getProviderScopes('github'))
            ->redirect()
            ->getTargetUrl();

        $gitlab = Socialite::driver('gitlab')
            ->stateless()
            ->scopes($this->getProviderScopes('gitlab'))
            ->redirect()
            ->getTargetUrl();

        return response()->json([
            'github' => $github,
            'gitlab' => $gitlab
        ]);
    }

    /**
     * Get the scopes for the current provider
     *
     * @return array
     */
    public function getProviderScopes($provider)
    {
        switch ($provider):
            case 'github':
                return ['repo', 'admin:public_key'];
            case 'gitlab':
                return ['api'];
            case 'bitbucket':
                return [];
            default:
                return [];
        endswitch;
    }

    /**
     * Obtain the user information from provider.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        // this is no longer protected by the auth middleware.
        // if there is no auth user, it means we need to register the user.

        $user = auth()->user();

        if (! $user) {
            return (new SocialiteController())->handleProviderCallback('github');
        }

        $userDetails = Socialite::driver($provider)
            ->stateless()
            ->user();

        $data = [];
        $data[$provider] = $userDetails->token;

        $user->update([
            'source_control' => array_merge($user->source_control, $data)
        ]);

        return redirect('/account/source-control');
    }

    /**
     *
     * Remove the user's github token
     *
     * @return \Illuminate\Http\Response
     */
    public function unlinkProvider($provider)
    {
        $user = auth()->user();

        $data = [];
        $data[$provider] = null;

        if (!isset($user->source_control[$provider])) {
            return new UserResource($user->refresh());
        }

        $user->update([
            'source_control' => array_merge($user->source_control, $data)
        ]);

        return new UserResource($user->refresh());
    }
}
