<?php


namespace App\Libraries\System;


use http\Exception\InvalidArgumentException;
use Illuminate\Support\Str;

class FilialSettings
{
    public function get(string $key, ?int $filialId)
    {
        if ($filialId < 1) {
            throw new \InvalidArgumentException('$filialId указан некоректно');
        }

        if (empty($key)) {
            throw new \InvalidArgumentException('Некоректно указан ключ настройки $key');
        }

        return config(
            "filials.custom.{$filialId}.{$key}",
            $this->getDefault($key)
        );
    }

    public function getDefault($key)
    {
        return config("filials.default.{$key}");
    }

    public function getTimeZone(int $filialId)
    {
        return $this->get('timezone', $filialId);
    }
}
