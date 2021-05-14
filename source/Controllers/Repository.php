<?php
namespace Source\Controllers;

use CoffeeCode\DataLayer\DataLayer;
use Source\Interfaces\ActiveRecord;


abstract class Repository implements ActiveRecord{

    protected $model;

    public function __construct(DataLayer $model)
    {
        $this->model = $model;
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
            var_dump($this->model);
            //var_dump($this->model->fail());die();
            echo showMessage("Erro ao salvar os dados, Porfavor tente mais tarde!", "error");
            return;
        }

        echo json_encode(jsonFormat($this->model));
    }

    public function update(array $data)
    {
        if (in_array("", $data)) {
            echo showMessage("Preencha todos os campos obrigatórios, Porfavor!", "warning");
            return; 
        }

        $this->model = $this->model->findById((int)$data["id"]);
        
        if ($this->model) {
            foreach(array_keys($data) as $field)
                if(isset($data[$field])) $this->model->$field =  $data[$field];
        } 
            
        if(!$this->model->save()){
            echo json_encode([
                "message"=>"Erro ao salvar os dados.",
                "type"=>"warning"
            ]);
            http_response_code(204);
            return;
        }
                
        echo json_encode(jsonFormat($this->model));           
    }

    public function delete(array $data)
    {
        $this->model = $this->model->findById((int)$data["id"]);

        $rowExists = !isset($this->model);

        if ($rowExists) {
            echo  showMessage("O Identificador informado não existe...", "error");
            http_response_code(204);
            return;
        }

        if (!$this->model->destroy()) {
            echo  showMessage("Erro ao eliminar os dados", "error");
            http_response_code(204);
            return;
        }
        echo showMessage("Eliminada com sucesso", "success");
    }

    public function read()
    {
        $user = $this->model->find("1=1 ORDER BY id DESC")->fetch(true);
        if (!$user) {
           echo json_encode([
               "message"=>"Nenhum resultado encontrado"
           ]);
           http_response_code(204);
           return;
        }
        echo json_encode(jsonFormat($user));
    }


    public function show(array $data)
    {
        $this->model = $this->model->findById($data["id"]);

        if(!$this->model)
        {
            echo json_encode([
                "message"=>"Dados não encontrado",
                "type"=>"warning"
            ]);
            http_response_code(204);
            return;
        }

        echo json_encode($this->model->data());
    }


}