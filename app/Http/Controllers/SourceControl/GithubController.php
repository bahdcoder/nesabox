<?php

namespace App\Http\Controllers\SourceControl;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GithubController extends Controller
{
    /**
     * Fetch all organisations for a user's connected github account
     *
     * @return \Illuminate\Http\Response
     */
    public function organisations()
    {
        $githubToken = auth()->user()->source_control['github'];

        if (!$githubToken) {
            abort(400, __('Github is not connected for this account.'));
        }
    }
}
