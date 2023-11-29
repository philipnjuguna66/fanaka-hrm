<?php

namespace App\Enums;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;

trait toKeyValueOptions
{
    public static function getKeyValueOptions():array
    {
        $flatten_array= array();

        $cases =  array_map(fn($case) => [$case->value => $case->name],self::cases());

        $iter_object = new RecursiveIteratorIterator(new RecursiveArrayIterator($cases));

        foreach($iter_object as $key => $value) {

            $flatten_array[$key] = $value;

        }

        return  $flatten_array;
    }
}
