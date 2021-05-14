<?php

use Source\Models\Answers;

use Source\Models\Disease;
use Source\Models\Counties;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Source\Models\County;
use Source\Models\Provinces;
use Source\Models\Civil;

include 'vendor/phpqrcode/qrlib.php'; 

$str_conn = "mysql:hostname=localhost;dbname=srad;charset=utf8";
$conn = new \PDO($str_conn, "root", "");
$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

function resource($router, $controllers)
{
  foreach ($controllers as $key => $controller) {
    $router->group(mb_strtolower($key));
    $router->post("/", "{$controller}:store");
    $router->get("/{id}", "{$controller}:show");
    $router->get("/", "{$controller}:read");
    
    //$router->get("/show/{id}", "{$controller}:show");
    $router->post("/{id}", "{$controller}:update");
    $router->delete("/{id}", "{$controller}:delete");
  }
}

function getCasesByProvince($province_id)
{
  $str_conn = "mysql:hostname=localhost;dbname=srad;charset=utf8";
  $conn = new \PDO($str_conn, "root", "");
  $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  
  $query = "SELECT Mortes, Recuperados,Activos, provincias.id as provincia_id,provincias.nome as provincia_nome FROM provincias, ((SELECT SUM(quantidade) as Mortes,provincias.nome ,tipo FROM casos  INNER JOIN municipios ON municipios.id = casos.municipio INNER JOIN provincias ON provincias.id = municipios.provincia WHERE tipo = 'M' AND provincias.id = {$province_id}) as Mortes, (SELECT SUM(quantidade) as Recuperados,provincias.nome as provincias,tipo FROM casos  INNER JOIN municipios ON municipios.id = casos.municipio INNER JOIN provincias ON provincias.id = municipios.provincia WHERE tipo = 'R' AND provincias.id = {$province_id}) as Recuperados), (SELECT SUM(quantidade) as Activos,provincias.nome as provincias,tipo FROM casos  INNER JOIN municipios ON municipios.id = casos.municipio INNER JOIN provincias ON provincias.id = municipios.provincia WHERE tipo = 'A' AND provincias.id = {$province_id}) as Activos WHERE provincias.id = {$province_id}";

  $exec = $conn->prepare($query);
  $exec->execute();

  return $exec->fetch(PDO::FETCH_ASSOC);


}

function getCasesByCounties($county_id, $doenca_id)
{ 
  $str_conn = "mysql:hostname=localhost;dbname=srad;charset=utf8";
  $conn = new \PDO($str_conn, "root", "");
  $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  
  $query = "SELECT Mortes, Recuperados, Activos, municipios.id as municipio_id,municipios.nome as municipio_nome
  FROM casos,municipios, ((SELECT SUM(quantidade) as Mortes,municipios.nome ,tipo 
  FROM casos  INNER JOIN municipios ON municipios.id = casos.municipio
  WHERE tipo = 'M' AND municipios.id = {$county_id} AND casos.doenca = {$doenca_id} ) as Mortes,
        
(SELECT SUM(quantidade) as Recuperados,municipios.nome as municipios,tipo 
  FROM casos  INNER JOIN municipios ON municipios.id = casos.municipio 
  WHERE tipo = 'R' AND municipios.id = {$county_id}  AND casos.doenca = {$doenca_id}) as Recuperados),
  
  (SELECT SUM(quantidade) as Activos,municipios.nome as municipios,tipo 
  FROM casos  INNER JOIN municipios ON municipios.id = casos.municipio 
   WHERE tipo = 'A' AND municipios.id = {$county_id}  AND casos.doenca = {$doenca_id}) as Activos
  WHERE municipios.id = {$county_id}  AND casos.doenca = {$doenca_id}";

  $exec = $conn->prepare($query);
  $exec->execute();

  return $exec->fetch(PDO::FETCH_ASSOC);


}

function getCasesByDeseases($doenca_id)
{ 
  $str_conn = "mysql:hostname=localhost;dbname=srad;charset=utf8";
  $conn = new \PDO($str_conn, "root", "");
  $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  
  $query = "SELECT
    Mortes,
    Recuperados,
    Activos,
    doencas.id AS doenca_id,
    doencas.nome AS doenca_nome
FROM
    casos,
    doencas,
    (
        (
        SELECT
            SUM(quantidade) AS Mortes,
            doencas.nome AS doenca,
            tipo
        FROM
            casos
        INNER JOIN doencas ON doencas.id = casos.doenca
        WHERE
            tipo = 'M' AND doencas.id = {$doenca_id}
    ) AS Mortes,
    (
    SELECT
        SUM(quantidade) AS Recuperados,
        doencas.nome AS doenca,
        tipo
    FROM
        casos
    INNER JOIN doencas ON doencas.id = casos.doenca
    WHERE
        tipo = 'R' AND doencas.id = {$doenca_id}
) AS Recuperados
    ),
    (
    SELECT
        SUM(quantidade) AS Activos,
        doencas.nome AS doenca,
        tipo
    FROM
        casos
    INNER JOIN doencas ON doencas.id = casos.doenca
    WHERE
        tipo = 'A' AND doencas.id = {$doenca_id}
) AS Activos
WHERE
    doencas.id = {$doenca_id}";

  $exec = $conn->prepare($query);
  $exec->execute();

  return $exec->fetch(PDO::FETCH_ASSOC);


}

function getCasesByProvinceType($doenca)
{
  $str_conn = "mysql:hostname=localhost;dbname=srad;charset=utf8";
  $conn = new \PDO($str_conn, "root", "");
  $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  
  $query = "SELECT SUM(quantidade) as cases, provincias.abreviacao as provincia_nome, tipo,casos.date,provincias.id as provincia_id FROM `casos` INNER JOIN municipios ON municipios.id = casos.municipio INNER JOIN provincias ON provincias.id = municipios.provincia GROUP BY provincias.id,date,tipo WHERE doenca = {$doenca}";
  $exec = $conn->prepare($query);
  $exec->execute();

  return $exec->fetchAll(PDO::FETCH_ASSOC);
}

function queryBuilder(){
  $str_conn = "mysql:hostname=localhost;dbname=srad;charset=utf8";
  $conn = new \PDO($str_conn, "root", "");
  $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  
  $query = "SELECT SUM(quantidade) as quantidade,doencas.nome as doenca, doencas.id as doenca_id FROM `casos` RIGHT JOIN doencas ON doencas.id = casos.doenca GROUP BY doenca ORDER BY quantidade DESC LIMIT 5";
  $exec = $conn->prepare($query);
  $exec->execute();

  return $exec->fetchAll(PDO::FETCH_ASSOC);
}

function queryBuilderByDecease($decease){
  $str_conn = "mysql:hostname=localhost;dbname=srad;charset=utf8";
  $conn = new \PDO($str_conn, "root", "");
  $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  
  $query = "SELECT SUM(quantidade) as quantidade,doencas.nome as doenca, doencas.id as doenca_id FROM `casos` RIGHT JOIN doencas ON doencas.id = casos.doenca WHERE doencas.id = {$decease}";
  $exec = $conn->prepare($query);
  $exec->execute();

  return $exec->fetchAll(PDO::FETCH_ASSOC);
}

function getProvincesAssociatedInDisease()
{
  //var_dump(queryBuilder());
  $json = array();
  $provinces = (new Provinces())->find()->fetch(true);
  

  foreach($provinces as $province)
  {
    $array_data = array();
    $json[$province->nome] = array();

    foreach(queryBuilder() as $disease)
    {
 
      $query = queryProvinceDisease($province->id, $disease["doenca_id"]);
      if(count($query)<=0){
        $array_data[$disease["doenca"]] = array(
          "morte"=>0,
          "recuperado"=>0,
          "activo"=>0,
        );
        continue;
      }
      foreach($query as $value){
        $array_data[$value["doenca"]] = array(
          "morte"=>0,
          "recuperado"=>0,
          "activo"=>0,
        );

        $choose = "";
        switch($value["tipo"]){
          case 'M':
            $choose = "morte";
            break;
          case 'R':
            $choose = "recuperado";
            break;
          case 'A':
            $choose = "activo";
            break;
        }
      }
      $array_data[$value["doenca"]][$choose] = $value["quantidade"];
      
    }  
    array_push($json[$province->nome],$array_data);

  }
  return $json;
}

function queryProvinceDisease($province, $disease) 
{
  $str_conn = "mysql:hostname=localhost;dbname=srad;charset=utf8";
  $conn = new \PDO($str_conn, "root", "");
  $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  
  //$query = "SELECT doencas.nome as doencas, diseased, recovered, confirmed, provincias.id as provincia_id,provincias.nome as provincia_nome, doencas.nome ,casos.date FROM provincias, casos , (SELECT SUM(quantidade) as diseased,tipo FROM casos WHERE tipo = 'M') as diseased, (SELECT SUM(quantidade) as recovered FROM casos WHERE tipo = 'R') as recovered, (SELECT SUM(quantidade) as confirmed,tipo FROM casos WHERE tipo = 'A') as confirmed LEFT JOIN doencas ON doencas.id = casos.doenca WHERE provincias.id = {$province} AND doenca = {$disease} LIMIT 1";
  $query = "SELECT SUM(quantidade) as quantidade,tipo,doencas.nome as doenca,provincias.nome FROM `casos` INNER JOIN municipios ON municipios.id = casos.municipio INNER JOIN provincias ON provincias.id = municipios.provincia INNER JOIN doencas ON doencas.id = casos.doenca WHERE doencas.id = {$disease} AND municipios.provincia = {$province} GROUP BY tipo,provincia,doenca";
  $exec = $conn->prepare($query);
  $exec->execute();

  return $exec->fetchAll(PDO::FETCH_ASSOC);
}
function queryCasesByDate($decease, $province){

  $json = [];
  $str_conn = "mysql:hostname=localhost;dbname=srad;charset=utf8";
  $conn = new \PDO($str_conn, "root", "");
  $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  for($i = 1;$i<=12;$i++){
    //$query = "SELECT doencas.nome as doencas, diseased, recovered, confirmed, provincias.id as provincia_id,provincias.nome as provincia_nome, doencas.nome ,casos.date FROM provincias, casos , (SELECT SUM(quantidade) as diseased,tipo FROM casos WHERE tipo = 'M') as diseased, (SELECT SUM(quantidade) as recovered FROM casos WHERE tipo = 'R') as recovered, (SELECT SUM(quantidade) as confirmed,tipo FROM casos WHERE tipo = 'A') as confirmed LEFT JOIN doencas ON doencas.id = casos.doenca WHERE provincias.id = {$province} AND doenca = {$disease} LIMIT 1";
    $query = "SELECT mortes, recuperados, activos FROM (SELECT SUM(quantidade) AS mortes, EXTRACT(month FROM date) as mes FROM casos INNER JOIN municipios ON municipios.id = casos.municipio INNER JOIN provincias ON provincias.id = municipios.provincia INNER JOIN doencas ON doencas.id = casos.doenca WHERE tipo= 'M' AND EXTRACT(month FROM date) = {$i}  AND provincias.id = {$province} AND doencas.id = {$decease}) as mortes, (SELECT SUM(quantidade) AS recuperados FROM casos INNER JOIN municipios ON municipios.id = casos.municipio INNER JOIN provincias ON provincias.id = municipios.provincia INNER JOIN doencas ON doencas.id = casos.doenca WHERE tipo= 'R' AND EXTRACT(month FROM date) = {$i}  AND provincias.id = {$province} AND doencas.id = {$decease}) as recuperados, (SELECT SUM(quantidade) AS activos FROM casos INNER JOIN municipios ON municipios.id = casos.municipio INNER JOIN provincias ON provincias.id = municipios.provincia INNER JOIN doencas ON doencas.id = casos.doenca WHERE tipo= 'A' AND EXTRACT(month FROM date) = {$i} AND provincias.id = {$province} AND doencas.id = {$decease}) as activos";
    $exec = $conn->prepare($query);
    $exec->execute();
    array_push($json, $exec->fetch(PDO::FETCH_ASSOC));
  }

  return $json;
}
function queryCasesByDateProvince($province)
{
  $json = [];
  $str_conn = "mysql:hostname=localhost;dbname=srad;charset=utf8";
  $conn = new \PDO($str_conn, "root", "");
  $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  for($i = 1;$i<=12;$i++){
    //$query = "SELECT doencas.nome as doencas, diseased, recovered, confirmed, provincias.id as provincia_id,provincias.nome as provincia_nome, doencas.nome ,casos.date FROM provincias, casos , (SELECT SUM(quantidade) as diseased,tipo FROM casos WHERE tipo = 'M') as diseased, (SELECT SUM(quantidade) as recovered FROM casos WHERE tipo = 'R') as recovered, (SELECT SUM(quantidade) as confirmed,tipo FROM casos WHERE tipo = 'A') as confirmed LEFT JOIN doencas ON doencas.id = casos.doenca WHERE provincias.id = {$province} AND doenca = {$disease} LIMIT 1";
    $query = "SELECT SUM(quantidade) as quantidade, tipo FROM casos INNER JOIN municipios ON municipios.id = casos.municipio INNER JOIN provincias ON provincias.id = municipios.provincia WHERE EXTRACT(month FROM date) = {$i} AND provincias.id = {$province}";
    $exec = $conn->prepare($query);
    $exec->execute();
    array_push($json, $exec->fetch(PDO::FETCH_ASSOC));
  }

  return $json;
}

function returnJson($doenca)
{
  $json = array();
  $cases = getCasesByProvinceType($doenca);

  foreach($cases as $case)
  {
    if(!in_array($case["provincia_nome"], array_keys($json)))
    {
        $json[$case["provincia_nome"]] = array();
        
        $json[$case["provincia_nome"]]["dates"] = array();
    }
    else
    {
        $json[$case["provincia_nome"]]["dates"] += array(
          $case["date"]=>""
        );     
    }
    
    if(!in_array($case["date"], array_keys($json[$case["provincia_nome"]]["dates"])))
      {
          $json[$case["provincia_nome"]]["dates"] = array();
          $json[$case["provincia_nome"]]["dates"][$case["date"]] = array();
          
          $json = amountJsonData($json, $case);
      }
      else
      {          
        $json = amountJsonData($json, $case);
      }
    
  }
  return $json;
 
}

function amountJsonData($json, $case)
{
  $death = $case["tipo"] == "M" ? $case["cases"]: 0;
          $recoverd = $case["tipo"] == "R" ? $case["cases"]: 0;
          $confirmed = $case["tipo"] == "A" ? $case["cases"]: 0;
          $tested = $case["tipo"] == "A" ? $case["cases"]: 0;
  
            $json[$case["provincia_nome"]]["dates"][$case["date"]] =  array(
             "delta"=> [
              "deceased"=> $death,
             "recovered"=> $recoverd,
             "tested"=> $tested,
             "confirmed"=> $confirmed
             ],
             "delta7"=> [
              "deceased"=> $death,
             "recovered"=> $recoverd,
             "tested"=> $tested,
             "confirmed"=> $confirmed
             ],"total"=> [
             "deceased"=> $death,
             "recovered"=> $recoverd,
             "tested"=> $tested,
             "confirmed"=> $confirmed
             ]);

    return $json;
}

function str_slug(string $string): string
{
  $string = filter_var(mb_strtolower($string), FILTER_SANITIZE_STRIPPED);
  $formats = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
  $replace = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';
  $slug = str_replace(
    ["-----", "----", "---", "--"],
    "-",
    str_replace(
      " ",
      "-",
      trim(strtr(utf8_decode($string), utf8_decode($formats), $replace))
    )
  );
  return $slug;
}
/**
 * @param string $string
 * @return string
 */
function str_studly_case(string $string): string
{
  $string = str_slug($string);
  $studlyCase = str_replace(
    " ",
    "",
    mb_convert_case(str_replace("-", " ", $string), MB_CASE_TITLE)
  );
  return $studlyCase;
}
function jsonFormat($Data)
{
  $newData = [];

  if ($Data) {
    if (!is_array($Data))
      return $Data->data;


    foreach ($Data as $key => $data) {
      $newData[$key] = $data->data;
    }
  }
  return $newData;
}

function safeData($data)
{
  $data = array_map("htmlspecialchars", $data);

  $data = array_map("trim", $data);

  $data = array_map("stripslashes", $data);

  return $data;
}

function array_push_assoc($array, $key, $value)
{
  $array[$key] = $value;
  return $array;
}

function showMessage($message, $type)
{
  return json_encode([
    "message" => $message,
    "type" => $type
  ]);
}

function uploadFile($file, $key) {
  $target_dir ="uploads";
  if(!is_dir($target_dir))
     mkdir($target_dir);
  $newName = uniqid() . "-" . basename($file["name"][$key]);

  $target_file = $target_dir ."/". $newName;

  $uploadOk = 1;

  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" 
    && $imageFileType != "pdf") {
    return [
      "type" => "error",
      "result" => "Desculpe, apenas PDF, JPG, JPEG e PNG são permitidos."
    ];
  }
    
  if (move_uploaded_file($file["tmp_name"][$key], $target_file)) {
    return [
      "type" => "success",
      "result" => htmlspecialchars($newName)
    ];
  } else {
    return [
      "type" => "error",
      "result" => "Desculpe, erro ao carregar o seu arquivo."
    ];
  }
}
function sentSubscribed($qt, $doenca, $municipio, $estado){
    $doenca = (new Disease())->findById($doenca);
    $municipio = (new County())->findById($municipio);

    $data = date("d-m-Y");
    
    
    $civils = (new Civil())->find()->fetch(true);

    foreach($civils as $civil)
    {
      switch($estado){
        case 'M':
          $text = "Caro Cidadão(ã) {$civil->first_name}<br> Na data de {$data} Foram diagnosticados {$qt} casos Mortos de(a) {$doenca->nome} no Município de {$municipio->nome}";  
          break;
        case 'R':
          $text = "Caro Cidadão(ã) {$civil->first_name}<br> Na data de {$data} Foram diagnosticados {$qt} casos Recuperados de(a) {$doenca->nome} no Município de {$municipio->nome}";
          break;
        case 'A':
          $text = "Caro Cidadão(ã) {$civil->first_name}<br> Na data de {$data} Foram diagnosticados {$qt} casos Novos de(a) {$doenca->nome} no Município de {$municipio->nome}";
          break;
      }
      sendEmail(["nome"=>$civil->first_name." ".$civil->last_name, "email"=>$civil->email],$text);
    }
}
function sendEmail($to, $body,$attachment=null) {
  $mail = new PHPMailer();

  try {
      $mail->SMTPDebug = true;
      $mail->isSMTP(true);
      
      $mail->Host = MAIL["host"];                    // Set the SMTP server to send through
      $mail->SMTPAutoTLS = false;
      $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
      $mail->Username   = MAIL['user'];                     // SMTP username
      $mail->Password   = MAIL['passwd']; // SMTP password                               // SMTP password
      $mail->SMTPSecure = "ssl";         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
      $mail->Port       = MAIL['port'];                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
    
      $mail->CharSet = "utf-8";

      $mail->setFrom(MAIL['from_email'], 'SRAD - SISTEMA DE RASTREAMENTO E ANÁLISES DE DOENÇAS');
      $mail->addAddress($to["email"], $to["nome"]);
      if($attachment)
        $mail->addAttachment($attachment);

      $mail->isHTML(true);
      $mail->Subject = 'ACTUALIZAÇÃO - SRAD';
      $mail->Body = $body;
      $mail->send();
  } catch (Exception $e) {
      return $mail->ErrorInfo;
  }
}

function addAZero(string $n){
    return strlen($n) < 2 ? "0".$n : $n;
}

function transformTimeStamp($data){

  $schedule = explode(" ", $data);
  $data_room = str_replace("/","-",$schedule[0]);

  $schedule_array = explode("-", $data_room);
  
  $schedule_array = "{$schedule_array[2]}-".addAZero($schedule_array[1])."-".addAZero($schedule_array[0]);
  return $schedule_array." ".$schedule[1];

}
function transformToAOFormat($data){

  $schedule = explode(" ", $data);
  $data_room = str_replace("-","/",$schedule[0]);

  $schedule_array = explode("/", $data_room);
  
  $schedule_array = "{$schedule_array[2]}/".addAZero($schedule_array[1])."/".addAZero($schedule_array[0]);
  return $schedule_array;
  
}

function getTotalCasesWithCounties($cases)
{
    $data = [
      "total"=>0,
      "doenca"=>"",
      "doenca_id"=>0,
      "municipio"=>"",
      "municipio_id"=>""
    ];     

    $json = [
        "cases"=>null,
        "total"=>0
    ];

    array_walk($cases, function($arr, $key) use(&$data){
      $data["total"] += (int)$arr->quantidade;
      $disease_data = (new Disease())->findById((int)$arr->doenca);
      $county_data = (new County())->findById((int)$arr->municipio_id);
      $data["doenca"] = $disease_data->data()->nome;
      $data["doenca_id"] = $arr->doenca;
      $data["municipio_id"] = $county_data->id;
      $data["municipio"] = $arr->municipio;
    });

    $json["cases"] = jsonFormat($cases);
    $json["total"] = $data["total"];
    $json["doenca"] = $data["doenca"];
    $json["doenca_id"] = $data["doenca_id"];
    $json["municipio"] = $data["municipio"];
    $json["municipio_id"] = $data["municipio_id"];
    


    return $json;

}

function getTotalCasesWithProvinces($cases)
{
    $data = [
      "total"=>0,
      "doenca"=>"",
      "doenca_id"=>0,
      "provincia"=>"",
      "provincia_id"=>""
    ];     

    $json = [
        "cases"=>null,
        "total"=>0
    ];

    array_walk($cases, function($arr, $key) use(&$data){
      $data["total"] += (int)$arr->quantidade;
      $disease_data = (new Disease())->findById((int)$arr->doenca);
      $province_data = (new Provinces())->findById((int)$arr->provincias_id);
      $data["doenca"] = $disease_data->data()->nome;
      $data["doenca_id"] = $arr->doenca;
      $data["provincia_id"] = $province_data->id;
      $data["provincia"] = $province_data->nome;
    });

    $json["cases"] = jsonFormat($cases);
    $json["total"] = $data["total"];
    $json["doenca"] = $data["doenca"];
    $json["doenca_id"] = $data["doenca_id"];
    $json["provincia"] = $data["provincia"];
    $json["provincia_id"] = $data["provincia_id"];
    


    return $json;

}