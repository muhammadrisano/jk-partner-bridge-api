<?php

namespace App\Helpers;

class ApiFormatter{
    // protected static $response =[
    //     'code' => null,
    //     'data'=> null
    // ];
    public static function createApi($code = null, $data = null){
        // self::$response['code'] = $code;
        // self::$response['message'] = $message;
        // self::$response['data'] = $data['data'];
        
        return response()->json($data, $code);
    }
}