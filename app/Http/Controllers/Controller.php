<?php

namespace App\Http\Controllers;

use App\Http\Traits\HandlesSshKeys;
use App\Http\Traits\HandlesProcesses;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Http\ServerProviders\InteractsWithAws;
use App\Exceptions\InvalidProviderCredentials;
use App\Http\ServerProviders\InteractsWithVultr;
use App\Http\ServerProviders\InteractWithLinode;
use App\Http\ServerProviders\HasServerProviders;
use Illuminate\Routing\Controller as BaseController;
use App\Http\ServerProviders\InteractsWithDigitalOcean;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\SourceControlProviders\InteractsWithGithub;

class Controller extends BaseController
{
    use AuthorizesRequests,
        DispatchesJobs,
        HandlesSshKeys,
        HandlesProcesses,
        ValidatesRequests,
        InteractsWithDigitalOcean,
        InteractsWithVultr,
        InteractsWithAws,
        InteractWithLinode,
        HasServerProviders,
        InteractsWithGithub;
}
