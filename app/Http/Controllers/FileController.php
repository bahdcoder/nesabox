<?php

namespace App\Http\Controllers;

class FileController extends Controller
{
    public function nesaMetricsPackageJson()
    {
        return view('files.nesa-metrics.package-json')->render();
    }

    public function nesaMetricsIndexJs()
    {
        return view('files.nesa-metrics.index-js')->render();
    }
}
