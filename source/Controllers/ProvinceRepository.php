<?php

namespace Source\Controllers;

use Source\Models\Provinces as Model;


class ProvinceRepository extends Repository
{    
    public function __construct()
    {
        parent::__construct(new Model());
    }
}