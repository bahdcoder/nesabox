<?php

namespace App\Http\Requests\Users;

use App\Rules\PublicKey;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AddSshkeyRequest extends FormRequest
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

    public function messages()
    {
        return [
            'key.unique' => __('This key is already in use on an account.')
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|alpha_dash',
            'key' => ['required', new PublicKey(), Rule::unique('sshkeys')->where(function ($query) {
                return $query->where(
                    'is_profile_key', true
                );
            })]
        ];
    }
}
