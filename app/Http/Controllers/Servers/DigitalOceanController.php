<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;

class DigitalOceanController extends Controller
{
    /** */
    public function sizes()
    {
        $region = request()->query('region');

        if (!$region) {
            return response()->json(
                [
                    'message' => __('Region is required to get sizes.')
                ],
                400
            );
        }

        // Here, we'll fetch all the sizes for a specific region
        return response()->json($this->getDigitalOceanSizesForRegion($region));
    }
}
