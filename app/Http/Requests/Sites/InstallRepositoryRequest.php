<?php

namespace App\Http\Requests\Sites;

use App\Rules\GitRepository;
use Illuminate\Foundation\Http\FormRequest;

class InstallRepositoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'branch' => 'required',
            'provider' => 'required|in:github,gitlab,bitbucket',
            'repository' => ['required', new GitRepository($this)]
        ];
    }
}
