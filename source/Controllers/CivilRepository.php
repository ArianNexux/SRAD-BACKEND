<?php

namespace Source\Controllers;

use Source\Models\Civil as Model;

class CivilRepository extends Repository
{    
    public function __construct()
    {
        parent::__construct(new Model());
    }
}