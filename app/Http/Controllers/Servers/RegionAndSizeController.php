<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class RegionAndSizeController extends Controller
{
    /**
     * Fetch all regions from different providers
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            AWS => [
                'regions' => $this->awsRegions(),
                'sizes' => AWS_SIZES
            ],
            DIGITAL_OCEAN => [
                'regions' => DIGITAL_OCEAN_REGIONS
            ],
            LINODE => [
                'regions' => LINODE_REGIONS,
                'sizes' => LINODE_SIZES
            ]
        ]);
    }

    /**
     * Get aws regions
     *
     * @return array
     */
    public function awsRegions()
    {
        $user = auth()->user();

        return Cache::rememberForever("aws-regions-{$user->id}", function () {
            $regions = $this->describeAwsRegions();

            return collect($regions)->map(function ($region) {
                return [
                    'id' => $region['RegionName'],
                    'name' => AWS_REGIONS[$region['RegionName']]
                ];
            });
        });
    }
}
