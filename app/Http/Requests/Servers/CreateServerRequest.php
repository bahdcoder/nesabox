<?php

namespace App\Http\Requests\Servers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateServerRequest extends FormRequest
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
        $providers = [DIGITAL_OCEAN, LINODE, AWS, VULTR];

        return [
            'size' => 'required',
            'name' => [
                'required',
                Rule::unique('servers')->where(function ($query) {
                    return $query->where('user_id', auth()->user()->id);
                })
            ],
            'region' => 'required',
            'provider' => 'required|in:' . implode(',', $providers),
            'databases.*' => 'required|in:mysql,mysql8,mariadb,postgres,mongodb'
        ];
    }
}
