<?php

use Illuminate\Support\Facades\Cache;

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

if (! function_exists('get_ram')) {
    function get_ram($value) { 
        if ($value < 1024) {
            return "{$value}MB";
        }

        $inGb = floor($value / 1024);

        return "{$inGb}GB";
    }
}

if (! function_exists('get_disk')) {
    function get_disk($value) {
        $inGb = floor($value / 1024);

        return "{$inGb}GB";
    }
}
