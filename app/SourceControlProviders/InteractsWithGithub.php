<?php

namespace App\Http\SourceControlProviders;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

trait InteractsWithGithub
{
    /**
     * Fetch a github repostory
     *
     * @return object
     */
    public function fetchGithubRepositoryBranch($repository, $branch)
    {
        try {
            return json_decode(
                (string) github_api()
                    ->get('/repos/' . $repository . '/branches/' . $branch)
                    ->getBody()
            );
        } catch (GuzzleException $e) {
            return null;
        }
    }

    /**
     *
     * Add public key to user github account
     *
     * @return object
     */
    public function addGithubPublicKey($title, $key, $apiToken)
    {
        return json_decode(
            (string) github_api($apiToken)
                ->post('user/keys', [
                    'json' => [
                        'title' => $title,
                        'key' => $key
                    ]
                ])
                ->getBody()
        );
    }
}
