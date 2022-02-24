<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class FirstStepRequest extends FormRequest{

     /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            "mobile_no" => "required|numeric",
            "name" => "required"
        ];
    }

    
    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages() {
        return [
            
        ];
    }


    public function authorize()
    {
        return true;
    }

}
