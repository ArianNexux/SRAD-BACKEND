<?php

namespace Source\Controllers;

use Source\Models\Users as Model;
use Source\Models\County;


class UserRepository extends Repository
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

        foreach(array_keys($data) as $field){
            switch($field){
                case "senha":
                    $this->model->senha = password_hash(filter_var("12345", FILTER_SANITIZE_STRING), PASSWORD_DEFAULT);
                    break;
                default:
                    $this->model->$field = filter_var($data[$field], FILTER_SANITIZE_STRING);

            }
        }        

        
        if (!$this->model->save()) {
            var_dump($this->model->fail());
            echo showMessage("Erro ao salvar os dados, Porfavor tente mais tarde!", "error");
            return;
        }

        $county = (new County())->findById($this->model->municipio);

        $this->model->municipio = $county->nome;
        $this->model->municipio_id = $county->id;

        echo json_encode(jsonFormat($this->model));
    }

    public function read()
    {
        $json = [];
        $infos = $this->model->find("1=1 ORDER BY id DESC")->fetch(true);
        foreach($infos as $case){
            $municipio = (new County())->findById($case->municipio)->data();
            $case->municipio = $municipio->nome;
            $case->municipio_id = $municipio->id;
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

    public function resetPassword($data)
    {
        $user = (new Model())->findById($data["id"]);

        if(!$user){
            http_response_code(204);
        }

        $user->senha = password_hash("12345", PASSWORD_DEFAULT);
        $user->save();
    }
    
}
