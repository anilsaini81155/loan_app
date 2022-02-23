<?php

namespace App\Http\Requests;


class SecondStepRequest
{

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            "loan_amount" => "required|numeric",
            "loan_tenure" => "required|numeric",
        ];
    }


    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }
}
