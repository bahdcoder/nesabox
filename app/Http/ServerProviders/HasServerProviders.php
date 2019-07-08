<?php

namespace App\Http\ServerProviders;

trait HasServerProviders
{
    /**
     * Get the default credentials for a provider
     *
     * @param $provider
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
}
