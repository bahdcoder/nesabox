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
            case DIGITAL_OCEAN:
                if (
                    !$this->verifySuccessfulDigitalOceanConnection(
                        $request->apiToken
                    )
                ) {
                    return $this->sendCredentialsCheckFailure();
                }

                $this->updateUserForDigitalOCean($request);

                break;
            case VULTR:
                if (!$this->verifySuccessfulVultrConnection($request->apiKey)) {
                    return $this->sendCredentialsCheckFailure();
                }

                $this->updateUserForVultr($request);

                break;
            case AWS:
                if (
                    !$this->verifySuccessfulAwsConnection(
                        $request->apiKey,
                        $request->apiSecret
                    )
                ) {
                    return $this->sendCredentialsCheckFailure();
                }

                $this->updateUserForAws($request);

                break;
            case LINODE:
                if (
                    !$this->verifySuccessfullLinodeConnection(
                        $request->accessToken
                    )
                ) {
                    return $this->sendCredentialsCheckFailure();
                }

                $this->updateUserForLinode($request);

                break;
            default:
                break;
        endswitch;

        return new UserResource(
            auth()
                ->user()
                ->refresh()
        );
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
                DIGITAL_OCEAN => array_merge(
                    $user->providers[DIGITAL_OCEAN],
                    [
                        [
                            'id' => Str::uuid(),
                            'profileName' => $request->profileName,
                            'apiToken' => $request->apiToken,
                            'default' =>
                                count($user->providers[DIGITAL_OCEAN]) === 0
                        ]
                    ]
                )
            ])
        ]);
    }

    /**
     * Update user linode account credentials
     *
     * @return Illuminate\Http\Response
     */
    public function updateUserForLinode(AddServerProviderRequest $request)
    {
        $user = auth()->user();

        $user->update([
            'providers' => array_merge($user->providers, [
                LINODE => array_merge($user->providers[LINODE], [
                    [
                        'id' => Str::uuid(),
                        'profileName' => $request->profileName,
                        'accessToken' => $request->accessToken,
                        'default' => count($user->providers[LINODE]) === 0
                    ]
                ])
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
        ], 400);
    }

    /**
     * Update user aws account credentials
     *
     * @return Illuminate\Http\Response
     */
    public function updateUserForAws(AddServerProviderRequest $request)
    {
        $user = auth()->user();

        $user->update([
            'providers' => array_merge($user->providers, [
                AWS => array_merge($user->providers[AWS], [
                    [
                        'id' => Str::uuid(),
                        'apiKey' => $request->apiKey,
                        'apiSecret' => $request->apiSecret,
                        'profileName' => $request->profileName,
                        'default' => count($user->providers[AWS]) === 0
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
