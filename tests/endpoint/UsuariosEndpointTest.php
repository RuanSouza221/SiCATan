<?php declare(strict_types=1);
namespace endpoint;

use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Validator;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

final class UsuariosEndpointTest extends TestCase
{
    public function testCriarContaEndpoint(): void
    {
        $cliente = new Client(['base_uri' => getenv("BASE_URI"),'http_errors' => false]);

        $_dados["nome"] = "Usuario_6";
        $_dados["email"] = "Usuario_6@mail.com";
        $_dados["senha"] = 'z)B;HQDe6iD+ZJ`bbXEM';
        $response = $cliente->post("usuarios",["json"=>$_dados]);
        $retorno = $response->getBody()->getContents();
        $this->assertSame(201,$response->getStatusCode(),$retorno);
        $this->assertSame(36,strlen(json_decode($retorno)->data->id));
        $this->assertSame("Usuario_6",json_decode($retorno)->data->nome);
        $this->assertSame("Usuario_6@mail.com",json_decode($retorno)->data->email);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame(NULL,json_decode($retorno)->data->data_mod);
        $this->assertSame(1,json_decode($retorno)->data->nivel);
        $this->assertSame(NULL,json_decode($retorno)->data->organizacao);


        $_dados["nome"] = "Usuario_7";
        $_dados["email"] = "Usuario_7@mail.com";
        $_dados["senha"] = 'MUCu3_+A<~VIQ2%T#Y2z';
        $_dados["nivel"] = 3;
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->post("usuarios",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertSame(201,$response->getStatusCode(),$retorno);
        $this->assertSame(36,strlen(json_decode($retorno)->data->id));
        $this->assertSame("Usuario_7",json_decode($retorno)->data->nome);
        $this->assertSame("Usuario_7@mail.com",json_decode($retorno)->data->email);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame(NULL,json_decode($retorno)->data->data_mod);
        $this->assertSame(3,json_decode($retorno)->data->nivel);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",json_decode($retorno)->data->organizacao);

        $_dados["nome"] = "Usuario_8";
        $_dados["email"] = "Usuario_8@mail.com";
        $_dados["senha"] = 'MUCu3_+A<~VIQ2%T#Y2z';
        $_dados["nivel"] = 3;
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjozLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.jnCJxuWAUCKTg5G5XYmK1axgC-25n6b7MqE2zN1d8uM";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=3
        $response = $cliente->post("usuarios",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertSame(403,$response->getStatusCode(),$retorno);
        $this->assertSame("Usuario sem permissão",json_decode($retorno)->errors->message);

        $_dados["nome"] = "Usuario_8";
        $_dados["email"] = "Usuario_8@mail.com";
        $_dados["senha"] = '';
        $response = $cliente->post("usuarios",["json"=>$_dados]);
        $retorno = $response->getBody()->getContents();
        $this->assertSame(400,$response->getStatusCode(),$retorno);
        $this->assertSame("Dados ausentes",json_decode($retorno)->errors->message);

        $_dados["nome"] = "Usuario_8";
        $_dados["email"] = "";
        $_dados["senha"] = 'MUCu3_+A<~VIQ2%T#Y2z';
        $response = $cliente->post("usuarios",["json"=>$_dados]);
        $retorno = $response->getBody()->getContents();
        $this->assertSame(400,$response->getStatusCode(),$retorno);
        $this->assertSame("Dados ausentes",json_decode($retorno)->errors->message);

        $_dados["nome"] = "";
        $_dados["email"] = "Usuario_8@mail.com";
        $_dados["senha"] = 'MUCu3_+A<~VIQ2%T#Y2z';
        $response = $cliente->post("usuarios",["json"=>$_dados]);
        $retorno = $response->getBody()->getContents();
        $this->assertSame(400,$response->getStatusCode(),$retorno);
        $this->assertSame("Dados ausentes",json_decode($retorno)->errors->message);

        $_dados["nome"] = "Usuario_8";
        $_dados["email"] = "&Usuario_7.com";
        $_dados["senha"] = 'MUCu3_+A<~VIQ2%T#Y2z';
        $response = $cliente->post("usuarios",["json"=>$_dados]);
        $retorno = $response->getBody()->getContents();
        $this->assertSame(400,$response->getStatusCode(),$retorno);
        $this->assertSame("Email Invalido",json_decode($retorno)->errors->message);

        $_dados["nome"] = "Usuario_8";
        $_dados["email"] = "Usuario_7@mail.com";
        $_dados["senha"] = 'MUCu3_+A<~VIQ2%T#Y2z';
        $response = $cliente->post("usuarios",["json"=>$_dados]);
        $retorno = $response->getBody()->getContents();
        $this->assertSame(400,$response->getStatusCode(),$retorno);
        $this->assertSame("Email já existe",json_decode($retorno)->errors->message);

        $_dados["nome"] = "Usuario_8";
        $_dados["email"] = "Usuario_8@mail.com";
        $_dados["senha"] = 'MUCu3_+A<~VIQ2%T#Y2z';
        $_dados["nivel"] = 3;
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6ImU5YmM4NGZmLWY2NmItNDFlOS1hMGZlLWQwYTgyYTAwYTU2NyIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMkBtYWlsLmNvbSJ9.die2vKmI1SD9rcmeHcQqtYYLlyb_1bVFjjMuGyuovCA";
        //token / id = e9bc84ff-f66b-41e9-a0fe-d0a82a00a567 / nivel=1
        $response = $cliente->post("usuarios",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertSame(400,$response->getStatusCode(),$retorno);
        $this->assertSame("Organização não encontrada",json_decode($retorno)->errors->message);
    }

    public function testLoginContaEndpoint(): void
    {
        $cliente = new Client(['base_uri' => getenv("BASE_URI"),'http_errors' => false]);

        $_dados["email"] = "Usuario_6@mail.com";
        $_dados["senha"] = 'z)B;HQDe6iD+ZJ`bbXEM';
        $response = $cliente->post("login",["json"=>$_dados]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Login bem-sucedido",json_decode($retorno)->message);
        $this->assertSame(193,strlen(json_decode($retorno)->data->access_token));
        $token = (new Parser(new JoseEncoder()))->parse(json_decode($retorno)->data->access_token);
        $this->assertSame(36,strlen($token->claims()->get('id')));
        $this->assertSame(1,$token->claims()->get('nivel'));
        $this->assertSame("Usuario_6@mail.com",$token->claims()->get('email'));
        $_valido = (new Validator())->validate($token, new SignedWith(new Sha256(),InMemory::base64Encoded(getenv("CHAVE_TOKEN"))));
        $this->assertTrue($_valido);

        $_dados["email"] = "Usuario_6@mail.com";
        $_dados["senha"] = 'senhaerrada';
        $response = $cliente->post("login",["json"=>$_dados]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(401,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Falha na autenticação. Verifique suas credenciais",json_decode($retorno)->message);

        $_dados["email"] = "Usuario_errado@mail.com";
        $_dados["senha"] = 'MUCu3_+A<~VIQ2%T#Y2z';
        $response = $cliente->post("login",["json"=>$_dados]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(401,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Falha na autenticação. Verifique suas credenciais",json_decode($retorno)->message);

        $_dados["email"] = "&Usuario_7.com";
        $_dados["senha"] = 'MUCu3_+A<~VIQ2%T#Y2z';
        $response = $cliente->post("login",["json"=>$_dados]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(400,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Email invalido",json_decode($retorno)->message);

        $_dados["email"] = "Usuario_6@mail.com";
        $_dados["senha"] = '';
        $response = $cliente->post("login",["json"=>$_dados]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(400,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Dados ausentes",json_decode($retorno)->message);

        $_dados["email"] = "";
        $_dados["senha"] = 'MUCu3_+A<~VIQ2%T#Y2z';
        $response = $cliente->post("login",["json"=>$_dados]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(400,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Dados ausentes",json_decode($retorno)->message);

    }

    public function testAtualizaContaEndpoint(): void
    {
        $cliente = new Client(['base_uri' => getenv("BASE_URI"),'http_errors' => false]);

        $response = $cliente->post("login",["json"=>["email"=>"Usuario_6@mail.com","senha"=>'z)B;HQDe6iD+ZJ`bbXEM']]);
        $_token_user_6 = json_decode($response->getBody()->getContents())->data->access_token;
        $_id_user_6 = (new Parser(new JoseEncoder()))->parse($_token_user_6)->claims()->get('id');

        $response = $cliente->post("login",["json"=>["email"=>"Usuario_7@mail.com","senha"=>'MUCu3_+A<~VIQ2%T#Y2z']]);
        $_token_user_7 = json_decode($response->getBody()->getContents())->data->access_token;
        $_id_user_7 = (new Parser(new JoseEncoder()))->parse($_token_user_7)->claims()->get('id');

        $_dados["id"] = $_id_user_6;
        $_dados["nome"] = "Usuario_6 test";
        $_dados["email"] = "Usuario_6@mail.xyz";
        $_dados["senha"] = "testesenhaupdate";
        $_dados["inativo"] = 1;
        $token = $_token_user_6;
        $response = $cliente->put("usuarios",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Usuario atualizado com sucesso",json_decode($retorno)->message);
        $this->assertSame($_id_user_6,json_decode($retorno)->data->id);
        $this->assertSame("Usuario_6 test",json_decode($retorno)->data->nome);
        $this->assertSame("Usuario_6@mail.xyz",json_decode($retorno)->data->email);
        $response = $cliente->post("login",["json"=>["email"=>"Usuario_6@mail.xyz","senha"=>'testesenhaupdate']]);
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame(NULL,json_decode($retorno)->data->data_mod);
        $this->assertSame(1,json_decode($retorno)->data->nivel);
        $this->assertSame(NULL,json_decode($retorno)->data->organizacao);
        unset($_dados);

        $_dados["id"] = $_id_user_6;
        $_dados["nome"] = "Usuario_6";
        $token = $_token_user_6;
        $response = $cliente->put("usuarios",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Usuario atualizado com sucesso",json_decode($retorno)->message);
        $this->assertSame($_id_user_6,json_decode($retorno)->data->id);
        $this->assertSame("Usuario_6",json_decode($retorno)->data->nome);
        $this->assertSame("Usuario_6@mail.xyz",json_decode($retorno)->data->email);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame(NULL,json_decode($retorno)->data->data_mod);
        $this->assertSame(1,json_decode($retorno)->data->nivel);
        $this->assertSame(1,json_decode($retorno)->data->inativo);
        $this->assertSame(NULL,json_decode($retorno)->data->organizacao);
        unset($_dados);

        $_dados["id"] = $_id_user_6;
        $_dados["email"] = "Usuario_6@mail.com";
        $token = $_token_user_6;
        $response = $cliente->put("usuarios",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Usuario atualizado com sucesso",json_decode($retorno)->message);
        $this->assertSame($_id_user_6,json_decode($retorno)->data->id);
        $this->assertSame("Usuario_6",json_decode($retorno)->data->nome);
        $this->assertSame("Usuario_6@mail.com",json_decode($retorno)->data->email);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame(NULL,json_decode($retorno)->data->data_mod);
        $this->assertSame(1,json_decode($retorno)->data->nivel);
        $this->assertSame(1,json_decode($retorno)->data->inativo);
        $this->assertSame(NULL,json_decode($retorno)->data->organizacao);
        unset($_dados);

        $_dados["id"] = $_id_user_6;
        $_dados["senha"] = "MUCu3_+A<~VIQ2%T#Y2z";
        $token = $_token_user_6;
        $response = $cliente->put("usuarios",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Usuario atualizado com sucesso",json_decode($retorno)->message);
        $this->assertSame($_id_user_6,json_decode($retorno)->data->id);
        $this->assertSame("Usuario_6",json_decode($retorno)->data->nome);
        $this->assertSame("Usuario_6@mail.com",json_decode($retorno)->data->email);
        $response = $cliente->post("login",["json"=>["email"=>"Usuario_6@mail.com","senha"=>'MUCu3_+A<~VIQ2%T#Y2z']]);
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame(NULL,json_decode($retorno)->data->data_mod);
        $this->assertSame(1,json_decode($retorno)->data->nivel);
        $this->assertSame(1,json_decode($retorno)->data->inativo);
        $this->assertSame(NULL,json_decode($retorno)->data->organizacao);
        unset($_dados);

        $_dados["id"] = $_id_user_6;
        $_dados["inativo"] = 0;
        $token = $_token_user_6;
        $response = $cliente->put("usuarios",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Usuario atualizado com sucesso",json_decode($retorno)->message);
        $this->assertSame($_id_user_6,json_decode($retorno)->data->id);
        $this->assertSame("Usuario_6",json_decode($retorno)->data->nome);
        $this->assertSame("Usuario_6@mail.com",json_decode($retorno)->data->email);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame(NULL,json_decode($retorno)->data->data_mod);
        $this->assertSame(1,json_decode($retorno)->data->nivel);
        $this->assertSame(0,json_decode($retorno)->data->inativo);
        $this->assertSame(NULL,json_decode($retorno)->data->organizacao);
        unset($_dados);

        $_dados["id"] = $_id_user_6;
        $token = $_token_user_6;
        $response = $cliente->put("usuarios",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(400,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Dados ausentes",json_decode($retorno)->message);

        $_dados["id"] = "";
        $token = $_token_user_6;
        $response = $cliente->put("usuarios",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(400,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Dados ausentes",json_decode($retorno)->message);

        $_dados["id"] = $_id_user_7;
        $_dados["nome"] = "Usuario_7 test";
        $token = $_token_user_6;
        $response = $cliente->put("usuarios",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(403,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Sem permissão para modificar",json_decode($retorno)->message);

        $_dados["id"] = $_id_user_7;
        $_dados["nome"] = "Usuario_7 test";
        $_dados["nivel"] = 2;
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->put("usuarios",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Usuario atualizado com sucesso",json_decode($retorno)->message);
        $this->assertSame($_id_user_7,json_decode($retorno)->data->id);
        $this->assertSame("Usuario_7 test",json_decode($retorno)->data->nome);
        $this->assertSame("Usuario_7@mail.com",json_decode($retorno)->data->email);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame(NULL,json_decode($retorno)->data->data_mod);
        $this->assertSame(2,json_decode($retorno)->data->nivel);
        $this->assertSame(0,json_decode($retorno)->data->inativo);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",json_decode($retorno)->data->organizacao);
        unset($_dados);

        $response = $cliente->post("login",["json"=>["email"=>"Usuario_7@mail.com","senha"=>'MUCu3_+A<~VIQ2%T#Y2z']]);
        $_token_user_7 = json_decode($response->getBody()->getContents())->data->access_token;

        $_dados["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $_dados["nivel"] = 2;
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->put("usuarios",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(400,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Não pode ser realizado, escale outro usuário primeiro",json_decode($retorno)->message);
        unset($_dados);

        $_dados["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $_dados["inativo"] = 1;
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->put("usuarios",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(400,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Não pode ser realizado, escale outro usuário primeiro",json_decode($retorno)->message);
        unset($_dados);

        $_dados["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $_dados["email"] = "&Usuario_1.com";
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->put("usuarios",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(400,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Email invalido",json_decode($retorno)->message);
        unset($_dados);

        $_dados["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $_dados["email"] = "Usuario_6@mail.com";
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->put("usuarios",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(400,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Email já existe",json_decode($retorno)->message);
        unset($_dados);

        $_dados["id"] = $_id_user_7;
        $_dados["senha"] = "tentativademudarasenha";
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->put("usuarios",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(403,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Sem permissão para modificar",json_decode($retorno)->message);
        unset($_dados);

        $_dados["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $_dados["inativo"] = 1;
        $token = $_token_user_7;
        $response = $cliente->put("usuarios",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(403,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Sem permissão para modificar",json_decode($retorno)->message);
        unset($_dados);

        $_dados["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $_dados["nivel"] = 1;
        $token = $_token_user_7;
        $response = $cliente->put("usuarios",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(403,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Sem permissão para modificar",json_decode($retorno)->message);
        unset($_dados);

        $_dados["id"] = "idnaoexistente";
        $_dados["nivel"] = 1;
        $token = $_token_user_7;
        $response = $cliente->put("usuarios",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(404,$response->getStatusCode(),$retorno);
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Usuario não encontrado",json_decode($retorno)->message);
        unset($_dados);

        $_dados["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $_dados["nivel"] = 2;
        $token = $_token_user_7;
        $response = $cliente->put("usuarios",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Usuario atualizado com sucesso",json_decode($retorno)->message);
        $this->assertSame("302f91e2-57ac-47b4-9ea1-ce524cf77f9d",json_decode($retorno)->data->id);
        $this->assertSame("Usuario_1",json_decode($retorno)->data->nome);
        $this->assertSame("Usuario_1@mail.com",json_decode($retorno)->data->email);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_mod));
        $this->assertSame(2,json_decode($retorno)->data->nivel);
        $this->assertSame(0,json_decode($retorno)->data->inativo);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",json_decode($retorno)->data->organizacao);
    }

    public function testVizualizaContaEndpoint(): void
    {
        $cliente = new Client(['base_uri' => getenv("BASE_URI"),'http_errors' => false]);

        $ID = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $_dados["nome"] = "Usuario_1";
        $_dados["nivel"] = "2";
        $_dados["organizacao"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $_dados["order"] = "nome";
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoyLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.yiA6sYs_gD6OU3PgCNmd9zgZ-RQYsoWUfIwJY_R1Op8";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=2
        $response = $cliente->get("usuarios/$ID",["query"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(1,count(json_decode($retorno)));
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame("302f91e2-57ac-47b4-9ea1-ce524cf77f9d",json_decode($retorno)[0]->id);
        $this->assertSame("Usuario_1",json_decode($retorno)[0]->nome);
        $this->assertSame("Usuario_1@mail.com",json_decode($retorno)[0]->email);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[0]->data_cad));
        $this->assertSame(2,json_decode($retorno)[0]->nivel);
        $this->assertSame(0,json_decode($retorno)[0]->inativo);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",json_decode($retorno)[0]->organizacao);
        unset($ID,$_dados,$token);

        //a corrigir
        $ID = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6ImU5YmM4NGZmLWY2NmItNDFlOS1hMGZlLWQwYTgyYTAwYTU2NyIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMkBtYWlsLmNvbSJ9.die2vKmI1SD9rcmeHcQqtYYLlyb_1bVFjjMuGyuovCA";
        //token / id = e9bc84ff-f66b-41e9-a0fe-d0a82a00a567 / nivel=1
        $response = $cliente->get("usuarios/$ID",["headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(1,count(json_decode($retorno)));
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame("e9bc84ff-f66b-41e9-a0fe-d0a82a00a567",json_decode($retorno)[0]->id);
        $this->assertSame("Usuario_2",json_decode($retorno)[0]->nome);
        $this->assertSame("Usuario_2@mail.com",json_decode($retorno)[0]->email);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[0]->data_cad));
        $this->assertSame(1,json_decode($retorno)[0]->nivel);
        $this->assertSame(0,json_decode($retorno)[0]->inativo);
        $this->assertSame(NULL,json_decode($retorno)[0]->organizacao);
        unset($ID,$_dados,$token);

        $_dados["organizacao"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $_dados["order"] = "nome";
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoyLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.yiA6sYs_gD6OU3PgCNmd9zgZ-RQYsoWUfIwJY_R1Op8";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=2
        $response = $cliente->get("usuarios",["query"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(3,count(json_decode($retorno)));
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame("302f91e2-57ac-47b4-9ea1-ce524cf77f9d",json_decode($retorno)[0]->id);
        $this->assertSame("Usuario_1",json_decode($retorno)[0]->nome);
        $this->assertSame("Usuario_1@mail.com",json_decode($retorno)[0]->email);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[0]->data_cad));
        $this->assertSame(2,json_decode($retorno)[0]->nivel);
        $this->assertSame(0,json_decode($retorno)[0]->inativo);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",json_decode($retorno)[0]->organizacao);

        $this->assertSame(36,strlen(json_decode($retorno)[1]->id));
        $this->assertSame("Usuario_4 test",json_decode($retorno)[1]->nome);
        $this->assertSame("Usuario_4@mail.com",json_decode($retorno)[1]->email);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[1]->data_cad));
        $this->assertSame(3,json_decode($retorno)[1]->nivel);
        $this->assertSame(0,json_decode($retorno)[1]->inativo);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",json_decode($retorno)[1]->organizacao);

        $this->assertSame(36,strlen(json_decode($retorno)[2]->id));
        $this->assertSame("Usuario_7 test",json_decode($retorno)[2]->nome);
        $this->assertSame("Usuario_7@mail.com",json_decode($retorno)[2]->email);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[2]->data_cad));
        $this->assertSame(2,json_decode($retorno)[2]->nivel);
        $this->assertSame(0,json_decode($retorno)[2]->inativo);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",json_decode($retorno)[2]->organizacao);
        unset($ID,$_dados,$token);

        $response = $cliente->post("login",["json"=>["email"=>"Usuario_7@mail.com","senha"=>'MUCu3_+A<~VIQ2%T#Y2z']]);
        $_token_user_7 = json_decode($response->getBody()->getContents())->data->access_token;
        $_id_user_7 = (new Parser(new JoseEncoder()))->parse($_token_user_7)->claims()->get('id');
        $_dados = ["id"=>$_id_user_7,"nivel"=>3];
        $response = $cliente->put("usuarios",["json"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$_token_user_7]]);
        $this->assertSame(200,$response->getStatusCode(),$response->getBody()->getContents());
        unset($_dados);

        $_dados["organizacao"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $_dados["nome"] = "7";
        $_dados["nivel"] = 3;
        $_dados["order"] = "nome";
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoyLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.yiA6sYs_gD6OU3PgCNmd9zgZ-RQYsoWUfIwJY_R1Op8";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=2
        $response = $cliente->get("usuarios",["query"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(1,count(json_decode($retorno)));
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame(36,strlen(json_decode($retorno)[0]->id));
        $this->assertSame("Usuario_7 test",json_decode($retorno)[0]->nome);
        $this->assertSame("Usuario_7@mail.com",json_decode($retorno)[0]->email);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[0]->data_cad));
        $this->assertSame(3,json_decode($retorno)[0]->nivel);
        $this->assertSame(0,json_decode($retorno)[0]->inativo);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",json_decode($retorno)[0]->organizacao);
        unset($ID,$_dados,$token);

        $_dados["organizacao"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $_dados["nome"] = "Usuario_1";
        $_dados["order"] = "nome";
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoyLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.yiA6sYs_gD6OU3PgCNmd9zgZ-RQYsoWUfIwJY_R1Op8";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=2
        $response = $cliente->get("usuarios",["query"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(1,count(json_decode($retorno)));
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame("302f91e2-57ac-47b4-9ea1-ce524cf77f9d",json_decode($retorno)[0]->id);
        $this->assertSame("Usuario_1",json_decode($retorno)[0]->nome);
        $this->assertSame("Usuario_1@mail.com",json_decode($retorno)[0]->email);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[0]->data_cad));
        $this->assertSame(2,json_decode($retorno)[0]->nivel);
        $this->assertSame(0,json_decode($retorno)[0]->inativo);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",json_decode($retorno)[0]->organizacao);
        unset($ID,$_dados,$token);

        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoyLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.yiA6sYs_gD6OU3PgCNmd9zgZ-RQYsoWUfIwJY_R1Op8";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=2
        $response = $cliente->get("usuarios",["headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(400,$response->getStatusCode(),$retorno);
        $this->assertSame("Dados ausentes",json_decode($retorno)->error);
        unset($ID,$_dados,$token);

        $_dados["organizacao"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $_dados["nome"] = "Usuario_1";
        $_dados["order"] = "nome";
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjozLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.jnCJxuWAUCKTg5G5XYmK1axgC-25n6b7MqE2zN1d8uM";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=3
        $response = $cliente->get("usuarios",["query"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(403,$response->getStatusCode(),$retorno);
        $this->assertSame("Usuario sem permissão",json_decode($retorno)->error);
        unset($ID,$_dados,$token);

        $_dados["organizacao"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $_dados["order"] = "nome";
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6ImU5YmM4NGZmLWY2NmItNDFlOS1hMGZlLWQwYTgyYTAwYTU2NyIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.awFUFyGqu5ycbVH6uGQD1m9mP-hdf-O6x6Rfh-EAoxk";
        //token / id = e9bc84ff-f66b-41e9-a0fe-d0a82a00a567 / nivel=1
        $response = $cliente->get("usuarios",["query"=>$_dados,"headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(403,$response->getStatusCode(),$retorno);
        $this->assertSame("Usuario sem permissão",json_decode($retorno)->error);
        unset($ID,$_dados,$token);

        $ID = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->get("usuarios/$ID",["headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(403,$response->getStatusCode(),$retorno);
        $this->assertSame("Usuario sem permissão",json_decode($retorno)->error);
        unset($ID,$_dados,$token);

        $ID = $_id_user_7;
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->get("usuarios/$ID",["headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(1,count(json_decode($retorno)));
        $this->assertSame(200,$response->getStatusCode(),$retorno);
        $this->assertSame(36,strlen(json_decode($retorno)[0]->id));
        $this->assertSame("Usuario_7 test",json_decode($retorno)[0]->nome);
        $this->assertSame("Usuario_7@mail.com",json_decode($retorno)[0]->email);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[0]->data_cad));
        $this->assertSame(3,json_decode($retorno)[0]->nivel);
        $this->assertSame(0,json_decode($retorno)[0]->inativo);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",json_decode($retorno)[0]->organizacao);
        unset($ID,$_dados,$token);

        $ID = "idnãoexistente";
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjMwMmY5MWUyLTU3YWMtNDdiNC05ZWExLWNlNTI0Y2Y3N2Y5ZCIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fMUBtYWlsLmNvbSJ9.KaZq8U6R0A6_Wh0La8IjcooMiMQ2p7PV_oI707J-ACg";
        //token / id = 302f91e2-57ac-47b4-9ea1-ce524cf77f9d / nivel=1
        $response = $cliente->get("usuarios/$ID",["headers"=>["Authorization"=>"Bearer ".$token]]);
        $retorno = $response->getBody()->getContents();
        $this->assertJson($retorno);
        $this->assertSame(404,$response->getStatusCode(),$retorno);
        $this->assertSame("Resultado não encontrado",json_decode($retorno)->error);
        unset($ID,$_dados,$token);
    }
}