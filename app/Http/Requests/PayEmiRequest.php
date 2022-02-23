<?php

namespace App\Http\Requests;


class PayEmiRequest
{

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            "emi_amount" => "required|numeric",
            "emi_id" => "required|numeric"
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
