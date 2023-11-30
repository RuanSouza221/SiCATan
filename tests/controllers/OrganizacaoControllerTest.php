<?php declare(strict_types=1);
namespace controllers;

use PHPUnit\Framework\TestCase;
use SiCATan_api\Controllers\OrganizacaoController;
use SiCATan_api\Controllers\UsuariosController;
use SiCATan_api\Models\UsuariosBanco;

final class OrganizacaoControllerTest extends TestCase
{
    public function testInstanciaClasse(): void
    {

        $OrganizacaoController = new OrganizacaoController();

        $this->assertInstanceOf(OrganizacaoController::class, $OrganizacaoController);

    }

    public function testPostOrganizacao(): void
    {
        $OrganizacaoController = new OrganizacaoController();
        $retorno = (new UsuariosController())->criarConta(["nome"=>"Usuario_9","email"=>"Usuario_9@mail.com","senha"=>'ra=g~`<z8y]']);
        $_id_user_9 = json_decode($retorno)->data->id;

        $POST["descricao"] = "Organizacao_3";
        $token["id"] = $_id_user_9;
        $token["nivel"] = 1;
        $retorno = $OrganizacaoController->postOrganizacao($POST, $token);
        $this->assertSame(201,http_response_code());
        $this->assertJson($retorno);
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Organização criada",json_decode($retorno)->message);
        $this->assertSame(36,strlen(json_decode($retorno)->data->id));
        $this->assertSame("Organizacao_3",json_decode($retorno)->data->descricao);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame(0,json_decode($retorno)->data->inativo);

        $POST["descricao"] = "Organizacao_4";
        $token["id"] = $_id_user_9;
        $token["nivel"] = 1;
        $retorno = $OrganizacaoController->postOrganizacao($POST, $token);
        $this->assertSame(401,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("já possui uma organização",json_decode($retorno)->message);

        $POST["descricao"] = "Organizacao_4";
        $token["id"] = $_id_user_9;
        $token["nivel"] = 2;
        $retorno = $OrganizacaoController->postOrganizacao($POST, $token);
        $this->assertSame(403,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Sem permissão",json_decode($retorno)->message);

        $POST["descricao"] = "Organizacao_4";
        $token["id"] = $_id_user_9;
        $token["nivel"] = 3;
        $retorno = $OrganizacaoController->postOrganizacao($POST, $token);
        $this->assertSame(403,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Sem permissão",json_decode($retorno)->message);

        $POST["descricao"] = "";
        $token["id"] = $_id_user_9;
        $token["nivel"] = 1;
        $retorno = $OrganizacaoController->postOrganizacao($POST, $token);
        $this->assertSame(401,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Dados ausentes",json_decode($retorno)->message);

    }

    public function testPutOrganizacao(): void
    {
        $OrganizacaoController = new OrganizacaoController();
        $_user_9 = (new UsuariosBanco())->buscarUsuario(["email"=>"Usuario_9@mail.com"])[0];

        $PUT["id"] = $_user_9["organizacao"];
        $PUT["descricao"] = "Organizacao 3";
        $PUT["inativo"] = 1;
        $token["id"] = $_user_9["id"];
        $token["nivel"] = 1;
        $retorno = $OrganizacaoController->putOrganizacao($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(200,http_response_code());
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Organização atualizada com sucesso",json_decode($retorno)->message);
        $this->assertSame($_user_9["organizacao"],json_decode($retorno)->data->id);
        $this->assertSame("Organizacao 3",json_decode($retorno)->data->descricao);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame(1,json_decode($retorno)->data->inativo);
        unset($PUT);

        $PUT["id"] = $_user_9["organizacao"];
        $PUT["descricao"] = "Organizacao_3";
        $token["id"] = $_user_9["id"];
        $token["nivel"] = 1;
        $retorno = $OrganizacaoController->putOrganizacao($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(200,http_response_code());
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Organização atualizada com sucesso",json_decode($retorno)->message);
        $this->assertSame($_user_9["organizacao"],json_decode($retorno)->data->id);
        $this->assertSame("Organizacao_3",json_decode($retorno)->data->descricao);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame(1,json_decode($retorno)->data->inativo);
        unset($PUT);

        $PUT["id"] = $_user_9["organizacao"];
        $PUT["inativo"] = 0;
        $token["id"] = $_user_9["id"];
        $token["nivel"] = 1;
        $retorno = $OrganizacaoController->putOrganizacao($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(200,http_response_code());
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Organização atualizada com sucesso",json_decode($retorno)->message);
        $this->assertSame($_user_9["organizacao"],json_decode($retorno)->data->id);
        $this->assertSame("Organizacao_3",json_decode($retorno)->data->descricao);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame(0,json_decode($retorno)->data->inativo);
        unset($PUT);

        $PUT["id"] = $_user_9["organizacao"];
        $PUT["inativo"] = 1;
        $token["id"] = $_user_9["id"];
        $token["nivel"] = 2;
        $retorno = $OrganizacaoController->putOrganizacao($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(403,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Sem permissão",json_decode($retorno)->message);
        unset($PUT);

        $PUT["id"] = $_user_9["organizacao"];
        $PUT["descricao"] = "sempermissao";
        $token["id"] = $_user_9["id"];
        $token["nivel"] = 2;
        $retorno = $OrganizacaoController->putOrganizacao($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(403,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Sem permissão",json_decode($retorno)->message);
        unset($PUT);

        $PUT["id"] = $_user_9["organizacao"];
        $PUT["inativo"] = 1;
        $token["id"] = $_user_9["id"];
        $token["nivel"] = 3;
        $retorno = $OrganizacaoController->putOrganizacao($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(403,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Sem permissão",json_decode($retorno)->message);
        unset($PUT);

        $PUT["id"] = $_user_9["organizacao"];
        $PUT["descricao"] = "sempermissao";
        $token["id"] = $_user_9["id"];
        $token["nivel"] = 3;
        $retorno = $OrganizacaoController->putOrganizacao($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(403,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Sem permissão",json_decode($retorno)->message);
        unset($PUT);

        $PUT["id"] = $_user_9["organizacao"];
        $PUT["inativo"] = 1;
        $token["id"] = $_user_9["id"];
        $token["nivel"] = 2;
        $retorno = $OrganizacaoController->putOrganizacao($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(403,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Sem permissão",json_decode($retorno)->message);
        unset($PUT);

        $PUT["inativo"] = 1;
        $token["id"] = $_user_9["id"];
        $token["nivel"] = 1;
        $retorno = $OrganizacaoController->putOrganizacao($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(400,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Dados ausentes",json_decode($retorno)->message);
        unset($PUT);

        $PUT["id"] = $_user_9["organizacao"];
        $token["id"] = $_user_9["id"];
        $token["nivel"] = 1;
        $retorno = $OrganizacaoController->putOrganizacao($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(400,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Dados ausentes",json_decode($retorno)->message);
        unset($PUT);

        $PUT["id"] = $_user_9["organizacao"];
        $PUT["inativo"] = 1;
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = 1;
        $retorno = $OrganizacaoController->putOrganizacao($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(403,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Sem permissão",json_decode($retorno)->message);
        unset($PUT);

    }

    public function testGetOrganizacao(): void
    {
        $OrganizacaoController = new OrganizacaoController();
        $_user_9 = (new UsuariosBanco())->buscarUsuario(["email"=>"Usuario_9@mail.com"])[0];

        $ID = $_user_9["organizacao"];
        $token["id"] = $_user_9["id"];
        $token["nivel"] = "1";
        $retorno = $OrganizacaoController->getOrganizacao($token,$ID);
        $this->assertJson($retorno);
        $this->assertSame(1,count(json_decode($retorno)));
        $this->assertSame(200,http_response_code());
        $this->assertSame($_user_9["organizacao"],json_decode($retorno)[0]->id);
        $this->assertSame("Organizacao_3",json_decode($retorno)[0]->descricao);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[0]->data_cad));
        $this->assertSame(0,json_decode($retorno)[0]->inativo);
        unset($ID,$token);

        $ID = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = "1";
        $retorno = $OrganizacaoController->getOrganizacao($token,$ID);
        $this->assertJson($retorno);
        $this->assertSame(1,count(json_decode($retorno)));
        $this->assertSame(200,http_response_code());
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",json_decode($retorno)[0]->id);
        $this->assertSame("Organização_1",json_decode($retorno)[0]->descricao);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[0]->data_cad));
        $this->assertSame(0,json_decode($retorno)[0]->inativo);
        unset($ID,$token);

        $ID = "";
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = "1";
        $retorno = $OrganizacaoController->getOrganizacao($token,$ID);
        $this->assertJson($retorno);
        $this->assertSame(400,http_response_code());
        $this->assertSame("Dados ausentes",json_decode($retorno)->error);
        unset($ID,$token);

        $ID = $_user_9["organizacao"];
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = "1";
        $retorno = $OrganizacaoController->getOrganizacao($token,$ID);
        $this->assertJson($retorno);
        $this->assertSame(403,http_response_code());
        $this->assertSame("Usuario sem permissão",json_decode($retorno)->error);
        unset($ID,$token);

        $ID = "Idnãoexistente";
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = "1";
        $retorno = $OrganizacaoController->getOrganizacao($token,$ID);
        $this->assertJson($retorno);
        $this->assertSame(403,http_response_code());
        $this->assertSame("Usuario sem permissão",json_decode($retorno)->error);
        unset($ID,$token);
    }
}