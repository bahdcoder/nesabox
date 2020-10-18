<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Http\Requests\Sites\InstallRepositoryRequest;
use App\Http\SourceControlProviders\InteractsWithGithub;

class GitRepository implements Rule
{
    use InteractsWithGithub;

    /**
     * The request object
     *
     * @var \App\Http\Requests\Sites\InstallRepositoryRequest
     */
    private $request;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(InstallRepositoryRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        switch ($this->request->provider) {
            case 'github':
                return (bool) $this->fetchGithubRepositoryBranch(
                    $this->request->repository,
                    $this->request->branch
                );
            default:
                return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The repository or branch name provided is invalid. Please try disconnecting and reconnecting your source control provider if the repository looks valid.';
    }
}
