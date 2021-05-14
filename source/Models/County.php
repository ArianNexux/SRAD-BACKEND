<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class County extends DataLayer{

    public function __construct()
    {
        parent::__construct("municipios",[],"id",false);
    }

    
}