<?php

namespace App\Http\Requests\Servers;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;
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

        return [
            'size' => 'required',
            'ip_address' => ['required_if:provider,' . CUSTOM_PROVIDER],
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
                })
            ],
            'provider' => 'required|in:' . implode(',', $providers),
            'databases' => 'required',
            'databases.*' => 'required|in:mysql,mysql8,mariadb,postgres,mongodb'
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
                    if (
                        !collect(DIGITAL_OCEAN_REGIONS)->first(function (
                            $region
                        ) {
                            return $region['id'] === $this->region;
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
                        !collect(DIGITAL_OCEAN_SIZES)->first(function ($size) {
                            return $size['id'] === $this->size;
                        })
                    ) {
                        $validator
                            ->errors()
                            ->add(
                                'size',
                                __("The size is invalid for {$this->provider}.")
                            );
                    }
                case VULTR:
                    $vultrRegions = Cache::rememberForever(
                        'vultr-data',
                        function () {
                            return json_decode(
                                file_get_contents(
                                    base_path('provider-data/vultr.json')
                                )
                            )->regions;
                        }
                    );

                    if (
                        !collect($vultrRegions)->first(function ($region) {
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
                        !collect(VULTR_SIZES)->first(function ($size) {
                            return $size['id'] === $this->size;
                        })
                    ) {
                        $validator
                            ->errors()
                            ->add(
                                'size',
                                __("The size is invalid for {$this->provider}.")
                            );
                    }
                
                case LINODE:
                    $linodeData = Cache::rememberForever(
                        'linode-data',
                        function () {
                            return json_decode(
                                file_get_contents(
                                    base_path('provider-data/linode.json')
                                )
                            );
                        }
                    );

                    if (
                        !collect($linodeData->regions)->first(function ($region) {
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
            endswitch;
        });
    }
}
