<?php

namespace Tests\Fakes;

use Symfony\Component\Process\Process as SymphonyProcess;

class Process extends SymphonyProcess
{
    /**
     * Runs the process.
     *
     * The callback receives the type of output (out or err) and
     * some bytes from the output in real-time. It allows to have feedback
     * from the independent process during execution.
     *
     * The STDOUT and STDERR are also available after the process is finished
     * via the getOutput() and getErrorOutput() methods.
     *
     * @param callable|null $callback A PHP callback to run whenever there is some
     *                                output available on STDOUT or STDERR
     *
     * @return int The exit status code
     *
     * @throws RuntimeException When process can't be launched
     * @throws RuntimeException When process stopped after receiving signal
     * @throws LogicException   In case a callback is provided and output has been disabled
     *
     * @final
     */
    public function run(callable $callback = null, array $env = []): int
    {
        return 0;
    }

    /**
     * Checks if the process ended successfully.
     *
     * @return bool true if the process ended successfully, false otherwise
     */
    public function isSuccessful()
    {
        return true;
    }
}
