<?php declare(strict_types=1);
namespace endpoint;

use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

final class OrganizacaoEndpointTest extends TestCase
{
    public function testPostOrganizacaoEndpoint(): void
    {
        $cliente = new Client(['base_uri' => getenv("BASE_URI"),'http_errors' => false]);

        $cliente->post("usuarios",["json"=>["nome"=>"Usuario_10","email"=>"Usuario_10@mail.com","senha"=>"z)B;H"]]);
        $response = $cliente->post("login",["json"=>["email"=>"Usuario_10@mail.com","senha"=>'z)B;H']]);
        $_user_10["token"] = json_decode($response->getBody()->getContents())->data->access_token;
        $_user_10["id"] = (new Parser(new JoseEncoder()))->parse($_user_10["token"])->claims()->get('id');

        $_dados["descricao"] = "Organizacao_5";
        $token = $_user_10["token"];
        $response = $cliente->post("organizacao",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertSame(201,$response->getStatusCode(),$retorno);
        $this->assertJson($retorno);
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Organização criada",json_decode($retorno)->message);
        $this->assertSame(36,strlen(json_decode($retorno)->data->id));
        $_user_10["organizacao"] = json_decode($retorno)->data->id;
        $this->assertSame("Organizacao_5",json_decode($retorno)->data->descricao);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame(0,json_decode($retorno)->data->inativo);

        $response = $cliente->get("usuarios/".$_user_10["id"],["headers"=>["Authorization"=>"Bearer ".$_user_10["token"]]]);
        $retorno = $response->getBody()->getContents();
        $this->assertSame($_user_10["id"],json_decode($retorno)[0]->id);
        $this->assertSame($_user_10["organizacao"],json_decode($retorno)[0]->organizacao);


        $_dados["descricao"] = "Organizacao_6";
        $token = $_user_10["token"];
        $response = $cliente->post("organizacao",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertSame(401,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("já possui uma organização",json_decode($retorno)->message);

        $_dados["descricao"] = "Organizacao_6";
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6ImU5YmM4NGZmLWY2NmItNDFlOS1hMGZlLWQwYTgyYTAwYTU2NyIsIm5pdmVsIjoyLCJlbWFpbCI6IlVzdWFyaW9fMkBtYWlsLmNvbSJ9.5_CGXjNC_B_MGiHTEttZUQKpMjDH6XbHV6ClTNyYrRk";
        //token / id = e9bc84ff-f66b-41e9-a0fe-d0a82a00a567 / nivel=2
        $response = $cliente->post("organizacao",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertSame(403,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Sem permissão",json_decode($retorno)->message);

        $_dados["descricao"] = "Organizacao_6";
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6ImU5YmM4NGZmLWY2NmItNDFlOS1hMGZlLWQwYTgyYTAwYTU2NyIsIm5pdmVsIjozLCJlbWFpbCI6IlVzdWFyaW9fMkBtYWlsLmNvbSJ9.rCKZ7JcnRe0yyRf3BJKnuZI9QopbmLLZjjg7_kgo_n0";
        //token / id = e9bc84ff-f66b-41e9-a0fe-d0a82a00a567 / nivel=3
        $response = $cliente->post("organizacao",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertSame(403,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Sem permissão",json_decode($retorno)->message);

        $_dados["descricao"] = "";
        $token = $_user_10["token"];
        $response = $cliente->post("organizacao",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertSame(401,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Dados ausentes",json_decode($retorno)->message);
    }

    public function testPutOrganizacaoEndpoint(): void
    {
        $cliente = new Client(['base_uri' => getenv("BASE_URI"),'http_errors' => false]);

        $response = $cliente->post("login",["json"=>["email"=>"Usuario_10@mail.com","senha"=>'z)B;H']]);
        $_user_10["token"] = json_decode($response->getBody()->getContents())->data->access_token;
        $_user_10["id"] = (new Parser(new JoseEncoder()))->parse($_user_10["token"])->claims()->get('id');
        $response = $cliente->get("usuarios/".$_user_10["id"],["headers"=>["Authorization"=>"Bearer ".$_user_10["token"]]]);
        $retorno = $response->getBody()->getContents();
        $_user_10["organizacao"] = json_decode($retorno)[0]->organizacao;

        $_dados["id"] = $_user_10["organizacao"];
        $_dados["descricao"] = "Organizacao 5";
        $_dados["inativo"] = 1;
        $token = $_user_10["token"];
        $response = $cliente->put("organizacao",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Organização atualizada com sucesso",json_decode($retorno)->message);
        $this->assertSame($_user_10["organizacao"],json_decode($retorno)->data->id);
        $this->assertSame("Organizacao 5",json_decode($retorno)->data->descricao);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame(1,json_decode($retorno)->data->inativo);
        unset($_dados);

        $_dados["id"] = $_user_10["organizacao"];
        $_dados["descricao"] = "Organizacao_5";
        $token = $_user_10["token"];
        $response = $cliente->put("organizacao",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Organização atualizada com sucesso",json_decode($retorno)->message);
        $this->assertSame($_user_10["organizacao"],json_decode($retorno)->data->id);
        $this->assertSame("Organizacao_5",json_decode($retorno)->data->descricao);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame(1,json_decode($retorno)->data->inativo);
        unset($_dados);

        $_dados["id"] = $_user_10["organizacao"];
        $_dados["inativo"] = 0;
        $token = $_user_10["token"];
        $response = $cliente->put("organizacao",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Organização atualizada com sucesso",json_decode($retorno)->message);
        $this->assertSame($_user_10["organizacao"],json_decode($retorno)->data->id);
        $this->assertSame("Organizacao_5",json_decode($retorno)->data->descricao);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame(0,json_decode($retorno)->data->inativo);
        unset($_dados);

        $_dados["id"] = $_user_10["organizacao"];
        $_dados["inativo"] = 1;
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoyLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.yiA6sYs_gD6OU3PgCNmd9zgZ-RQYsoWUfIwJY_R1Op8";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=2
        $response = $cliente->put("organizacao",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(403,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Sem permissão",json_decode($retorno)->message);
        unset($_dados);

        $_dados["id"] = $_user_10["organizacao"];
        $_dados["descricao"] = "sempermissao";
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoyLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.yiA6sYs_gD6OU3PgCNmd9zgZ-RQYsoWUfIwJY_R1Op8";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=2
        $response = $cliente->put("organizacao",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(403,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Sem permissão",json_decode($retorno)->message);
        unset($_dados);

        $_dados["id"] = $_user_10["organizacao"];
        $_dados["inativo"] = 1;
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjozLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.jnCJxuWAUCKTg5G5XYmK1axgC-25n6b7MqE2zN1d8uM";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=3
        $response = $cliente->put("organizacao",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(403,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Sem permissão",json_decode($retorno)->message);
        unset($_dados);

        $_dados["id"] = $_user_10["organizacao"];
        $_dados["descricao"] = "sempermissao";
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjozLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.jnCJxuWAUCKTg5G5XYmK1axgC-25n6b7MqE2zN1d8uM";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=3
        $response = $cliente->put("organizacao",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(403,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Sem permissão",json_decode($retorno)->message);
        unset($_dados);

        $_dados["id"] = $_user_10["organizacao"];
        $_dados["inativo"] = 1;
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoyLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.yiA6sYs_gD6OU3PgCNmd9zgZ-RQYsoWUfIwJY_R1Op8";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=2
        $response = $cliente->put("organizacao",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(403,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Sem permissão",json_decode($retorno)->message);
        unset($_dados);

        $_dados["inativo"] = 1;
        $token = $_user_10["token"];
        $response = $cliente->put("organizacao",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(400,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Dados ausentes",json_decode($retorno)->message);
        unset($_dados);

        $_dados["id"] = $_user_10["organizacao"];
        $token = $_user_10["token"];
        $response = $cliente->put("organizacao",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(400,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Dados ausentes",json_decode($retorno)->message);
        unset($_dados);

        $_dados["id"] = $_user_10["organizacao"];
        $_dados["inativo"] = 1;
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->put("organizacao",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(403,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Sem permissão",json_decode($retorno)->message);
        unset($_dados);
    }

    public function testGetOrganizacaoEndpoint(): void
    {
        $cliente = new Client(['base_uri' => getenv("BASE_URI"),'http_errors' => false]);

        $response = $cliente->post("login",["json"=>["email"=>"Usuario_10@mail.com","senha"=>'z)B;H']]);
        $_user_10["token"] = json_decode($response->getBody()->getContents())->data->access_token;
        $_user_10["id"] = (new Parser(new JoseEncoder()))->parse($_user_10["token"])->claims()->get('id');
        $response = $cliente->get("usuarios/".$_user_10["id"],["headers"=>["Authorization"=>"Bearer ".$_user_10["token"]]]);
        $retorno = $response->getBody()->getContents();
        $_user_10["organizacao"] = json_decode($retorno)[0]->organizacao;

        $ID = $_user_10["organizacao"];
        $token = $_user_10["token"];
        $response = $cliente->get("organizacao/$ID",["headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(1,count(json_decode($retorno)));
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame($_user_10["organizacao"],json_decode($retorno)[0]->id);
        $this->assertSame("Organizacao_5",json_decode($retorno)[0]->descricao);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[0]->data_cad));
        $this->assertSame(0,json_decode($retorno)[0]->inativo);
        unset($ID,$token);

        $ID = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->get("organizacao/$ID",["headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(1,count(json_decode($retorno)));
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",json_decode($retorno)[0]->id);
        $this->assertSame("Organização_1",json_decode($retorno)[0]->descricao);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[0]->data_cad));
        $this->assertSame(0,json_decode($retorno)[0]->inativo);
        unset($ID,$token);

        $ID = "";
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->get("organizacao/$ID",["headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(400,$response->getStatusCode(),$retorno);
        $this->assertSame("Dados ausentes",json_decode($retorno)->error);
        unset($ID,$token);

        $ID = $_user_10["organizacao"];
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->get("organizacao/$ID",["headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(403,$response->getStatusCode(),$retorno);
        $this->assertSame("Usuario sem permissão",json_decode($retorno)->error);
        unset($ID,$token);

        $ID = "Idnãoexistente";
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->get("organizacao/$ID",["headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(403,$response->getStatusCode(),$retorno);
        $this->assertSame("Usuario sem permissão",json_decode($retorno)->error);
        unset($ID,$token);
    }
}