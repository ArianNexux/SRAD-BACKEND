<?php 

namespace Source\Traits;

use Source\Models\Users;

trait UserTrait{
    public function updatePassword($data)
    {
        $this->model = (new Users())->findById($data["user"]);

        if($data["new_password"] != $data["confirm_password"]){
            echo json_encode([
                "message"=>"A Senha de confirmação e a nova senha devem ser iguais.",
                "type"=>"warning"
            ]);
            http_response_code(204);
            return;
        }
        
        $this->model->password = password_hash($data["new_password"],PASSWORD_DEFAULT);
        $this->model->firstTime = 1;
        
        if(!$this->model->save()){
            var_dump($this->model->fail());
            http_response_code(204);
            return;
        }
        echo json_encode(jsonFormat($this->model));
        http_response_code(200);
        
    }

    public function userVerify($data)
    {
        
      $user = $this->model->find("users.email ='{$data["email"]}'", null, "*,users.password as password,users.id as id, judge.id as judgeId, judge.type as typeJudge, users.type as type, contests.name as nameContests, users.name as name, contests.id as idContest", "LEFT JOIN judge ON judge.user_judge = users.id LEFT JOIN contests ON contests.id = judge.contest")->fetch();
    

      if(!$user){
        echo json_encode([
            "message"=>"E-mail incorreto ou Concurso fora de validade.",
            "type"=>"error"
        ]);
        http_response_code(204);
        return; 
      }

      if($user->type == "Júri"){
        $user = $this->model->find("users.email ='{$data["email"]}' AND CURRENT_TIMESTAMP BETWEEN contests.start_date AND contests.due_date", null, "*,users.password as password,users.id as id, judge.id as judgeId, judge.type as typeJudge, users.type as type, contests.name as nameContests, users.name as name, contests.id as idContest", "LEFT JOIN judge ON judge.user_judge = users.id LEFT JOIN contests ON contests.id = judge.contest")->fetch();          
        
        if(!$user){
            echo json_encode([
                "message"=>"Concurso inválido.",
                "type"=>"error"
            ]);
            http_response_code(200);
            return;
        }
      }
     
        $fileName = uploadFile($_FILES["qr_code"],0)["result"];
        $qrText = decodeQrCode("uploads/".$fileName);

        if(!$qrText){
            echo json_encode([
                "message"=>"QRCode Inválido.",
                "type"=>"error"
            ]);
            http_response_code(204);
            return; 
        }
        //var_dump($user->qr_code);die();
        $userQrCodeBD = decodeQrCode("QrCodes/".$user->qr_code);

        if(strcmp($userQrCodeBD, $qrText) != 0){
            echo json_encode([
                "message"=>"QrCode incorreto.",
                "type"=>"error"
            ]);
           http_response_code(204);
            return;
        }
        unlink("uploads/".$fileName);

        if (!password_verify($data["password"], $user->password)) {
            echo json_encode([
                "message"=>"A Senha inserida está incorreta.",
                "type"=>"error"
            ]);
            http_response_code(204);
            return;
        }


        echo json_encode(jsonFormat($user));
        http_response_code(200);
    }
}