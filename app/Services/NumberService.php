<?php

namespace App\Services;

final class NumberService
{
    public static function formatFloat(float $number, int $nDecimals=2)
    {
        return floatval(number_format($number, $nDecimals, '.', ''));
    }
}
