<?php

define("DATA_LAYER_CONFIG", [
  "driver" => "mysql",
  "host" => "localhost",
  "port" => "3306",
  "dbname" => "srad",
  "username" => "root",
  "passwd" => "",
  "options" => [

    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    PDO::ATTR_CASE => PDO::CASE_NATURAL

  ]
]);

define("URL_BASE", "http://localhost/srad");

define("MAIL", [
  "debug" => false,
  "host" => "smtp.sendgrid.net",
  "port" => "465",
  "secure" => "ssl",
  "user" => "apikey",
  "passwd"=>"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",
  //"passwd" => "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",
  "from_name" => "Equipe de Suporte | SRAD",
  "from_email" => "ariannexux0101@gmail.com",
]);

define("CONTROLLERS", [
  "usuarios" => "UserRepository",
  "casos" => "CasesRepository",
  "doencas" => "DiseaseRepository",
  "provincias" => "ProvinceRepository",
  "municipios" => "CountyRepository",
  "informacoes" => "InfoRepository",
  "cidadaos" => "CivilRepository",
]);
