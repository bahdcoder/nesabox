<?php

namespace App\Http\Requests\Sites;

use App\Rules\Domain;
use App\Rules\Subdomain;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateSiteRequest extends FormRequest
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
            'name' => [
                'required',
                Rule::unique('sites')->where(function ($q) {
                    return $q->where('server_id', $this->route('server')->id);
                }),
                new Domain()
            ],
            'type' => [
                Rule::requiredIf(function () {
                    return $this->route('server')->type !== 'load_balancer';
                }),
                'in:nodejs,html'
            ],
            'directory' => 'required'
        ];
    }
}
