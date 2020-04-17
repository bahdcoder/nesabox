<?php

namespace App\Http\SourceControlProviders;

use App\Site;
use App\User;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use iDimensionz\HttpClient\Guzzle\GuzzleHttpClient;

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

    /**
     * 
     */
    public function unlinkGithubSourceControl($accessToken)
    {
        $clientId = config('services.github.client_id');
        $clientSecret = config('services.github.client_secret');

        return (new HttpClient([
            'base_uri' => 'https://api.github.com',
            'auth' => [
                $clientId,
                $clientSecret
            ],
        ]))->delete("/applications/{$clientId}/tokens/{$accessToken}");
    }

    /**
     * Add a webhook to the git repository
     *
     * @return object
     */
    public function addGithubPushWebhook(Site $site, User $user)
    {
        return json_decode(
            (string) github_api()
                ->post("/repos/{$site->repository}/hooks", [
                    'json' => [
                        'name' => 'web',
                        'events' => ['push'],
                        'config' => [
                            'content_type' => 'json',
                            'url' =>
                                config('app.url') .
                                route(
                                    'github-webhooks',
                                    [
                                        $site->id,
                                        'api_token' => $user->api_token
                                    ],
                                    false
                                ),
                            'insecure_ssl' => app()->environment('production')
                                ? 0
                                : 1
                        ]
                    ]
                ])
                ->getBody()
        );
    }

    /**
     * Delete a webhook from the git repository
     *
     * @return object
     */
    public function deleteGithubPushWebhook(Site $site)
    {
        return github_api()->delete(
            "/repos/{$site->repository}/hooks/{$site->push_to_deploy_hook_id}"
        );
    }
}
