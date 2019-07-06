<?php

namespace App\Http\Controllers\Settings;

use Socialite;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class SourceControlProvidersController extends Controller
{
    /**
     * Redirect the user to the current provider authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRedirectUrl(string $provider)
    {
        return response()->json([
            'url' => Socialite::driver($provider)
                ->stateless()
                ->scopes($this->getProviderScopes($provider))
                ->redirect()
                ->getTargetUrl()
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
        $user = auth()->user();

        $userDetails = Socialite::driver($provider)
            ->stateless()
            ->user();

        $data = [];
        $data[$provider] = $userDetails->token;

        $user->update([
            'source_control' => array_merge($user->source_control, $data)
        ]);

        return new UserResource($user->refresh());
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

        $user->update([
            'source_control' => array_merge($user->source_control, $data)
        ]);

        return new UserResource($user->refresh());
    }
}
