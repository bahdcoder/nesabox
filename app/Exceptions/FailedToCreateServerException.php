<?php

namespace App\Exceptions;

use App\Server;
use Exception;

class FailedToCreateServerException extends Exception
{
    public $server;

    public $exception;

    public function __construct(Server $server, $exception)
    {
        $this->server = $server;
        $this->exception = $exception;
    }

    /**
     * Render this exception
     *
     * @return \Illuminate\Http\Response
     */
    public function render()
    {
        $this->server->explode();

        return response()->json(
            [
                'message' => $this->exception
            ],
            400
        );
    }
}
