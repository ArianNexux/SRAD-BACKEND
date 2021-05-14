<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class Info extends DataLayer{

    public function __construct()
    {
        parent::__construct("informacoes",[],"id",false);
    }
}