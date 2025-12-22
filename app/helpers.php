<?php

function generalJsonResponse(bool $status,string|null $message,mixed $data,int $code)
{
    return response()->json([
        "status" => $status,
        "message" => $message,
        "data" => $data,
    ],$code);  
}

function successJsonResponse(string|null $message = null,mixed $data = null)
{
    return generalJsonResponse(true,$message,$data,200);
}

function errorJsonResponse(string|null $message = null,mixed $data = null,int $code = 400)
{
    return generalJsonResponse(false,$message,$data,$code);
}

function forbiddenJsonResponse(string|null $message = null,mixed $data = null)
{
    return generalJsonResponse(false,$message,$data,403);
}

function failValidationJsonResponse(array $errors)
{
    return generalJsonResponse(false,null,$errors,422);
}