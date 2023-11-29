<?php

namespace App\Enums;

enum TaxFormulas:string
{
    use toKeyValueOptions;

  case EMPLOYERS_OWNED_HOUSE = 'employers_owned_house';
  case EMPLOYERS_RENTED_HOUSE = 'employers_rented_house';
  case AGRICULTURE_FARM = 'agriculture_farm';
  case TELEPHONE_BENEFIT = 'telephone_benefit';
  case FOOD_BENEFIT = 'food_benefit';

  case NONE = 'none';


}
