<?php

namespace App\Http\Controllers\Sites;

use App\Site;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Scripts\Sites\UpdateFileContent;

class FileContentController extends Controller
{
    public function index(Request $request, Site $site)
    {
        $this->validate($request, [
            'path' => 'required|string'
        ]);

        $process = $this->getFileContent($site->server, $request->path, false);

        return $process->isSuccessful() ? $process->getOutput() : response()->json($process->getErrorOutput(), 400);
    }

    public function update(Request $request, Site $site) {
        $this->validate($request, [
            'path' => 'required|string',
            'fileContent' => 'required'
        ]);

        $process = (new UpdateFileContent($site, $request->path, $request->fileContent))->run();

        return $process->isSuccessful() ? $process->getOutput() : response()->json($process->getErrorOutput(), 400);
    }
}
