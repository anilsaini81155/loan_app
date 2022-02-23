<?php

namespace App\Http\Requests;


class FirstStepRequest{

     /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            "mobile_no" => "required|numeric",
            "name" => "required|numeric",
            
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

}
