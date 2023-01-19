<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CityWeatherRequest extends FormRequest
{
	public $validator = null;
	
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
            'geo_latitude' => 'required|numeric',
            'geo_longitude' => 'required|numeric',
            'geo_city' => 'required|string'
        ];
    }
	
	protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $this->validator = $validator;
    }
}
