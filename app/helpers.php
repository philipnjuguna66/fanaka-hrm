<?php

function extractIdFromExactPrefixValue($value, $prefix): ?int
{
    $pattern = "/^$prefix(\d+)/";
    preg_match($pattern, $value, $matches);

    if (isset($matches[1])) {
        return (int)$matches[1]; // Convert the matched ID to an integer and return
    }

    return null; // Return null if no ID is found
}

function generateNumberPrefix(int $number, string $prefix, int $length = 4): string
{
    return $prefix . str_pad($number, $length, '0', STR_PAD_LEFT);
}