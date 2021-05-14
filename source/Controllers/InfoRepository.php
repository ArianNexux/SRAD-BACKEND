<?php

namespace Source\Controllers;

use Source\Models\Info as Model;
use Source\Models\Disease;


class InfoRepository extends Repository
{    
    public function __construct()
    {
        parent::__construct(new Model());
    }

    public function store(array $data)
    {
        if (in_array("", $data)) {
            echo showMessage("Preencha todos os campos obrigatórios, Porfavor!", "warning");
            return;
        }
        
        foreach(array_keys($data) as $key => $field){
                $this->model->$field = filter_var($data[$field], FILTER_SANITIZE_STRING);
        }
        $this->model->img = uploadFile($_FILES["img"], 0)["result"];
        
        if (!$this->model->save()) {
            var_dump($this->model->fail());die();
            echo showMessage("Erro ao salvar os dados, Porfavor tente mais tarde!", "error");
            return;
        }
        $doenca = (new Disease())->findById($this->model->doenca);

        $this->model->doenca = $doenca->nome;
        $this->model->doenca_id = $doenca->id;
        echo json_encode(jsonFormat($this->model));
    }

    public function read()
    {
        $json = [];
        $infos = $this->model->find("1=1 ORDER BY id DESC")->fetch(true);
        foreach($infos as $case){
            $doenca = (new Disease())->findById($case->doenca)->data();
            $case->doenca = $doenca->nome;
            $case->doenca_id = $doenca->id;
            array_push($json, $case->data());
        }
        if (!$infos) {
           echo json_encode([
               "message"=>"Nenhum resultado encontrado"
           ]);
           http_response_code(204);
           return;
        }
        echo json_encode(($json));
    }

    public function readFront()
    {
        $json = ["docs"=>[]];
        $off = $_GET["page"] != 1 ? $_GET["page"] * 3 - 3 : 0;
        $infos = $this->model->find("1=1 ORDER BY id DESC LIMIT 3 OFFSET {$off}",null,"titulo as title, descricao as description, doenca,img")->fetch(true);
        var_dump($infos);die(); 
        $counter = count($infos);
        foreach($infos as $case){
            $doenca = (new Disease())->findById($case->doenca)->data();
            $case->doenca = $doenca->nome;
            $case->doenca_id = $doenca->id;
            array_push($json["docs"], $case->data());
        }
        if (!$infos) {
           echo json_encode([
               "message"=>"Nenhum resultado encontrado"
           ]);
           http_response_code(204);
           return;
        }
        $json["total"] = $counter;
        $json["limit"] = 3;
        $json["pages"] = ceil($counter / 3);
        $json["page"] = $_GET["page"];

        echo json_encode(($json));
    }
}