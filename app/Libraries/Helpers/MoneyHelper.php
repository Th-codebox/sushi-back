<?php


namespace App\Libraries\Helpers;




class MoneyHelper
{

    public static function format($valueBase) : int
    {
        return round(abs($valueBase)/ 100);
    }
}
