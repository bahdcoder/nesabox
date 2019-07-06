<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\AddServerProviderRequest;
use App\Http\Resources\UserResource;

class ServerProvidersController extends Controller
{
    /**
     * Update service provider information
     *
     * @return Illuminate\Http\Response
     */
    public function store(AddServerProviderRequest $request)
    {
        switch ($request->provider):
            case 'digital-ocean':
                if (! $this->verifySuccessfulDigitalOceanConnection(
                    $request->apiToken
                )) {
                    return $this->sendCredentialsCheckFailure();
                }

                $this->updateUserForDigitalOCean($request);

                break;
            case 'vultr':
                if (! $this->verifySuccessfulVultrConnection($request->apiKey)) {
                    return $this->sendCredentialsCheckFailure(); 
                }

                $this->updateUserForVultr($request);

                break;
            case 'aws':
                if (! $this->verifySuccessfulAwsConnection($request->apiKey, $request->apiSecret)) {
                    return $this->sendCredentialsCheckFailure();
                }

                $this->updateUserForAws($request);

                break;
            default:
                break;
        endswitch;

        return new UserResource(auth()->user()->refresh());
    }

    /**
     * Update user digital ocean account credentials
     *
     * @return Illuminate\Http\Response
     */
    public function updateUserForDigitalOCean(AddServerProviderRequest $request)
    {
        $user = auth()->user();

        $user->update([
            'providers' => array_merge($user->providers, [
                'digital-ocean' => array_merge(
                    $user->providers['digital-ocean'],
                    [
                        [
                            'id' => Str::uuid(),
                            'profileName' => $request->profileName,
                            'apiToken' => $request->apiToken,
                            'default' =>
                                count($user->providers['digital-ocean']) === 0
                        ]
                    ]
                )
            ])
        ]);
    }

    /**
     * Send failure response for credentials check
     *
     * @return Illuminate\Http\Response
     */
    public function sendCredentialsCheckFailure()
    {
        $provider = request()->provider;

        return response()->json([
            'message' => "Invalid {$provider} credentials."
        ]);
    }

    /**
     * Update user aws account credentials
     *
     * @return Illuminate\Http\Response
     */
    public function updateUserForAws(AddServerProviderRequest $request) {
        $user = auth()->user();

        $user->update([
            'providers' => array_merge($user->providers, [
                'aws' => array_merge($user->providers['aws'], [
                    [
                        'id' => Str::uuid(),
                        'apiKey' => $request->apiKey,
                        'apiSecret' => $request->apiSecret,
                        'profileName' => $request->profileName,
                        'default' => count($user->providers['aws']) === 0
                    ]
                ])
            ])
        ]);
    }

    /**
     * Update user vultr account credentials
     *
     * @return Illuminate\Http\Response
     */
    public function updateUserForVultr(AddServerProviderRequest $request)
    {
        $user = auth()->user();

        $user->update([
            'providers' => array_merge($user->providers, [
                'vultr' => array_merge($user->providers['vultr'], [
                    [
                        'id' => Str::uuid(),
                        'profileName' => $request->profileName,
                        'apiKey' => $request->apiKey,
                        'default' => count($user->providers['vultr']) === 0
                    ]
                ])
            ])
        ]);
    }
}
