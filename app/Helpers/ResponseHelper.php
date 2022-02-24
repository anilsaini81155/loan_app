<?php

namespace App\Helpers\GlobalsHelper;

use Illuminate\Support\Facades\Response;

if (!function_exists('putsResponse')) {

    function putsResponse($status, $message, $data, $code)
    {

        $content = [
            "status" => $status,
            "message" => $message,
            "data" => $data,
            "code" => $code
        ];

        return Response::json($content, $code);
    }
}



if (!function_exists('funcReturnsData')) {

    function funcReturnsData($status, $message, $data, $code)
    {

        $content = collect([
            "status" => $status,
            "message" => $message,
            "data" => collect($data),
            "code" => $code
        ]);

        return $content;
    }
}
