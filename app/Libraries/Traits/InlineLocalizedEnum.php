<?php


namespace App\Libraries\Traits;


trait InlineLocalizedEnum
{
    public static function getDescription($value): string
    {
        return static::$lang[(string)$value] ?? parent::getDescription($value);
    }
}
