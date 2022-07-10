<?php

namespace App\Helpers;

use Ramsey\Uuid\Uuid;

final class Utils
{
    public static function generateUniqueId()
    {
        return str_replace('-', '', Uuid::uuid4()->toString());
    }

    public static function dashesToCamelCase($string, $capitalizeFirstCharacter = false)
    {
        $str = str_replace('', '', ucwords(str_replace('_', ' ', $string)));

        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }

        return $str;
    }

    // Generate Standard Payment reference
    public static function generateTransactionRef()
    {
        return date("Ymd") . time() . mt_rand(10000, 99999);
    }
}
