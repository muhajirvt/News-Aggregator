<?php

namespace App\Helpers;

use Exception;

class CommonHelper
{
    public static function customCeilDivision($numerator, $denominator)
    {
        if ($denominator == 0) {
            return 0;
        }

        $result = $numerator / $denominator;

        return ($result == (int)$result) ? (int)$result : ((int)$result + 1);
    }
}
