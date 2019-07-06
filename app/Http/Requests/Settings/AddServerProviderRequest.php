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
        $providers = [DIGITAL_OCEAN, LINODE, AWS, VULTR];

        return [
            'profileName' => 'required',
            'apiKey' => 'required_if:provider,'. VULTR. '|required_if:provider,' . AWS,
            'provider' => 'required|in:' . implode(',', $providers),
            'apiToken' => 'required_if:provider,' . DIGITAL_OCEAN,
            'apiSecret' => 'required_if:provider,' . AWS,
            'accessToken' => 'required_if:provider,' . LINODE
        ];
    }
}
