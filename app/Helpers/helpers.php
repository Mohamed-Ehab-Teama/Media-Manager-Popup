<?php

namespace App\Helpers;


if (! function_exists('custom_json_response')) {
    function custom_json_response($data = [], $status = true, $message = '', $code = 200)
    {
        return response()->json([
            'status'    => true,
            'message'   => $message,
            'code'      => $code,
            'data'      => $data,
        ], $code);
    }
}
