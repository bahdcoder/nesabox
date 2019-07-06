<?php

namespace App\Http\ServerProviders;

use Aws\Ec2\Ec2Client;
use Aws\Exception\AwsException;

trait InteractsWithAws
{
    /**
     * This method tests the aws connection with the token provided
     *
     * @param string $token
     *
     * @return boolean
     */
    public function verifySuccessfulAwsConnection(string $key, string $secret)
    {
        try {
            return $this->getAwsConnectionInstance(
                $key,
                $secret
            )->describeRegions();
        } catch (AwsException $e) {
            return false;
        }
    }

    /**
     * This method creates a new connection to aws
     *
     * @return object
     */
    public function getAwsConnectionInstance(
        string $key,
        string $secret,
        string $region = null
    ) {
        return new Ec2Client([
            'region' => $region ?? 'us-east-1',
            'version' => 'latest',
            'credentials' => [
                'key' => $key,
                'secret' => $secret
            ]
        ]);
    }
}
