<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class Users extends DataLayer{

    public function __construct()
    {
        parent::__construct("usuarios",[],"id",false);
    }
}