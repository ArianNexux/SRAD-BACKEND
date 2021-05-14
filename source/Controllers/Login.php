<?php 


namespace Source\Controllers;

use Source\Models\Users;

class Login{


    public function login($data)
    {
      
        $user = (new Users())->find("email = '{$data["email"]}'")->fetch();
        if(in_array("",$data))
        {
            echo json_encode([
                "type"=>"warning",
                "message"=>"Campos obrigatórios vazios."
            ]);
            http_response_code(204);
            return;
        }
        
        if(!$user)
        {
            echo json_encode([
                "type"=>"warning",
                "message"=>"Verifique o seu e-mail, o usuário informado não existe."
            ]);
            http_response_code(204);
            return;
        }

        if(!password_verify($data["senha"], $user->senha))
        {
            echo json_encode([
                "type"=>"warning",
                "message"=>"Senha incorreta, Verifique a sua senha."
            ]);
            http_response_code(204);
            return;
        }


        echo json_encode([
            "type"=>"sucsess",
            "message"=>"Login efectuado com sucesso.",
            "data"=>$user->data()
        ]);
        http_response_code(200);
        

    }
}