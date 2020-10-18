<?php

namespace App\Http\Controllers\Sites;

use App\Site;
use App\Server;
use App\Http\Controllers\Controller;
use App\Http\Resources\SiteResource;
use App\Jobs\Sites\SslCertificate;
use Illuminate\Support\Facades\Storage;

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

    public function custom(Server $server, Site $site)
    {
        $request = request();

        $this->validate($request, [
            'certificate' => 'required',
            'privateKey' => 'required'
        ]);

        if ($site->installing_certificate_status === STATUS_INSTALLING) {
            return new SiteResource($site);
        }

        $site->update([
            'installing_certificate_status' => STATUS_INSTALLING
        ]);

        $certificate = str_random(60);

        Storage::disk('local')->put(
            "custom_ssl/{$certificate}.cer",
            $request->certificate
        );

        $privateKey = str_random(60);

        Storage::disk('local')->put(
            "custom_ssl/{$privateKey}.key",
            $request->privateKey
        );

        SslCertificate::dispatch($server, $site, [
            'certificate' => $certificate,
            'privateKey' => $privateKey
        ]);

        return new SiteResource($site);
    }

    public function getSslCert(string $hash)
    {
        $file = Storage::disk('local')->get("custom_ssl/{$hash}.cer");

        Storage::delete("custom_ssl/{$hash}.cer");

        return $file;
    }

    public function getSslKey(string $hash)
    {
        $file = Storage::disk('local')->get("custom_ssl/{$hash}.key");

        Storage::delete("custom_ssl/{$hash}.key");

        return $file;
    }

    public function uninstall(Server $server, Site $site)
    {
        $process = $this->uninstallSsl($server, $site);

        if ($process->isSuccessful()) {
            $site->update([
                'installing_certificate_status' => null
            ]);
        } else {
            $server->alert(
                'Failed to uninstall SSL certificate. View log output for more details.',
                $process->getErrorOutput()
            );

            return response()->json(
                [
                    'message' => 'Failed to uninstall ssl certificate.'
                ],
                400
            );
        }

        return new SiteResource($site->fresh());
    }
}
