<?php

namespace App\Exceptions;

use Exception;
use App\Server;

class FailedCreatingServer extends Exception
{
    /**
     * Provider
     *
     * @var \App\Server
     */
    public $server;

    /**
     * The exception thrown
     *
     * @var GuzzleException|ProcessFailedException
     */
    public $e;

    /**
     * Initialize the provider
     *
     * @return void
     */
    public function __construct(Server $server, $e)
    {
        $this->e = $e;
        $this->server = $server;
    }

    /**
     * Render this exception
     *
     * @return \Illuminate\Http\Response
     */
    public function render()
    {
        $message = __("Failed creating {$this->server->provider}.");

        $this->cleanupServer();

        return response()->json(
            [
                'message' => $message,
                'details' => $this->e->message
            ],
            400
        );
    }

    /**
     * Delete server if creation failed from provider
     *
     * @return null
     */
    public function cleanupServer()
    {
        foreach ($this->server->databaseUsers as $databaseUser):
            foreach ($databaseUser->databases as $database):
                $database->delete();
            endforeach;

            $databaseUser->delete();
        endforeach;

        $this->server->delete();
    }
}
