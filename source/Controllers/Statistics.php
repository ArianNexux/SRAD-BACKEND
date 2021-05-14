<?php

namespace Source\Controllers;
header("Access-Control-Allow-Origin:  *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

use Source\Models\Cases;
use Source\Models\Provinces;
use Source\Models\County;
use Source\Models\Disease;

class Statistics{
    
    public function casesByCounties(array $data) 
    {
        
        $cases = (new Cases())->getByCounties($data);
 
        if(!$cases)
        {
            echo json_encode([
                "message"=>"Nenhum caso encontrado para o respectivo municipio",
                "type"=>"warning"
            ]);
            http_response_code(204);
            return;
        }
        
        $cases = getTotalCasesWithCounties($cases);
        
        echo json_encode($cases);

    }

    public function casesByProvinces(array $data) 
    {
        $cases = (new Cases())->getByProvinces($data);
        if(!$cases)
        {
            echo json_encode([
                "message"=>"Nenhum caso encontrado para o respectivo municipio",
                "type"=>"warning"
            ]);
            http_response_code(204);
            return;
        }

        $cases = getTotalCasesWithProvinces($cases);
        
        echo json_encode($cases);
    }

    public function casesByProvincesBack($data){
        $provinces = (new Provinces())->find()->fetch(true);
        $cases_county_death = 0;
        $json = [];
        $cases_county_recovered = 0;
        $cases_county_actives = 0;
        foreach($provinces as $province)
        {
            $counties = (new County())->find("provincia = {$province->id}")->fetch(true);
            
            foreach($counties as $county)
            {
                $cases_county_death += (new Cases())->find("municipio = {$county->id} AND tipo = 'M' AND doenca = {$data["decease"]}",null,"SUM(quantidade) as quantidade")->fetch()->data()->quantidade;
                $cases_county_recovered += (new Cases())->find("municipio = {$county->id} AND tipo = 'R' AND doenca = {$data["decease"]}",null,"SUM(quantidade) as quantidade")->fetch()->data()->quantidade;
                $cases_county_actives += (new Cases())->find("municipio = {$county->id} AND tipo = 'A' AND doenca = {$data["decease"]}",null,"SUM(quantidade) as quantidade")->fetch()->data()->quantidade;
            }

            $province->mortos = $cases_county_death;
            $province->recuperados = $cases_county_recovered;
            $province->activos = $cases_county_actives;
            
            array_push($json, $province->data());

            $cases_county_death = 0;
            $cases_county_recovered = 0;
            $cases_county_actives = 0;
        }

        echo json_encode($json);
    }

    public function casesByDiseaseDate($data){
        $dates = (new Cases())->find("1=1 ORDER BY date DESC",null, "DISTINCT(date)")->fetch(true);
        $cases_county_death = 0;
        $json = [];
        $cases_county_recovered = 0;
        $cases_county_actives = 0;
        foreach($dates as $date)
        {
                $cases_county_death += (new Cases())->find("tipo = 'M' AND doenca = {$data["disease"]}",null,"SUM(quantidade) as quantidade")->fetch()->data()->quantidade;
                $cases_county_recovered += (new Cases())->find("tipo = 'R' AND doenca = {$data["disease"]}",null,"SUM(quantidade) as quantidade")->fetch()->data()->quantidade;
                $cases_county_actives += (new Cases())->find("tipo = 'A' AND doenca = {$data["disease"]}",null,"SUM(quantidade) as quantidade")->fetch()->data()->quantidade;
            

            $date->mortos = $cases_county_death;
            $date->recuperados = $cases_county_recovered;
            $date->activos = $cases_county_actives;
            
            array_push($json, $date->data());

            $cases_county_death = 0;
            $cases_county_recovered = 0;
            $cases_county_actives = 0;
        }

        echo json_encode($json);
    }

    public function getCasesByDeseaseBack($data){

        $json = [];
        
        $deceases = (new Disease())->find()->fetch(true);
        if(!$deceases){
            http_response_code(204);
            return;
        }
        foreach($deceases as $decease)
        {
            $decease->confirmados = (new Cases())->find("doenca = {$decease->id} AND municipio = {$data["county"]}",null,"SUM(quantidade) as quantidade")->fetch()->data()->quantidade;
            array_push($json, $decease->data());
        }
        echo json_encode($json);
    }

    public function casesAllProvinces(array $data) 
    {
        /*
        $json = array();
        $provinces = (new Provinces())->find()->fetch(true);
        foreach ($provinces as $province)
        {
            array_push($json, getCasesByProvince($province->id));
        }
        if(!$provinces)
        {
            echo json_encode([
                "message"=>"Nenhum caso encontrado.",
                "type"=>"warning"
            ]);
            http_response_code(204);
            return;
        }
        */
        
        echo json_encode(returnJson($data["disease"]));
    }

    public function casesByDesease($data)
    {
        $json = array();
        $cases = (new Cases())->getCasesByDeseases($data);
        if(!$cases){
            http_response_code(204);
        }
        foreach($cases as $case)
        {
            array_push($json, $case->data);
        }

        echo json_encode($json);
    }

    public function countiesCasesByProvinces($data)
    {
        $json = array();
        $counties = (new County())->find("provincia = {$data["provincia"]}")->fetch(true);
        
        if(!$counties){
            http_response_code(204);
        }

        foreach($counties as $county){
            $cases = getCasesByCounties($county->id, $data["doenca"]);
            array_push($json, $cases);
        }
        echo json_encode($json);
    }

    public function showMonth(){
        echo json_encode(queryCasesByDateProvince(11));
    }

    public function casesPerDateByProvinceAndDecease($data){
        $json = queryCasesByDate($data["decease"], $data["province"]);

        if(!$json){
            http_response_code(204);
        }

        echo json_encode($json);
    }

    public function lastUpdate(){
        $cases = (new Cases())->find("1 = 1 ORDER BY id DESC")->fetch(false)->data();   
        echo json_encode([
            "data"=>transformToAOFormat($cases->date)
        ]);

    }

    public function totalDashboard(){
        
    }
}