<?php

namespace Kejubayer\PathaoIntegration\Helpers;

use Illuminate\Support\Facades\Http;

class HttpClient
{
    public static function post($url, $data = [], $headers = [])
    {
        return Http::withHeaders($headers)
            ->post($url, $data)
            ->json();
    }

    public static function get($url, $headers = [])
    {
        return Http::withHeaders($headers)
            ->get($url)
            ->json();
    }
}