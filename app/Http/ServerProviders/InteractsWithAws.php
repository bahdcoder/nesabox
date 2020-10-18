<?php

namespace App\Http\ServerProviders;

use Aws\Ec2\Ec2Client;
use Aws\Exception\AwsException;
use App\Exceptions\InvalidProviderCredentials;

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
     *
     * Fetch Aws regions available to user
     *
     * @return array|boolean
     */
    public function describeAwsRegions()
    {
        $credential = auth()
            ->user()
            ->getDefaultCredentialsFor(AWS);

        try {
            return $this->getAwsConnectionInstance(
                $credential->apiKey,
                $credential->apiSecret
            )
                ->describeRegions()
                ->get('Regions');
        } catch (AwsException $e) {
            return false;
        }
    }

    /**
     * Fetch the vpc details
     *
     * @return array|boolean
     */
    public function getRegionVpc(string $region, string $credentialId)
    {
        $credential = auth()
            ->user()
            ->getDefaultCredentialsFor(AWS, $credentialId);

        if (!isset($credential->apiSecret)) {
            throw new InvalidProviderCredentials(AWS);
        }

        try {
            return $this->getAwsConnectionInstance(
                $credential->apiKey,
                $credential->apiSecret
            )->describeVpcs([
                'VpcIds' => []
            ]);
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
