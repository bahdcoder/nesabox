<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;

class AwsController extends Controller
{
    /**
     * Fetch vpus for a specific region
     *
     * @return \Illuminate\Http\Response
     */
    public function vpc()
    {
        $region = request()->query('region');
        $credentialId = request()->query('credential_id');

        if (!$region) {
            return response()->json(
                [
                    'message' => __('Region is required.')
                ],
                400
            );
        }

        if (!$credentialId) {
            return response()->json(
                [
                    'message' => __('Credential ID is required.')
                ],
                400
            );
        }

        return response()->json([
            'message' => $this->getRegionVpc($region, $credentialId)
        ]);
    }
}
