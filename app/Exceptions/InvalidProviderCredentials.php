<?php

namespace App\Exceptions;

use Exception;

class InvalidProviderCredentials extends Exception
{
    /**
     * Provider
     *
     * @var string
     */
    public $provider;

    /**
     * Initialize the provider
     *
     * @return void
     */
    public function __construct(string $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Render this exception
     *
     * @return \Illuminate\Http\Response
     */
    public function render()
    {
        return response()->json(
            [
                'message' => __("Invalid {$this->provider} credentials.")
            ],
            400
        );
    }
}
