<?php
if(!function_exists('helperJson')){

    function helperJson($data=null,$message,$code,$status = 200){

        return response()->json([

            'data' => $data,
            "message" => $message,
            "code" => $code

        ],$status);
    }
}
