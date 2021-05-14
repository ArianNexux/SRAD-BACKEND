<?php
namespace Source\Controllers;


use Dompdf\Dompdf;
use Source\Models\Disease;
use Source\Models\Provinces;

ini_set("memory_limit", "-1");

class Report{
    
    private $dompdf;
    private $pdf;
    

    public function __construct(){
        $this->dompdf = new Dompdf(["enable_remote"=>true]);
    }
    
   
  

    public function casesByProvincesFirstFive($data){
        $diseaseFor = queryBuilder();
        $provinces = (new Provinces())->find()->fetch(true);
        $diseases = (new Disease())->find()->fetch(true);
        $json = getProvincesAssociatedInDisease();
        

        $counter = count($diseaseFor);
        ob_start();
        require __DIR__."/../../reports/ByProvincesFirtstFive.php";
        $this->dompdf->loadHtml(ob_get_clean());

        $this->dompdf->setPaper("A4", 'landscape');
        $this->dompdf->render();
        $this->dompdf->stream("file.pdf",["Attachment"=>false]);

    }
    public function casesByProvincesByDecease($data){
        
        $provinces = (new Provinces())->find()->fetch(true);
        $diseases = (new Disease())->findById($data["decease"])->data();
        $json = getProvincesAssociatedInDisease();
        $diseaseFor =  queryBuilderByDecease($data["decease"]);

        ob_start();
        require __DIR__."/../../reports/ByProvincesPorDoenca.php";
        $this->dompdf->loadHtml(ob_get_clean());

        $this->dompdf->setPaper("A4", 'landscape');
        $this->dompdf->render();
        $this->dompdf->stream("file.pdf",["Attachment"=>false]);

    }
    
    public function casesByCounties($data){
        
        ob_start();
        require __DIR__."/../../reports/ByCounties.php";
        $this->dompdf->loadHtml(ob_get_clean());

        $this->dompdf->setPaper("A4", 'landscape');
        $this->dompdf->render();
        $this->dompdf->stream("file.pdf",["Attachment"=>false]);

    }
}