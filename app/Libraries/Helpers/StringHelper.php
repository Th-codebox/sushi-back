<?php


namespace App\Libraries\Helpers;

use Illuminate\Support\Str;

class StringHelper
{
    public static function arrayKeysToSnakeCase($array): array
    {
        return collect($array)->mapWithKeys(function ($item, $key) {
            return [Str::snake($key) => $item];
        })->toArray();

    }
}
