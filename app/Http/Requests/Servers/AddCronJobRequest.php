<?php

namespace App\Http\Requests\Servers;

use App\Rules\Cron;
use Illuminate\Foundation\Http\FormRequest;

class AddCronJobRequest extends FormRequest
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
            'user' => 'required',
            'command' => 'required',
            'cron' => ['required_if:frequency,custom', new Cron()],
            'frequency' =>
                'required|in:everyMinute,everyFiveMinutes,everyTenMinutes,hourly,daily,weekly,monthly,custom'
        ];
    }
}
