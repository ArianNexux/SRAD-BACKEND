<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class Provinces extends DataLayer{

    public function __construct()
    {
        parent::__construct("provincias",[],"id",false);
    }
}