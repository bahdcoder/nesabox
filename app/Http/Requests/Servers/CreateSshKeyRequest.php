<?php

namespace App\Http\Requests\Servers;

use App\Rules\PublicKey;
use Illuminate\Foundation\Http\FormRequest;

class CreateSshKeyRequest extends FormRequest
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
            'name' => 'required',
            'key' => ['required', new PublicKey()]
        ];
    }
}
