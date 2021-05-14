<?php

namespace Source\Controllers;

use Source\Models\County as Model;


class CountyRepository extends Repository
{    
    public function __construct()
    {
        parent::__construct(new Model());
    }
}