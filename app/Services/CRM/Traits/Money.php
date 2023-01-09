<?php

namespace App\Services\CRM\Traits;


trait Money
{
    /**
     * @param $money
     * @return int
     */
    protected static function toSave($money): int
    {
        return (int)$money * 100;
    }
}
