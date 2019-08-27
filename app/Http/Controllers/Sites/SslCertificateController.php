<?php

namespace App\Http\Controllers\Sites;

use App\Site;
use App\Server;
use App\Http\Controllers\Controller;
use App\Http\Resources\SiteResource;
use App\Jobs\Sites\SslCertificate;

class SslCertificateController extends Controller
{
    public function letsEncrypt(Server $server, Site $site)
    {
        if ($site->installing_certificate_status === STATUS_INSTALLING) {
            return new SiteResource($site);
        }

        $site->update([
            'installing_certificate_status' => STATUS_INSTALLING
        ]);

        SslCertificate::dispatch($server, $site);

        return new SiteResource($site);
    }
}
