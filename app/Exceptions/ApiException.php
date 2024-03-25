<?php

namespace App\Exceptions;

class ApiException
{
    public const NOT_FOUND = [
        "status" => 404,
        "message" => "resource not found"
    ];

    public const SERVER_ERROR = [
        "status" => 500,
        "message" => "failed to fetch the data",
    ];
}
