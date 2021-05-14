<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class Disease extends DataLayer{

    public function __construct()
    {
        parent::__construct("doencas",[],"id",false);
    }
}