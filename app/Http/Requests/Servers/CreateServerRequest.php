<?php

namespace App\Http\Requests\Servers;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

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
        $providers = [DIGITAL_OCEAN, LINODE, AWS, VULTR, CUSTOM_PROVIDER];

        $databases = [MYSQL_DB, MYSQL8_DB, MARIA_DB, POSTGRES_DB, MONGO_DB];

        return [
            'size' => 'required',
            'private_ip_address' => 'ipv4|nullable',
            'ip_address' => [
                'required_if:provider,' . CUSTOM_PROVIDER,
                'ipv4',
                'nullable'
            ],
            'name' => [
                'required',
                'alpha_dash',
                Rule::unique('servers')->where(function ($query) {
                    return $query->where('user_id', auth()->user()->id);
                })
            ],
            'region' => [
                Rule::requiredIf(function () {
                    return $this->provider !== CUSTOM_PROVIDER;
                }),
                'string'
            ],
            'provider' => 'required|in:' . implode(',', $providers),
            'databases.*' => 'in:' . implode(',', $databases),
            'type' => 'in:load_balancer,default,database'
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            switch ($this->provider):
                case DIGITAL_OCEAN:
                    $digitalOceanData = cached_provider_data(DIGITAL_OCEAN);

                    if (
                        !collect($digitalOceanData->regions)->first(function (
                            $region
                        ) {
                            return $region->slug === $this->region;
                        })
                    ) {
                        $validator
                            ->errors()
                            ->add(
                                'region',
                                __(
                                    "The region is invalid for {$this->provider}."
                                )
                            );
                    }

                    if (
                        !collect($digitalOceanData->sizes)->first(function (
                            $size
                        ) {
                            return $size->slug === $this->size;
                        })
                    ) {
                        $validator
                            ->errors()
                            ->add(
                                'size',
                                __("The size is invalid for {$this->provider}.")
                            );
                    }

                    break;
                case VULTR:
                    $vultrData = cached_provider_data(VULTR);

                    if (
                        !collect($vultrData->regions)->first(function (
                            $region
                        ) {
                            return $region->DCID === $this->region;
                        })
                    ) {
                        $validator
                            ->errors()
                            ->add(
                                'region',
                                __(
                                    "The region is invalid for {$this->provider}."
                                )
                            );
                    }

                    if (
                        !collect($vultrData->plans)->first(function ($size) {
                            return $size->VPSPLANID === $this->size;
                        })
                    ) {
                        $validator
                            ->errors()
                            ->add(
                                'size',
                                __("The size is invalid for {$this->provider}.")
                            );
                    }

                    break;
                case LINODE:
                    $linodeData = cached_provider_data(LINODE);

                    if (
                        !collect($linodeData->regions)->first(function (
                            $region
                        ) {
                            return $region->id === $this->region;
                        })
                    ) {
                        $validator
                            ->errors()
                            ->add(
                                'region',
                                __(
                                    "The region is invalid for {$this->provider}."
                                )
                            );
                    }

                    if (
                        !collect($linodeData->sizes)->first(function ($size) {
                            return $size->id === $this->size;
                        })
                    ) {
                        $validator
                            ->errors()
                            ->add(
                                'size',
                                __("The size is invalid for {$this->provider}.")
                            );
                    }

                    break;
            endswitch;
        });
    }
}
