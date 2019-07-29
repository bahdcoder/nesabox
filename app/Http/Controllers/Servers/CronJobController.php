<?php

namespace App\Http\Controllers\Servers;

use App\Server;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Servers\AddCronJobRequest;
use Illuminate\Console\Scheduling\ManagesFrequencies;
use App\Http\Resources\ServerResource;
use App\Scripts\Server\AddCronJob;
use App\Job;
use App\Scripts\Server\CronJobOutput;
use App\Scripts\Server\DeleteCronJob;

class CronJobController extends Controller
{
    use ManagesFrequencies;

    /**
     * The cron expression representing the job's frequency.
     *
     * @var string
     */
    public $expression = '* * * * *';

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddCronJobRequest $request, Server $server)
    {
        $this->authorize('view', $server);

        $job = $server->jobs()->create([
            'user' => $request->user,
            'command' => $request->command,
            'frequency' => $request->frequency,
            'cron' =>
                $request->frequency === 'custom'
                    ? $request->cron
                    : $this->{$request->frequency}()->expression
        ]);

        $job->rollSlug();

        $process = (new AddCronJob($server, $job))->run();

        if ($process->isSuccessful()) {
            $job->update([
                'status' => 'active'
            ]);
        }

        return new ServerResource($server->fresh());
    }

    /**
     * Get the log for the specified cron job
     *
     * @param  \App\Job  $job
     * @param \App\Server $server
     * @return \Illuminate\Http\Response
     */
    public function log(Server $server, Job $job)
    {
        $process = (new CronJobOutput($server, $job))->run();

        if (!$process->isSuccessful()) {
            abort(400);
        }

        return $process->getOutput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Job  $job
     * @param \App\Server $server
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Server $server, Job $job)
    {
        $process = (new DeleteCronJob($server, $job))->run();

        if (!$process->isSuccessful()) {
            abort(400);
        }

        $job->delete();

        return new ServerResource($server->fresh());
    }
}
