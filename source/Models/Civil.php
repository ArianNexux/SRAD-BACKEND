<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class Civil extends DataLayer{

    public function __construct()
    {
        parent::__construct("cidadaos",[],"id",false);
    }

    
}