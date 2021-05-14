<?php

namespace Source\Controllers;

use Source\Models\Cases as Model;
use Source\Models\County;
use Source\Models\Disease;


class CasesRepository extends Repository
{    

    public function __construct()
    {
        parent::__construct(new Model());
    }

    public function readAll($data){
        $json = array();
        $cases = (new Model())->find("casos.municipio = {$data["municipio"]}",null,"doencas.id as doenca_id,casos.id, casos.quantidade, casos.usuario, casos.tipo, municipios.nome as municipio, municipios.id as municipio_id, doencas.nome as doenca, casos.date", "INNER JOIN municipios ON municipios.id = casos.municipio INNER JOIN doencas ON doencas.id = casos.doenca")->fetch(true);
        foreach($cases as $case)
        {
            array_push($json, $case->data());
        }
        echo json_encode($json);
    }

    public function readTotal($data){
        $json = array();

        $cases = (new Model())->find("1 = 1 GROUP BY date ORDER BY date DESC LIMIT 10",null,"SUM(quantidade) as quantidade, date")->fetch(true);
        foreach($cases as $case)
        {
            array_push($json, $case->data());
        }
        echo json_encode($json);

    }

    public function store(array $data)
    {
        if (in_array("", $data)) {
            echo showMessage("Preencha todos os campos obrigatórios, Porfavor!", "warning");
            return;
        }

        foreach(array_keys($data) as $field)
            $this->model->$field = filter_var($data[$field], FILTER_SANITIZE_STRING);
        
        if (!$this->model->save()) {
            var_dump($this->model->fail());die();
            echo showMessage("Erro ao salvar os dados, Porfavor tente mais tarde!", "error");
            return;
        }
        $doenca = (new Disease())->findById($this->model->doenca);
        
        $this->model->doenca = $doenca->nome;
        $this->model->doenca_id = $doenca->id;
        echo json_encode(jsonFormat($this->model));
        sentSubscribed($data["quantidade"], $doenca->id, $data["municipio"], $data["tipo"]);

    }
     public function changeStateCase(array $data){
         $case = (new Model())->findById($data["case"]);

         $case->estado = $data["estado"];

         $case->save();
     }    


     

}