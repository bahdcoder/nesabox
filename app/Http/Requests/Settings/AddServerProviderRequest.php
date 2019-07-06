<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class AddServerProviderRequest extends FormRequest
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
            'profileName' => 'required',
            'apiKey' => 'required_if:provider,vultr|required_if:provider,aws',
            'provider' => 'required|in:digital-ocean,vultr,aws',
            'apiToken' => 'required_if:provider,digital-ocean',
            'apiSecret' => 'required_if:provider,aws',
        ];
    }
}
