<?php

namespace App\Http\Controllers;

use App\Http\Traits\HandlesSshKeys;
use App\Http\Traits\HandlesProcesses;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Http\ServerProviders\InteractsWithAws;
use App\Exceptions\InvalidProviderCredentials;
use App\Http\ServerProviders\InteractsWithVultr;
use App\Http\ServerProviders\InteractWithLinode;
use Illuminate\Routing\Controller as BaseController;
use App\Http\ServerProviders\InteractsWithDigitalOcean;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests,
        DispatchesJobs,
        HandlesSshKeys,
        HandlesProcesses,
        ValidatesRequests,
        InteractsWithDigitalOcean,
        InteractsWithVultr,
        InteractsWithAws,
        InteractWithLinode;

    /**
     *
     * This method checks if a user has credentials for a provider. If Yes,
     * returns the credential. If no, it responds with a 400
     *
     * @return array
     */
    public function getAuthUserCredentialsFor(
        string $provider,
        $credential_id = null
    ) {
        $credential = auth()
            ->user()
            ->getDefaultCredentialsFor($provider, $credential_id);

        if (
            !isset($credential->apiToken) &&
            !isset($credential->apiKey) &&
            !isset($credential->accessToken)
        ) {
            throw new InvalidProviderCredentials($provider);
        }

        return $credential;
    }
}
