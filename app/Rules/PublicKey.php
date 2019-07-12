<?php

namespace App\Rules;

use App\Http\Traits\HandlesProcesses;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Validation\Rule;
use Symfony\Component\Process\Exception\ProcessFailedException;

class PublicKey implements Rule
{
    use HandlesProcesses;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $path = $this->createPublicKeyFile($value);

        try {
            $this->execProcess("ssh-keygen -l -f {$path}");

            return true;
        } catch (ProcessFailedException $e) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The public key is invalid.');
    }

    /**
     * Create a public key file and return path to file
     *
     * @return string
     */
    public function createPublicKeyFile($key)
    {
        $file = str_random(60);

        Storage::disk('local')->put("keys/{$file}.pub", $key);

        return storage_path("app/keys/{$file}.pub");
    }
}
