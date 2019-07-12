<?php

namespace App\Http\Requests\Servers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Database;

class CreateDatabaseRequest extends FormRequest
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
        $databases = [MYSQL_DB, MYSQL8_DB, MARIA_DB, POSTGRES_DB, MONGO_DB];

        return [
            'name' => ['required', Rule::unique('databases')->where(function ($query) {
                return $query->where('server_id', $this->route('server')->id);
            })],
            'user' => ['required_with:password', Rule::unique('database_users', 'name')->where(function ($query) {
                return $query->where('server_id', $this->route('server')->id);
            })],
            'password' => ['required_with:user'],
            'type' => 'required|in:' . implode(',', $databases)
        ];
    }
}
