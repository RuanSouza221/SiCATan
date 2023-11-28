<?php declare(strict_types=1);
namespace controllers;

use PHPUnit\Framework\TestCase;
use SiCATan_api\Controllers\UsuariosController;
use SiCATan_api\Models\UsuariosBanco;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Validation\Validator;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Signer\Key\InMemory;

/**
 * @group UsuariosController
 */
final class UsuariosControllerTest extends TestCase
{

    public function testInstanciaClasse(): void
    {

        $UsuariosController = new UsuariosController();

        $this->assertInstanceOf(UsuariosController::class, $UsuariosController);

    }

    public function testCriarConta(): void
    {
        $UsuariosController = new UsuariosController();

        $POST["nome"] = "Usuario_3";
        $POST["email"] = "Usuario_3@mail.com";
        $POST["senha"] = 'MUCu3_+A<~VIQ2%T#Y2z';
        $retorno = $UsuariosController->criarConta($POST);

        $this->assertSame(201,http_response_code());
        $this->assertSame(36,strlen(json_decode($retorno)->data->id));
        $this->assertSame("Usuario_3",json_decode($retorno)->data->nome);
        $this->assertSame("Usuario_3@mail.com",json_decode($retorno)->data->email);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame(NULL,json_decode($retorno)->data->data_mod);
        $this->assertSame(1,json_decode($retorno)->data->nivel);
        $this->assertSame(NULL,json_decode($retorno)->data->organizacao);

        $POST["nome"] = "Usuario_4";
        $POST["email"] = "Usuario_4@mail.com";
        $POST["senha"] = 'MUCu3_+A<~VIQ2%T#Y2z';
        $POST["nivel"] = 3;
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = 1;
        $retorno = $UsuariosController->criarConta($POST, $token);

        $this->assertSame(201,http_response_code());
        $this->assertSame(36,strlen(json_decode($retorno)->data->id));
        $this->assertSame("Usuario_4",json_decode($retorno)->data->nome);
        $this->assertSame("Usuario_4@mail.com",json_decode($retorno)->data->email);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame(NULL,json_decode($retorno)->data->data_mod);
        $this->assertSame(3,json_decode($retorno)->data->nivel);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",json_decode($retorno)->data->organizacao);

        $POST["nome"] = "Usuario_5";
        $POST["email"] = "Usuario_5@mail.com";
        $POST["senha"] = 'MUCu3_+A<~VIQ2%T#Y2z';
        $POST["nivel"] = 3;
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = 3;
        $retorno = $UsuariosController->criarConta($POST, $token);
        $this->assertSame(403,http_response_code());
        $this->assertSame("Usuario sem permissão",json_decode($retorno)->errors->message);

        $POST["nome"] = "Usuario_5";
        $POST["email"] = "Usuario_5@mail.com";
        $POST["senha"] = '';
        $retorno = $UsuariosController->criarConta($POST);
        $this->assertSame(400,http_response_code());
        $this->assertSame("Dados ausentes",json_decode($retorno)->errors->message);

        $POST["nome"] = "Usuario_5";
        $POST["email"] = "";
        $POST["senha"] = 'MUCu3_+A<~VIQ2%T#Y2z';
        $retorno = $UsuariosController->criarConta($POST);
        $this->assertSame(400,http_response_code());
        $this->assertSame("Dados ausentes",json_decode($retorno)->errors->message);

        $POST["nome"] = "";
        $POST["email"] = "Usuario_5@mail.com";
        $POST["senha"] = 'MUCu3_+A<~VIQ2%T#Y2z';
        $retorno = $UsuariosController->criarConta($POST);
        $this->assertSame(400,http_response_code());
        $this->assertSame("Dados ausentes",json_decode($retorno)->errors->message);

        $POST["nome"] = "Usuario_5";
        $POST["email"] = "&Usuario_4.com";
        $POST["senha"] = 'MUCu3_+A<~VIQ2%T#Y2z';
        $retorno = $UsuariosController->criarConta($POST);
        $this->assertSame(400,http_response_code());
        $this->assertSame("Email Invalido",json_decode($retorno)->errors->message);

        $POST["nome"] = "Usuario_5";
        $POST["email"] = "Usuario_4@mail.com";
        $POST["senha"] = 'MUCu3_+A<~VIQ2%T#Y2z';
        $retorno = $UsuariosController->criarConta($POST);
        $this->assertSame(400,http_response_code());
        $this->assertSame("Email já existe",json_decode($retorno)->errors->message);

        $POST["nome"] = "Usuario_5";
        $POST["email"] = "Usuario_5@mail.com";
        $POST["senha"] = 'MUCu3_+A<~VIQ2%T#Y2z';
        $POST["nivel"] = 3;
        $token["id"] = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $token["nivel"] = 1;
        $retorno = $UsuariosController->criarConta($POST,$token);
        $this->assertSame(400,http_response_code());
        $this->assertSame("Organização não encontrada",json_decode($retorno)->errors->message);
    }

    public function testLoginConta(): void
    {
        $UsuariosController = new UsuariosController();

        $POST["email"] = "Usuario_3@mail.com";
        $POST["senha"] = 'MUCu3_+A<~VIQ2%T#Y2z';
        $retorno = $UsuariosController->loginConta($POST);
        $this->assertJson($retorno);
        $this->assertSame(200,http_response_code());
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Login bem-sucedido",json_decode($retorno)->message);
        $this->assertSame(193,strlen(json_decode($retorno)->data->access_token));

        $token = (new Parser(new JoseEncoder()))->parse(json_decode($retorno)->data->access_token);

        $this->assertSame(36,strlen($token->claims()->get('id')));
        $this->assertSame(1,$token->claims()->get('nivel'));
        $this->assertSame("Usuario_3@mail.com",$token->claims()->get('email'));

        $_valido = (new Validator())->validate($token, new SignedWith(new Sha256(),InMemory::base64Encoded(getenv("CHAVE_TOKEN"))));
        $this->assertTrue($_valido);

        $token = (new Parser(new JoseEncoder()))->parse("eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjkzMTI4N2RkLTNmNjctNDdhOC05YWYzLWZmYzEzMTFjZTQyYiIsIm5pdmVsIjoxLCJlbWFpbCI6IlVzdWFyaW9fM0BtYWlsLmNvbSJ9.NQbr67TObLeZ3Ht4Yo6w0ppK2qJOFf1czqK_RIhn3dQ");

        $this->assertSame(36,strlen($token->claims()->get('id')));
        $this->assertSame(1,$token->claims()->get('nivel'));
        $this->assertSame("Usuario_3@mail.com",$token->claims()->get('email'));

        $_valido = (new Validator())->validate($token, new SignedWith(new Sha256(),InMemory::base64Encoded(getenv("CHAVE_TOKEN"))));
        $this->assertFalse($_valido);

        $POST["email"] = "Usuario_3@mail.com";
        $POST["senha"] = 'senhaerrada';
        $retorno = $UsuariosController->loginConta($POST);
        $this->assertJson($retorno);
        $this->assertSame(401,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Falha na autenticação. Verifique suas credenciais",json_decode($retorno)->message);

        $POST["email"] = "Usuario_errado@mail.com";
        $POST["senha"] = 'MUCu3_+A<~VIQ2%T#Y2z';
        $retorno = $UsuariosController->loginConta($POST);
        $this->assertJson($retorno);
        $this->assertSame(401,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Falha na autenticação. Verifique suas credenciais",json_decode($retorno)->message);

        $POST["email"] = "&Usuario_4.com";
        $POST["senha"] = 'MUCu3_+A<~VIQ2%T#Y2z';
        $retorno = $UsuariosController->loginConta($POST);
        $this->assertJson($retorno);
        $this->assertSame(400,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Email invalido",json_decode($retorno)->message);

        $POST["email"] = "Usuario_3@mail.com";
        $POST["senha"] = '';
        $retorno = $UsuariosController->loginConta($POST);
        $this->assertJson($retorno);
        $this->assertSame(400,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Dados ausentes",json_decode($retorno)->message);

        $POST["email"] = "";
        $POST["senha"] = 'MUCu3_+A<~VIQ2%T#Y2z';
        $retorno = $UsuariosController->loginConta($POST);
        $this->assertJson($retorno);
        $this->assertSame(400,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Dados ausentes",json_decode($retorno)->message);

    }

    public function testAtualizaConta(): void
    {
        $UsuariosController = new UsuariosController();
        $UsuariosBanco = new UsuariosBanco();
        $_id_user_3 = $UsuariosBanco->buscarUsuario(["email"=>"Usuario_3@mail.com"])[0]["id"];
        $_id_user_4 = $UsuariosBanco->buscarUsuario(["email"=>"Usuario_4@mail.com"])[0]["id"];

        $PUT["id"] = $_id_user_3;
        $PUT["nome"] = "Usuario_3 test";
        $PUT["email"] = "Usuario_3@mail.xyz";
        $PUT["senha"] = "testesenhaupdate";
        $PUT["inativo"] = 1;
        $token["id"] = $_id_user_3;
        $token["nivel"] = 1;
        $retorno = $UsuariosController->atualizaConta($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(200,http_response_code());
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Usuario atualizado com sucesso",json_decode($retorno)->message);
        $this->assertSame($_id_user_3,json_decode($retorno)->data->id);
        $this->assertSame("Usuario_3 test",json_decode($retorno)->data->nome);
        $this->assertSame("Usuario_3@mail.xyz",json_decode($retorno)->data->email);
        $UsuariosController->loginConta(["email"=>"Usuario_3@mail.xyz","senha"=>"testesenhaupdate"]);
        $this->assertSame(200,http_response_code());
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
//        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_mod));
        $this->assertSame(1,json_decode($retorno)->data->nivel);
        $this->assertSame(NULL,json_decode($retorno)->data->organizacao);
        unset($PUT);

        $PUT["id"] = $_id_user_3;
        $PUT["nome"] = "Usuario_3";
        $token["id"] = $_id_user_3;
        $token["nivel"] = 1;
        $retorno = $UsuariosController->atualizaConta($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(200,http_response_code());
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Usuario atualizado com sucesso",json_decode($retorno)->message);
        $this->assertSame($_id_user_3,json_decode($retorno)->data->id);
        $this->assertSame("Usuario_3",json_decode($retorno)->data->nome);
        $this->assertSame("Usuario_3@mail.xyz",json_decode($retorno)->data->email);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
//        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_mod));
        $this->assertSame(1,json_decode($retorno)->data->nivel);
        $this->assertSame(1,json_decode($retorno)->data->inativo);
        $this->assertSame(NULL,json_decode($retorno)->data->organizacao);
        unset($PUT);

        $PUT["id"] = $_id_user_3;
        $PUT["email"] = "Usuario_3@mail.com";
        $token["id"] = $_id_user_3;
        $token["nivel"] = 1;
        $retorno = $UsuariosController->atualizaConta($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(200,http_response_code());
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Usuario atualizado com sucesso",json_decode($retorno)->message);
        $this->assertSame($_id_user_3,json_decode($retorno)->data->id);
        $this->assertSame("Usuario_3",json_decode($retorno)->data->nome);
        $this->assertSame("Usuario_3@mail.com",json_decode($retorno)->data->email);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
//        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_mod));
        $this->assertSame(1,json_decode($retorno)->data->nivel);
        $this->assertSame(1,json_decode($retorno)->data->inativo);
        $this->assertSame(NULL,json_decode($retorno)->data->organizacao);
        unset($PUT);

        $PUT["id"] = $_id_user_3;
        $PUT["senha"] = "MUCu3_+A<~VIQ2%T#Y2z";
        $token["id"] = $_id_user_3;
        $token["nivel"] = 1;
        $retorno = $UsuariosController->atualizaConta($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(200,http_response_code());
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Usuario atualizado com sucesso",json_decode($retorno)->message);
        $this->assertSame($_id_user_3,json_decode($retorno)->data->id);
        $this->assertSame("Usuario_3",json_decode($retorno)->data->nome);
        $this->assertSame("Usuario_3@mail.com",json_decode($retorno)->data->email);
        $UsuariosController->loginConta(["email"=>"Usuario_3@mail.com","senha"=>"MUCu3_+A<~VIQ2%T#Y2z"]);
        $this->assertSame(200,http_response_code());
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
//        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_mod));
        $this->assertSame(1,json_decode($retorno)->data->nivel);
        $this->assertSame(1,json_decode($retorno)->data->inativo);
        $this->assertSame(NULL,json_decode($retorno)->data->organizacao);
        unset($PUT);

        $PUT["id"] = $_id_user_3;
        $PUT["inativo"] = 0;
        $token["id"] = $_id_user_3;
        $token["nivel"] = 1;
        $retorno = $UsuariosController->atualizaConta($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(200,http_response_code());
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Usuario atualizado com sucesso",json_decode($retorno)->message);
        $this->assertSame($_id_user_3,json_decode($retorno)->data->id);
        $this->assertSame("Usuario_3",json_decode($retorno)->data->nome);
        $this->assertSame("Usuario_3@mail.com",json_decode($retorno)->data->email);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
//        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_mod));
        $this->assertSame(1,json_decode($retorno)->data->nivel);
        $this->assertSame(0,json_decode($retorno)->data->inativo);
        $this->assertSame(NULL,json_decode($retorno)->data->organizacao);
        unset($PUT);

        $PUT["id"] = $_id_user_3;
        $token["id"] = $_id_user_3;
        $token["nivel"] = 1;
        $retorno = $UsuariosController->atualizaConta($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(400,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Dados ausentes",json_decode($retorno)->message);

        $PUT["id"] = "";
        $token["id"] = $_id_user_3;
        $token["nivel"] = 1;
        $retorno = $UsuariosController->atualizaConta($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(400,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Dados ausentes",json_decode($retorno)->message);

        $PUT["id"] = $_id_user_4;
        $PUT["nome"] = "Usuario_4 test";
        $token["id"] = $_id_user_3;
        $token["nivel"] = 1;
        $retorno = $UsuariosController->atualizaConta($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(403,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Sem permissão para modificar",json_decode($retorno)->message);

        $PUT["id"] = $_id_user_4;
        $PUT["nome"] = "Usuario_4 test";
        $PUT["nivel"] = 2;
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = 1;
        $retorno = $UsuariosController->atualizaConta($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(200,http_response_code());
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Usuario atualizado com sucesso",json_decode($retorno)->message);
        $this->assertSame($_id_user_4,json_decode($retorno)->data->id);
        $this->assertSame("Usuario_4 test",json_decode($retorno)->data->nome);
        $this->assertSame("Usuario_4@mail.com",json_decode($retorno)->data->email);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
//        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_mod));
        $this->assertSame(2,json_decode($retorno)->data->nivel);
        $this->assertSame(0,json_decode($retorno)->data->inativo);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",json_decode($retorno)->data->organizacao);
        unset($PUT);

        $PUT["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $PUT["nivel"] = 2;
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = 1;
        $retorno = $UsuariosController->atualizaConta($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(400,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Não pode ser realizado, escale outro usuário primeiro",json_decode($retorno)->message);
        unset($PUT);

        $PUT["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $PUT["inativo"] = 1;
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = 1;
        $retorno = $UsuariosController->atualizaConta($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(400,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Não pode ser realizado, escale outro usuário primeiro",json_decode($retorno)->message);
        unset($PUT);

        $PUT["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $PUT["email"] = "&Usuario_1.com";
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = 1;
        $retorno = $UsuariosController->atualizaConta($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(400,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Email invalido",json_decode($retorno)->message);
        unset($PUT);

        $PUT["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $PUT["email"] = "Usuario_3@mail.com";
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = 1;
        $retorno = $UsuariosController->atualizaConta($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(400,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Email já existe",json_decode($retorno)->message);
        unset($PUT);

        $PUT["id"] = $_id_user_4;
        $PUT["senha"] = "tentativademudarasenha";
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = 1;
        $retorno = $UsuariosController->atualizaConta($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(403,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Sem permissão para modificar",json_decode($retorno)->message);
        unset($PUT);

        $PUT["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $PUT["inativo"] = 1;
        $token["id"] = $_id_user_4;
        $token["nivel"] = 2;
        $retorno = $UsuariosController->atualizaConta($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(403,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Sem permissão para modificar",json_decode($retorno)->message);
        unset($PUT);

        $PUT["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $PUT["nivel"] = 1;
        $token["id"] = $_id_user_4;
        $token["nivel"] = 2;
        $retorno = $UsuariosController->atualizaConta($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(403,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Sem permissão para modificar",json_decode($retorno)->message);
        unset($PUT);

        $PUT["id"] = "idnaoexistente";
        $PUT["nivel"] = 1;
        $token["id"] = $_id_user_4;
        $token["nivel"] = 2;
        $retorno = $UsuariosController->atualizaConta($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(404,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Usuario não encontrado",json_decode($retorno)->message);
        unset($PUT);

        $PUT["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $PUT["nivel"] = 2;
        $token["id"] = $_id_user_4;
        $token["nivel"] = 2;
        $retorno = $UsuariosController->atualizaConta($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(200,http_response_code());
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Usuario atualizado com sucesso",json_decode($retorno)->message);
        $this->assertSame("302f91e2-57ac-47b4-9ea1-ce524cf77f9d",json_decode($retorno)->data->id);
        $this->assertSame("Usuario_1",json_decode($retorno)->data->nome);
        $this->assertSame("Usuario_1@mail.com",json_decode($retorno)->data->email);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
//        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_mod));
        $this->assertSame(2,json_decode($retorno)->data->nivel);
        $this->assertSame(0,json_decode($retorno)->data->inativo);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",json_decode($retorno)->data->organizacao);
    }

    public function testVizualizaConta(): void
    {
        $UsuariosController = new UsuariosController();

        $ID = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $GET["nome"] = "Usuario_1";
        $GET["nivel"] = "2";
        $GET["organizacao"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $GET["order"] = "nome";
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = "2";
        $retorno = $UsuariosController->vizualizaConta($token,$ID,$GET);
        $this->assertJson($retorno);
        $this->assertSame(1,count(json_decode($retorno)));
        $this->assertSame(200,http_response_code());
        $this->assertSame("302f91e2-57ac-47b4-9ea1-ce524cf77f9d",json_decode($retorno)[0]->id);
        $this->assertSame("Usuario_1",json_decode($retorno)[0]->nome);
        $this->assertSame("Usuario_1@mail.com",json_decode($retorno)[0]->email);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[0]->data_cad));
        $this->assertSame(2,json_decode($retorno)[0]->nivel);
        $this->assertSame(0,json_decode($retorno)[0]->inativo);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",json_decode($retorno)[0]->organizacao);
        unset($ID,$GET,$token);

        $ID = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $token["id"] = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $token["nivel"] = "1";
        $retorno = $UsuariosController->vizualizaConta($token,$ID);
        $this->assertJson($retorno);
        $this->assertSame(1,count(json_decode($retorno)));
        $this->assertSame(200,http_response_code());
        $this->assertSame("e9bc84ff-f66b-41e9-a0fe-d0a82a00a567",json_decode($retorno)[0]->id);
        $this->assertSame("Usuario_2",json_decode($retorno)[0]->nome);
        $this->assertSame("Usuario_2@mail.com",json_decode($retorno)[0]->email);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[0]->data_cad));
        $this->assertSame(1,json_decode($retorno)[0]->nivel);
        $this->assertSame(0,json_decode($retorno)[0]->inativo);
        $this->assertSame(NULL,json_decode($retorno)[0]->organizacao);
        unset($ID,$GET,$token);

        $GET["organizacao"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $GET["order"] = "nome";
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = "2";
        $retorno = $UsuariosController->vizualizaConta($token,NULL,$GET);
        $this->assertJson($retorno);
        $this->assertSame(2,count(json_decode($retorno)));
        $this->assertSame(200,http_response_code());
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
        $this->assertSame(2,json_decode($retorno)[1]->nivel);
        $this->assertSame(0,json_decode($retorno)[1]->inativo);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",json_decode($retorno)[1]->organizacao);
        unset($ID,$GET,$token);

        $_id_user_4 = json_decode($retorno)[1]->id;
        $UsuariosController->atualizaConta(["id"=>json_decode($retorno)[1]->id,"nivel"=>3],["id"=>$_id_user_4,"nivel"=>2]);
        $this->assertSame(200,http_response_code());

        $GET["organizacao"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $GET["nivel"] = 3;
        $GET["order"] = "nome";
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = "2";
        $retorno = $UsuariosController->vizualizaConta($token,NULL,$GET);
        $this->assertJson($retorno);
        $this->assertSame(1,count(json_decode($retorno)));
        $this->assertSame(200,http_response_code());
        $this->assertSame(36,strlen(json_decode($retorno)[0]->id));
        $this->assertSame("Usuario_4 test",json_decode($retorno)[0]->nome);
        $this->assertSame("Usuario_4@mail.com",json_decode($retorno)[0]->email);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[0]->data_cad));
        $this->assertSame(3,json_decode($retorno)[0]->nivel);
        $this->assertSame(0,json_decode($retorno)[0]->inativo);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",json_decode($retorno)[0]->organizacao);
        unset($ID,$GET,$token);

        $GET["organizacao"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $GET["nome"] = "Usuario_1";
        $GET["order"] = "nome";
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = "2";
        $retorno = $UsuariosController->vizualizaConta($token,NULL,$GET);
        $this->assertJson($retorno);
        $this->assertSame(1,count(json_decode($retorno)));
        $this->assertSame(200,http_response_code());
        $this->assertSame("302f91e2-57ac-47b4-9ea1-ce524cf77f9d",json_decode($retorno)[0]->id);
        $this->assertSame("Usuario_1",json_decode($retorno)[0]->nome);
        $this->assertSame("Usuario_1@mail.com",json_decode($retorno)[0]->email);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[0]->data_cad));
        $this->assertSame(2,json_decode($retorno)[0]->nivel);
        $this->assertSame(0,json_decode($retorno)[0]->inativo);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",json_decode($retorno)[0]->organizacao);
        unset($ID,$GET,$token);

        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = "2";
        $retorno = $UsuariosController->vizualizaConta($token);
        $this->assertJson($retorno);
        $this->assertSame(400,http_response_code());
        $this->assertSame("Dados ausentes",json_decode($retorno)->error);
        unset($ID,$GET,$token);

        $GET["organizacao"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $GET["nome"] = "Usuario_1";
        $GET["order"] = "nome";
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = "3";
        $retorno = $UsuariosController->vizualizaConta($token,NULL,$GET);
        $this->assertJson($retorno);
        $this->assertSame(403,http_response_code());
        $this->assertSame("Usuario sem permissão",json_decode($retorno)->error);
        unset($ID,$GET,$token);

        $GET["organizacao"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $GET["order"] = "nome";
        $token["id"] = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $token["nivel"] = "1";
        $retorno = $UsuariosController->vizualizaConta($token,NULL,$GET);
        $this->assertJson($retorno);
        $this->assertSame(403,http_response_code());
        $this->assertSame("Usuario sem permissão",json_decode($retorno)->error);
        unset($ID,$GET,$token);

        $ID = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = "1";
        $retorno = $UsuariosController->vizualizaConta($token,$ID);
        $this->assertJson($retorno);
        $this->assertSame(403,http_response_code());
        $this->assertSame("Usuario sem permissão",json_decode($retorno)->error);
        unset($ID,$GET,$token);

        $ID = $_id_user_4;
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = "1";
        $retorno = $UsuariosController->vizualizaConta($token,$ID);
        $this->assertJson($retorno);
        $this->assertSame(1,count(json_decode($retorno)));
        $this->assertSame(200,http_response_code());
        $this->assertSame(36,strlen(json_decode($retorno)[0]->id));
        $this->assertSame("Usuario_4 test",json_decode($retorno)[0]->nome);
        $this->assertSame("Usuario_4@mail.com",json_decode($retorno)[0]->email);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[0]->data_cad));
        $this->assertSame(3,json_decode($retorno)[0]->nivel);
        $this->assertSame(0,json_decode($retorno)[0]->inativo);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",json_decode($retorno)[0]->organizacao);
        unset($ID,$GET,$token);

        $ID = "idnãoexistente";
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = "1";
        $retorno = $UsuariosController->vizualizaConta($token,$ID);
        $this->assertJson($retorno);
        $this->assertSame(404,http_response_code());
        $this->assertSame("Resultado não encontrado",json_decode($retorno)->error);
        unset($ID,$GET,$token);
    }
}