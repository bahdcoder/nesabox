<?php

namespace Bahdcoder\Vultr\Api;

class Api
{
    /**
     * Return json object of response
     *
     * @return object
     */
    public function getResult($response)
    {
        return json_decode($response->getBody());
    }
}
