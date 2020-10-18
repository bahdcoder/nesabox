<?php

namespace App\Http\Requests\Servers;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AddDatabaseUserRequest extends FormRequest
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
        $databases = [MYSQL_DB, MYSQL8_DB, MARIA_DB];

        return [
            'name' => [
                'required',
                'alpha_dash',
                Rule::unique('database_users')->where(function ($query) {
                    return $query
                        ->where('server_id', $this->route('server')->id)
                        ->where('type', $this->type);
                })
            ],
            'password' => 'required|min:8',
            'type' => 'required|in:' . implode(',', $databases)
        ];
    }
}
