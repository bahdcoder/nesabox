<?php

namespace App\Http\Controllers\Servers;

use App\Job;
use App\Server;
use App\Http\Controllers\Controller;
use App\Scripts\Server\CronJobOutput;
use App\Http\Resources\ServerResource;
use App\Http\Requests\Servers\AddCronJobRequest;
use App\Jobs\Servers\AddCronJob as AppAddCronJob;
use App\Jobs\Servers\DeleteCronJob;
use Illuminate\Console\Scheduling\ManagesFrequencies;

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
                    : $this->{$request->frequency}()->expression,
            'status' => STATUS_INSTALLING
        ]);

        $job->rollSlug();

        AppAddCronJob::dispatch($server, $job);

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
        $this->authorize('view', $server);

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
        $this->authorize('view', $server);

        $job->update([
            'status' => STATUS_DELETING
        ]);

        DeleteCronJob::dispatch($server, $job);

        return new ServerResource($server->fresh());
    }
}
