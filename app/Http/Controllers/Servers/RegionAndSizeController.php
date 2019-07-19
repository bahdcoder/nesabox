<?php

namespace App\Http\Controllers\Servers;

use Illuminate\Support\Str;
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
        $digitalocean = cached_provider_data(DIGITAL_OCEAN);

        $linode = cached_provider_data(LINODE);

        $vultr = cached_provider_data(VULTR);

        return response()->json([
            // AWS => [
            //     'regions' => $this->awsRegions(),
            //     'sizes' => AWS_SIZES
            // ],
            DIGITAL_OCEAN => [
                'regions' => collect($digitalocean->regions)->map(function ($region) {
                    return [
                        'label' => $region->name,
                        'value' => $region->slug
                    ];
                }),
                'sizes' => collect($digitalocean->sizes)->map(function ($size) {
                    $ram = get_ram($size->memory);
                    $core = str_plural('Core', $size->vcpus);

                    return [
                        'label' => "{$ram} RAM - {$size->vcpus} CPU {$core} {$size->disk}GB SSD",
                        'value' => $size->slug
                    ];
                })
            ],
            LINODE => [
                'regions' => collect($linode->regions)->map(function ($region) {
                    return [
                        'label' => $region->id,
                        'value' => Str::title($region->id)
                    ];
                }),
                'sizes' => collect($linode->sizes)->map(function ($size) {
                    $ram = get_ram($size->memory);
                    $gb = get_disk($size->disk);
                    $core = str_plural('Core', $size->vcpus);

                    return [
                        'label' => "{$size->label} - {$ram} {$size->vcpus} CPU {$core} {$gb} SSD",
                        'value' => $size->id
                    ];
                }),
            ],
            VULTR => [
                'regions' => collect($vultr->regions)->map(function ($region) {
                    return [
                        'label' => $region->name,
                        'value' => $region->DCID
                    ];
                }),
                'sizes' => collect(VULTR_SIZES)->map(function ($size) {
                    return [
                        'label' => $size['name'],
                        'value' => $size['id']
                    ];
                })
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
