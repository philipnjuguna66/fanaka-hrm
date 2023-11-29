<?php

namespace App\Enums;


enum Taxables: string
{
    use toKeyValueOptions;

    case TAXABLE = 'taxable';
    case NON_TAXABLE = 'non_taxable';

}
