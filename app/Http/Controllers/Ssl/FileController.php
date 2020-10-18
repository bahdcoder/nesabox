<?php

namespace App\Http\Controllers\Ssl;

use App\Http\Controllers\Controller;

class FileController extends Controller
{
    public function baseConf()
    {
        return view('', [
            'site' => ''
        ])->render();
    }
}
