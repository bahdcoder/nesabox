<?php

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

if (!function_exists('cached_provider_data')) {
    /**
     * Get cached provider data from json files
     *
     * @return array
     */
    function cached_provider_data($provider)
    {
        switch ($provider) {
            case DIGITAL_OCEAN:
                return Cache::rememberForever(
                    'digital-ocean-data',
                    function () {
                        return json_decode(
                            file_get_contents(
                                base_path('provider-data/digital-ocean.json')
                            )
                        );
                    }
                );
            case VULTR:
                return Cache::rememberForever('vultr-data', function () {
                    return json_decode(
                        file_get_contents(base_path('provider-data/vultr.json'))
                    );
                });
            case LINODE:
                return Cache::rememberForever('linode-data', function () {
                    return json_decode(
                        file_get_contents(
                            base_path('provider-data/linode.json')
                        )
                    );
                });
        }

        return [];
    }
}

if (!function_exists('str_root_password')) {
    /**
     * Returns a random root password
     *
     * @return string
     *
     * */
    function str_root_password()
    {
        return str_shuffle(str_random(10) . '{}[]%&');
    }
}

if (!function_exists('get_ram')) {
    /**
     * Get RAM in GB
     *
     * @param integer $value
     *
     * @return string
     */
    function get_ram($value)
    {
        if ($value < 1024) {
            return "{$value}MB";
        }

        $inGb = floor($value / 1024);

        return "{$inGb}GB";
    }
}

if (!function_exists('get_disk')) {
    /**
     * Get RAM in GB
     *
     * @param integer $value
     *
     * @return string
     */
    function get_disk($value)
    {
        $inGb = floor($value / 1024);

        return "{$inGb}GB";
    }
}

if (!function_exists('get_region_name')) {
    /**
     * Get the name of a region depending
     * on the provider passed
     *
     * @return string
     */
    function get_region_name(string $provider, $id)
    {
        $data = cached_provider_data($provider);

        if ($provider === DIGITAL_OCEAN) {
            return collect($data->regions)->first(function ($region) use ($id) {
                return $region->slug = $id;
            })->name;
        }

        if ($provider === VULTR) {
            return collect($data->regions)->first(function ($region) use ($id) {
                return $region->DCID = $id;
            })->name;
        }

        if ($provider === LINODE) {
            return collect($data->regions)->first(function ($region) use ($id) {
                return $region->id = $id;
            })->name;
        }

        return null;
    }
}


if (!function_exists('github_api')) {
    /**
     * Get an http client for github api interaction
     * 
     * @return \Guzzle\Client
     */
    function github_api($apiToken = null) {
        return new HttpClient([
            'base_uri' => 'https://api.github.com',
            'headers' => [
                'Authorization' =>
                    'token ' . ($apiToken ? $apiToken : auth()->user()->source_control['github'])
            ]
        ]);
    }
}
