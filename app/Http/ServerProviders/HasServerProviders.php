<?php

namespace App\Http\ServerProviders;

use App\Exceptions\InvalidProviderCredentials;

trait HasServerProviders
{
    public function getCredentialProvider($credentialId)
    {
        $provider = collect([DIGITAL_OCEAN, AWS, LINODE, VULTR])->first(
            function ($cloudProvider) use ($credentialId) {
                $credential = collect($this->providers[$cloudProvider])->first(
                    function ($cred) use ($credentialId) {
                        return $credentialId === $cred['id'];
                    }
                );

                return (bool) $credential;
            }
        );

        return $provider;
    }

    public function deleteCredential($credentialId)
    {
        $provider = $this->getCredentialProvider($credentialId);

        $newCredentials = [];

        $newCredentials[$provider] = collect($this->providers[$provider])
            ->filter(function ($credential) use ($credentialId) {
                return $credential['id'] !== $credentialId;
            })
            ->all();

        $this->update([
            'providers' => array_merge($this->providers, $newCredentials)
        ]);
    }

    /**
     * Get the default credentials for a provider
     *
     * @param string $provider
     *
     * @return object
     *
     */
    public function getDefaultCredentialsFor(
        string $provider,
        string $credentialId = null
    ) {
        return (object) collect($this->providers[$provider])->first(function (
            $credential
        ) use ($credentialId) {
            if ($credentialId) {
                return $credential['id'] === $credentialId;
            }

            return $credential['default'] === true;
        });
    }

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
