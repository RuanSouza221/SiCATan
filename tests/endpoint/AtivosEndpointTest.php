<?php declare(strict_types=1);
namespace endpoint;

use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

final class AtivosEndpointTest extends TestCase
{
    public function testPostAtivoEndpoint(): void
    {
        $cliente = new Client(['base_uri' => getenv("BASE_URI"),'http_errors' => false]);

        $_dados["descricao"] = "Ativo_10";
        $_dados["categoria"] = "Categoria_10";
        $_dados["ativo_ambiente"] = NULL;
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->post("ativos",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertSame(201,$response->getStatusCode(),$retorno);
        $this->assertJson($retorno);
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Ativo criado",json_decode($retorno)->message);
        $this->assertSame(36,strlen(json_decode($retorno)->data->id));
        $this->assertSame("Ativo_10",json_decode($retorno)->data->descricao);
        $this->assertSame("Categoria_10",json_decode($retorno)->data->categoria);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame(0,json_decode($retorno)->data->inativo);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",json_decode($retorno)->data->organizacao);
        $this->assertSame(NULL,json_decode($retorno)->data->ativo_ambiente);

        $_dados["descricao"] = "Ativo_11";
        $_dados["categoria"] = "Categoria_11";
        $_dados["ativo_ambiente"] = json_decode($retorno)->data->id;
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->post("ativos",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertSame(201,$response->getStatusCode(),$retorno);
        $this->assertJson($retorno);
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Ativo criado",json_decode($retorno)->message);
        $this->assertSame(36,strlen(json_decode($retorno)->data->id));
        $this->assertSame("Ativo_11",json_decode($retorno)->data->descricao);
        $this->assertSame("Categoria_11",json_decode($retorno)->data->categoria);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame(0,json_decode($retorno)->data->inativo);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",json_decode($retorno)->data->organizacao);
        $this->assertSame($_dados["ativo_ambiente"],json_decode($retorno)->data->ativo_ambiente);

        $_dados["descricao"] = "";
        $_dados["categoria"] = "Categoria_12";
        $_dados["ativo_ambiente"] = NULL;
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->post("ativos",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertSame(400,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Dados ausentes",json_decode($retorno)->message);

        $_dados["descricao"] = "Ativo_12";
        $_dados["categoria"] = "";
        $_dados["ativo_ambiente"] = NULL;
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->post("ativos",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertSame(400,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Dados ausentes",json_decode($retorno)->message);

        $_dados["descricao"] = "Ativo_12";
        $_dados["categoria"] = "Categoria_12";
        $_dados["ativo_ambiente"] = NULL;
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjozLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.jnCJxuWAUCKTg5G5XYmK1axgC-25n6b7MqE2zN1d8uM";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=3
        $response = $cliente->post("ativos",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertSame(403,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Usuario sem permissão",json_decode($retorno)->message);

        $_dados["descricao"] = "Ativo_12";
        $_dados["categoria"] = "Categoria_12";
        $_dados["ativo_ambiente"] = NULL;
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6ImU5YmM4NGZmLWY2NmItNDFlOS1hMGZlLWQwYTgyYTAwYTU2NyIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMkBtYWlsLmNvbSJ9.die2vKmI1SD9rcmeHcQqtYYLlyb_1bVFjjMuGyuovCA";
        //token / id = e9bc84ff-f66b-41e9-a0fe-d0a82a00a567 / nivel=1
        $response = $cliente->post("ativos",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertSame(403,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Usuario sem permissão",json_decode($retorno)->message);
    }

    public function testPutAtivoEndpoint(): void
    {
        $cliente = new Client(['base_uri' => getenv("BASE_URI"),'http_errors' => false]);

        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->get("ativos",["query"=>["descricao"=>"Ativo_10"],"headers"=>["Authorization"=>"Bearer ".$token]]);
        $_ativo_10 = json_decode($response->getBody()->getContents(),true)[0];
        $response = $cliente->get("ativos",["query"=>["descricao"=>"Ativo_11"],"headers"=>["Authorization"=>"Bearer ".$token]]);
        $_ativo_11 = json_decode($response->getBody()->getContents(),true)[0];

        $_dados["id"] = $_ativo_11["id"];
        $_dados["descricao"] = "Ativo 11";
        $_dados["categoria"] = "Categoria 11";
        $_dados["inativo"] = 1;
        $_dados["ativo_ambiente"] = NULL;
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->put("ativos",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Ativo atualizado com sucesso",json_decode($retorno)->message);
        $this->assertSame($_ativo_11["id"],json_decode($retorno)->data->id);
        $this->assertSame("Ativo 11",json_decode($retorno)->data->descricao);
        $this->assertSame("Categoria 11",json_decode($retorno)->data->categoria);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame($_ativo_11["organizacao"],json_decode($retorno)->data->organizacao);
        $this->assertSame(1,json_decode($retorno)->data->inativo);
        $this->assertSame(NULL,json_decode($retorno)->data->ativo_ambiente);
        unset($_dados);

        $_dados["id"] = $_ativo_10["id"];
        $_dados["descricao"] = "Ativo 10";
        $_dados["categoria"] = "Categoria 10";
        $_dados["inativo"] = 1;
        $_dados["ativo_ambiente"] = $_ativo_11["id"];
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->put("ativos",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Ativo atualizado com sucesso",json_decode($retorno)->message);
        $this->assertSame($_ativo_10["id"],json_decode($retorno)->data->id);
        $this->assertSame("Ativo 10",json_decode($retorno)->data->descricao);
        $this->assertSame("Categoria 10",json_decode($retorno)->data->categoria);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame($_ativo_10["organizacao"],json_decode($retorno)->data->organizacao);
        $this->assertSame(1,json_decode($retorno)->data->inativo);
        $this->assertSame($_ativo_11["id"],json_decode($retorno)->data->ativo_ambiente);
        unset($_dados);

        $_dados["id"] = $_ativo_11["id"];
        $_dados["descricao"] = "Ativo_11";
        $_dados["categoria"] = "Categoria_11";
        $_dados["inativo"] = 0;
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->put("ativos",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Ativo atualizado com sucesso",json_decode($retorno)->message);
        $this->assertSame($_ativo_11["id"],json_decode($retorno)->data->id);
        $this->assertSame("Ativo_11",json_decode($retorno)->data->descricao);
        $this->assertSame("Categoria_11",json_decode($retorno)->data->categoria);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame($_ativo_11["organizacao"],json_decode($retorno)->data->organizacao);
        $this->assertSame(0,json_decode($retorno)->data->inativo);
        $this->assertSame(NULL,json_decode($retorno)->data->ativo_ambiente);
        unset($_dados);

        $_dados["id"] = $_ativo_10["id"];
        $_dados["descricao"] = "Ativo_10";
        $_dados["categoria"] = "Categoria_10";
        $_dados["inativo"] = 0;
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->put("ativos",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Ativo atualizado com sucesso",json_decode($retorno)->message);
        $this->assertSame($_ativo_10["id"],json_decode($retorno)->data->id);
        $this->assertSame("Ativo_10",json_decode($retorno)->data->descricao);
        $this->assertSame("Categoria_10",json_decode($retorno)->data->categoria);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame($_ativo_10["organizacao"],json_decode($retorno)->data->organizacao);
        $this->assertSame(0,json_decode($retorno)->data->inativo);
        $this->assertSame($_ativo_11["id"],json_decode($retorno)->data->ativo_ambiente);
        unset($_dados);

        $_dados["id"] = $_ativo_10["id"];
        $_dados["descricao"] = "Ativo 10";
        $_dados["categoria"] = "Categoria 10";
        $_dados["inativo"] = "1";
        $_dados["ativo_ambiente"] = $_ativo_11["id"];
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjozLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.jnCJxuWAUCKTg5G5XYmK1axgC-25n6b7MqE2zN1d8uM";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=3
        $response = $cliente->put("ativos",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(403,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Sem permissão",json_decode($retorno)->message);
        unset($_dados);

        $_dados["id"] = $_ativo_10["id"];
        $_dados["descricao"] = "Ativo 10";
        $_dados["categoria"] = "Categoria 10";
        $_dados["inativo"] = "1";
        $_dados["ativo_ambiente"] = $_ativo_11["id"];
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6ImU5YmM4NGZmLWY2NmItNDFlOS1hMGZlLWQwYTgyYTAwYTU2NyIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMkBtYWlsLmNvbSJ9.die2vKmI1SD9rcmeHcQqtYYLlyb_1bVFjjMuGyuovCA";
        //token / id = e9bc84ff-f66b-41e9-a0fe-d0a82a00a567 / nivel=1
        $response = $cliente->put("ativos",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(403,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Sem permissão",json_decode($retorno)->message);
        unset($_dados);

        $_dados["id"] = $_ativo_10["id"];
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjozLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.jnCJxuWAUCKTg5G5XYmK1axgC-25n6b7MqE2zN1d8uM";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=3
        $response = $cliente->put("ativos",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(400,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Dados ausentes",json_decode($retorno)->message);
        unset($_dados);

        $_dados["descricao"] = "Ativo 10";
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjozLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.jnCJxuWAUCKTg5G5XYmK1axgC-25n6b7MqE2zN1d8uM";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=3
        $response = $cliente->put("ativos",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(400,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Dados ausentes",json_decode($retorno)->message);
        unset($_dados);
    }

    public function testGetAtivoEndpoint(): void
    {
        $cliente = new Client(['base_uri' => getenv("BASE_URI"),'http_errors' => false]);

        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->get("ativos",["query"=>["descricao"=>"Ativo_10"],"headers"=>["Authorization"=>"Bearer ".$token]]);
        $_ativo_10 = json_decode($response->getBody()->getContents(),true)[0];
        $response = $cliente->get("ativos",["query"=>["descricao"=>"Ativo_11"],"headers"=>["Authorization"=>"Bearer ".$token]]);
        $_ativo_11 = json_decode($response->getBody()->getContents(),true)[0];

        //id
        $ID = $_ativo_11["id"];
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->get("ativos/$ID",["headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(1,count(json_decode($retorno)));
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame($_ativo_11["id"],json_decode($retorno)[0]->id);
        $this->assertSame("Ativo_11",json_decode($retorno)[0]->descricao);
        $this->assertSame("Categoria_11",json_decode($retorno)[0]->categoria);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[0]->data_cad));
        $this->assertSame($_ativo_11["organizacao"],json_decode($retorno)[0]->organizacao);
        $this->assertSame(0,json_decode($retorno)[0]->inativo);
        $this->assertSame(NULL,json_decode($retorno)[0]->ativo_ambiente);
        $this->assertSame(1,json_decode($retorno)[0]->conteudo_qtd);
        unset($ID,$token,$_dados);

        //descricao
        $_dados["descricao"] = "_11";
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->get("ativos",["query"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(1,count(json_decode($retorno)));
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame($_ativo_11["id"],json_decode($retorno)[0]->id);
        $this->assertSame("Ativo_11",json_decode($retorno)[0]->descricao);
        $this->assertSame("Categoria_11",json_decode($retorno)[0]->categoria);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[0]->data_cad));
        $this->assertSame($_ativo_11["organizacao"],json_decode($retorno)[0]->organizacao);
        $this->assertSame(0,json_decode($retorno)[0]->inativo);
        $this->assertSame(NULL,json_decode($retorno)[0]->ativo_ambiente);
        $this->assertSame(1,json_decode($retorno)[0]->conteudo_qtd);
        unset($token,$_dados);

        //categoria
        $_dados["categoria"] = "Categoria_11";
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->get("ativos",["query"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(1,count(json_decode($retorno)));
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame($_ativo_11["id"],json_decode($retorno)[0]->id);
        $this->assertSame("Ativo_11",json_decode($retorno)[0]->descricao);
        $this->assertSame("Categoria_11",json_decode($retorno)[0]->categoria);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[0]->data_cad));
        $this->assertSame($_ativo_11["organizacao"],json_decode($retorno)[0]->organizacao);
        $this->assertSame(0,json_decode($retorno)[0]->inativo);
        $this->assertSame(NULL,json_decode($retorno)[0]->ativo_ambiente);
        $this->assertSame(1,json_decode($retorno)[0]->conteudo_qtd);
        unset($token,$_dados);

        //organizacao
        $_dados["organizacao"] = "aeab3060-01fe-4809-9a55-01a50da7dacb";
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6ImMyYWVlYTEwLTZjYWUtNDM1Yy1iYjZkLWFlMzAyY2I2Y2I1MyIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMV8xQG1haWwuY29tIn0.331Y1jI5tjes5C4EHDF6dbbFstfUQct9Ed0Igl3k7aI";
        //token / id = c2aeea10-6cae-435c-bb6d-ae302cb6cb53 / nivel=1
        $response = $cliente->get("ativos",["query"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(1,count(json_decode($retorno)));
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame("9cbd7405-ef8d-43f3-bc55-bcc78da4613d",json_decode($retorno)[0]->id);
        $this->assertSame("Ativo_2",json_decode($retorno)[0]->descricao);
        $this->assertSame("categoria_2",json_decode($retorno)[0]->categoria);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[0]->data_cad));
        $this->assertSame("aeab3060-01fe-4809-9a55-01a50da7dacb",json_decode($retorno)[0]->organizacao);
        $this->assertSame(0,json_decode($retorno)[0]->inativo);
        $this->assertSame(NULL,json_decode($retorno)[0]->ativo_ambiente);
        $this->assertSame(0,json_decode($retorno)[0]->conteudo_qtd);
        unset($token,$_dados);

        //organizacao
        $_dados["organizacao"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->get("ativos",["query"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(3,count(json_decode($retorno)));
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame("61aec667-2716-4672-8765-85a87226d05f",json_decode($retorno)[0]->id);
        $this->assertSame("Ativo_1",json_decode($retorno)[0]->descricao);
        $this->assertSame("categoria_1",json_decode($retorno)[0]->categoria);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[0]->data_cad));
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",json_decode($retorno)[0]->organizacao);
        $this->assertSame(0,json_decode($retorno)[0]->inativo);
        $this->assertSame(NULL,json_decode($retorno)[0]->ativo_ambiente);
        $this->assertSame(1,json_decode($retorno)[0]->conteudo_qtd);

        $this->assertSame($_ativo_11["id"],json_decode($retorno)[2]->id);
        $this->assertSame("Ativo_11",json_decode($retorno)[2]->descricao);
        $this->assertSame("Categoria_11",json_decode($retorno)[2]->categoria);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[2]->data_cad));
        $this->assertSame($_ativo_11["organizacao"],json_decode($retorno)[2]->organizacao);
        $this->assertSame(0,json_decode($retorno)[2]->inativo);
        $this->assertSame(NULL,json_decode($retorno)[2]->ativo_ambiente);
        $this->assertSame(1,json_decode($retorno)[2]->conteudo_qtd);
        unset($token,$_dados);

        //ativo_ambiente
        $_dados["ativo_ambiente"] = $_ativo_11["id"];
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->get("ativos",["query"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(1,count(json_decode($retorno)));
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame($_ativo_10["id"],json_decode($retorno)[0]->id);
        $this->assertSame("Ativo_10",json_decode($retorno)[0]->descricao);
        $this->assertSame("Categoria_10",json_decode($retorno)[0]->categoria);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[0]->data_cad));
        $this->assertSame($_ativo_10["organizacao"],json_decode($retorno)[0]->organizacao);
        $this->assertSame(0,json_decode($retorno)[0]->inativo);
        $this->assertSame($_ativo_11["id"],json_decode($retorno)[0]->ativo_ambiente);
        $this->assertSame(0,json_decode($retorno)[0]->conteudo_qtd);
        unset($token,$_dados);

        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->get("ativos",["headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(400,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Dados ausentes",json_decode($retorno)->message);
        unset($token);

        $_dados["categoria"] = "Categoria_11";
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6ImU5YmM4NGZmLWY2NmItNDFlOS1hMGZlLWQwYTgyYTAwYTU2NyIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMkBtYWlsLmNvbSJ9.die2vKmI1SD9rcmeHcQqtYYLlyb_1bVFjjMuGyuovCA";
        //token / id = e9bc84ff-f66b-41e9-a0fe-d0a82a00a567 / nivel=1
        $response = $cliente->get("ativos",["query"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(404,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Resultado não encontrado",json_decode($retorno)->message);
        unset($token,$_dados);
    }
}