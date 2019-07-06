<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Http\ServerProviders\InteractsWithAws;
use App\Http\ServerProviders\InteractsWithVultr;
use Illuminate\Routing\Controller as BaseController;
use App\Http\ServerProviders\InteractsWithDigitalOcean;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, InteractsWithDigitalOcean, InteractsWithVultr, InteractsWithAws;
}
