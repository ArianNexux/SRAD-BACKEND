<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;


class Cases extends DataLayer{

    public function __construct()
    {
        parent::__construct("casos",[],"id",false);
    }

    public function getByCounties(array $data)
    {
        return $this->find("municipio = {$data["county"]} AND doenca =  {$data["disease"]}",null, "casos.*, municipios.nome as municipio, municipios.id as municipio_id", "INNER JOIN municipios ON municipios.id = casos.municipio")->fetch(true);
    }
    public function getByProvinces(array $data)
    {
        return $this->find("provincia = {$data["province"]} AND doenca =  {$data["disease"]}",null, "casos.*, municipios.nome as municipio, municipios.id as municipio_id,  provincias.nome as provincia, provincias.id as provincias_id", "INNER JOIN municipios ON municipios.id = casos.municipio INNER JOIN provincias ON provincias.id = municipios.provincia")->fetch(true);
    }

    public function getByAllProvinces(array $data)
    {


        $deaths = $this->find(" tipo = 'M' AND provincias.id = 2",null,"SUM(quantidade) as Mortes,provincias.nome ,tipo"," INNER JOIN municipios ON municipios.id = casos.municipio INNER JOIN provincias ON provincias.id = municipios.provincia")->statement;
        $recovers = $this->find(" tipo = 'R' AND provincias.id = 2",null,"SUM(quantidade) as Recuperados,provincias.nome ,tipo"," INNER JOIN municipios ON municipios.id = casos.municipio INNER JOIN provincias ON provincias.id = municipios.provincia")->statement;
        $actives = $this->find(" tipo = 'A' AND provincias.id = 2, tipo",null,"SUM(quantidade) as Activos,provincias.nome ,tipo"," INNER JOIN municipios ON municipios.id = casos.municipio INNER JOIN provincias ON provincias.id = municipios.provincia")->statement;
   
        $this->entity = "provincias,({$deaths}) as Mortes, ({$recovers}) as Recuperados, ({$actives}) as Activos";
      
        return $this->find("provincias.id = 2",null,"Mortes, Recuperados,Activos, provincias.nome");
    }

    public function getCasesByDeseases($data)
    {
       return $this->find("doenca={$data["doenca"]} GROUP BY tipo",null,"SUM(quantidade) as quantidade,tipo")->fetch(true);
    }
    public function getCasesByCounties($data)
    {
       return $this->find("provincia={$data["provincia"]} GROUP BY tipo",null,"SUM(quantidade) as quantidade");
    }
}