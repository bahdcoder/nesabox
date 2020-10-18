<?php
namespace App\Http\SourceControlProviders;
use GuzzleHttp\Exception\GuzzleException;
trait InteractsWithGitlab
{
    /**
     *
     * Add public key to user github account
     *
     * @return object
     */
    public function addGitlabPublicKey($title, $key, $apiToken)
    {
        return json_decode(
            (string) gitlab_api($apiToken)
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
