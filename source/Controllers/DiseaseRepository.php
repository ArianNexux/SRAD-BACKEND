<?php

namespace Source\Controllers;

use Source\Models\Disease as Model;


class DiseaseRepository extends Repository
{    

    public function __construct()
    {
        parent::__construct(new Model());
    }
}