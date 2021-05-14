<?php
use CoffeeCode\Router\Router;


header("Access-Control-Allow-Origin:  *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

require __DIR__ . "/vendor/autoload.php";



$router = new Router(URL_BASE);
$router->namespace("Source\Controllers");

resource($router, CONTROLLERS);
$router->group(null);

$router->get("/enviaremail",function(){
  sendEmail(["nome"=>"Bento Julio","email"=>"carlosnguia2012@gmail.com"], "Ola mundo");
});
$router->get("/ultimoupdate","Statistics:lastUpdate");
$router->get("/resetarsenha","UserRepository:resetPassword");

$router->group("informacoes");
$router->get("/infofront","infoRepository:readFront");

$router->group("doencas");
$router->get("/{disease}/municipios/{county}","Statistics:casesByCounties");
$router->get("/{disease}/provincias/{province}","Statistics:casesByProvinces");
$router->get("/{disease}","Statistics:casesAllProvinces");
$router->get("/casospormunicipios/{provincia}/{doenca}","Statistics:countiesCasesByProvinces");

$router->group("casos");
$router->get("/casosnome/{municipio}", "CasesRepository:readAll");
$router->get("/casospormes", "Statistics:showMonth");
$router->get("/casosemdataprovincias/{province}/{decease}/", "Statistics:casesPerDateByProvinceAndDecease");
$router->get("/casosporprovincias/{decease}","Statistics:casesByProvincesBack");
$router->get("/casospordoenca/{county}/","Statistics:getCasesByDeseaseBack");
$router->get("/casospordata/{disease}","Statistics:casesByDiseaseDate");

$router->group("login");
$router->post("/", "Login:login", "web.login");

$router->group("relatorio");
$router->get("/casosporprovinciascinco","Report:casesByProvincesFirstFive");
$router->get("/casosporprovinciaspordoenca/{decease}","Report:casesByProvincesByDecease");
$router->get("/casospormunicipios/{province}","Report:casesByCounties");

$router->dispatch();

if ($router->error()) {
  echo json_encode([
    "message" => "A rota que prentende acessar é inválida"
  ]);
}

?>