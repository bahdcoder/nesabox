<?php

namespace App\Scripts\Server;

use App\Server;
use App\Scripts\Base;

class VerifyIsReady extends Base
{
    /**
     * The server to be initialized.
     *
     * @var \App\Server
     */
    public $server;

    /**
     * Initialize this class
     *
     * @return void
     */
    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    /**
     * Generate the init script
     *
     * @return string
     */
    public function generate()
    {
        return <<<EOD
abort() {
    echo
    echo "  $@" 1>&2
    echo
    exit 1
}

nginx -V
test $? -eq 0 || abort Verification Failed. Server not ready.

redis-cli --version
test $? -eq 0 || abort Verification Failed. Server not ready.    

node -v
test $? -eq 0 || abort Verification Failed. Server not ready.
EOD;
    }
}
