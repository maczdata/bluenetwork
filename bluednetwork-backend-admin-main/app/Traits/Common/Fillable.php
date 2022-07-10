<?php

namespace App\Traits\Common;

trait Fillable
{
    public function filled(array $arr): array
    {
        filled(true);
        return array_filter($arr, 'filled');
    }
}
