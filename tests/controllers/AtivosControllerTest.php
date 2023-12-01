<?php declare(strict_types=1);
namespace controllers;

use PHPUnit\Framework\TestCase;
use SiCATan_api\Controllers\AtivosController;
use SiCATan_api\Models\AtivosBanco;

final class AtivosControllerTest extends TestCase
{
    public function testInstanciaClasse(): void
    {

        $AtivosController = new AtivosController();

        $this->assertInstanceOf(AtivosController::class, $AtivosController);

    }

    public function testPostAtivo(): void
    {
        $AtivosController = new AtivosController();

        $POST["descricao"] = "Ativo_7";
        $POST["categoria"] = "Categoria_7";
        $POST["ativo_ambiente"] = NULL;
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = 1;
        $retorno = $AtivosController->postAtivo($POST, $token);
        $this->assertSame(201,http_response_code());
        $this->assertJson($retorno);
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Ativo criado",json_decode($retorno)->message);
        $this->assertSame(36,strlen(json_decode($retorno)->data->id));
        $this->assertSame("Ativo_7",json_decode($retorno)->data->descricao);
        $this->assertSame("Categoria_7",json_decode($retorno)->data->categoria);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame(0,json_decode($retorno)->data->inativo);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",json_decode($retorno)->data->organizacao);
        $this->assertSame(NULL,json_decode($retorno)->data->ativo_ambiente);

        $POST["descricao"] = "Ativo_8";
        $POST["categoria"] = "Categoria_8";
        $POST["ativo_ambiente"] = json_decode($retorno)->data->id;
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = 1;
        $retorno = $AtivosController->postAtivo($POST, $token);
        $this->assertSame(201,http_response_code());
        $this->assertJson($retorno);
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Ativo criado",json_decode($retorno)->message);
        $this->assertSame(36,strlen(json_decode($retorno)->data->id));
        $this->assertSame("Ativo_8",json_decode($retorno)->data->descricao);
        $this->assertSame("Categoria_8",json_decode($retorno)->data->categoria);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame(0,json_decode($retorno)->data->inativo);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",json_decode($retorno)->data->organizacao);
        $this->assertSame($POST["ativo_ambiente"],json_decode($retorno)->data->ativo_ambiente);

        $POST["descricao"] = "";
        $POST["categoria"] = "Categoria_9";
        $POST["ativo_ambiente"] = NULL;
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = 1;
        $retorno = $AtivosController->postAtivo($POST, $token);
        $this->assertSame(400,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Dados ausentes",json_decode($retorno)->message);

        $POST["descricao"] = "Ativo_9";
        $POST["categoria"] = "";
        $POST["ativo_ambiente"] = NULL;
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = 1;
        $retorno = $AtivosController->postAtivo($POST, $token);
        $this->assertSame(400,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Dados ausentes",json_decode($retorno)->message);

        $POST["descricao"] = "Ativo_9";
        $POST["categoria"] = "Categoria_9";
        $POST["ativo_ambiente"] = NULL;
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = 3;
        $retorno = $AtivosController->postAtivo($POST, $token);
        $this->assertSame(403,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Usuario sem permissão",json_decode($retorno)->message);

        $POST["descricao"] = "Ativo_9";
        $POST["categoria"] = "Categoria_9";
        $POST["ativo_ambiente"] = NULL;
        $token["id"] = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $token["nivel"] = 1;
        $retorno = $AtivosController->postAtivo($POST, $token);
        $this->assertSame(403,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Usuario sem permissão",json_decode($retorno)->message);
    }

    public function testPutAtivo(): void
    {
        $AtivosController = new AtivosController();
        $_ativo_7 = (new AtivosBanco())->buscarAtivo(["descricao"=>"Ativo_7"])[0];
        $_ativo_8 = (new AtivosBanco())->buscarAtivo(["descricao"=>"Ativo_8"])[0];

        $PUT["id"] = $_ativo_8["id"];
        $PUT["descricao"] = "Ativo 8";
        $PUT["categoria"] = "Categoria 8";
        $PUT["inativo"] = 1;
        $PUT["ativo_ambiente"] = NULL;
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = 1;
        $retorno = $AtivosController->putAtivo($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(200,http_response_code());
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Ativo atualizado com sucesso",json_decode($retorno)->message);
        $this->assertSame($_ativo_8["id"],json_decode($retorno)->data->id);
        $this->assertSame("Ativo 8",json_decode($retorno)->data->descricao);
        $this->assertSame("Categoria 8",json_decode($retorno)->data->categoria);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame($_ativo_8["organizacao"],json_decode($retorno)->data->organizacao);
        $this->assertSame(1,json_decode($retorno)->data->inativo);
        $this->assertSame(NULL,json_decode($retorno)->data->ativo_ambiente);
        unset($PUT);

        $PUT["id"] = $_ativo_7["id"];
        $PUT["descricao"] = "Ativo 7";
        $PUT["categoria"] = "Categoria 7";
        $PUT["inativo"] = 1;
        $PUT["ativo_ambiente"] = $_ativo_8["id"];
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = 1;
        $retorno = $AtivosController->putAtivo($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(200,http_response_code());
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Ativo atualizado com sucesso",json_decode($retorno)->message);
        $this->assertSame($_ativo_7["id"],json_decode($retorno)->data->id);
        $this->assertSame("Ativo 7",json_decode($retorno)->data->descricao);
        $this->assertSame("Categoria 7",json_decode($retorno)->data->categoria);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame($_ativo_7["organizacao"],json_decode($retorno)->data->organizacao);
        $this->assertSame(1,json_decode($retorno)->data->inativo);
        $this->assertSame($_ativo_8["id"],json_decode($retorno)->data->ativo_ambiente);
        unset($PUT);

        $PUT["id"] = $_ativo_8["id"];
        $PUT["descricao"] = "Ativo_8";
        $PUT["categoria"] = "Categoria_8";
        $PUT["inativo"] = 0;
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = 1;
        $retorno = $AtivosController->putAtivo($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(200,http_response_code());
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Ativo atualizado com sucesso",json_decode($retorno)->message);
        $this->assertSame($_ativo_8["id"],json_decode($retorno)->data->id);
        $this->assertSame("Ativo_8",json_decode($retorno)->data->descricao);
        $this->assertSame("Categoria_8",json_decode($retorno)->data->categoria);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame($_ativo_8["organizacao"],json_decode($retorno)->data->organizacao);
        $this->assertSame(0,json_decode($retorno)->data->inativo);
        $this->assertSame(NULL,json_decode($retorno)->data->ativo_ambiente);
        unset($PUT);

        $PUT["id"] = $_ativo_7["id"];
        $PUT["descricao"] = "Ativo_7";
        $PUT["categoria"] = "Categoria_7";
        $PUT["inativo"] = 0;
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = 1;
        $retorno = $AtivosController->putAtivo($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(200,http_response_code());
        $this->assertSame("success",json_decode($retorno)->status);
        $this->assertSame("Ativo atualizado com sucesso",json_decode($retorno)->message);
        $this->assertSame($_ativo_7["id"],json_decode($retorno)->data->id);
        $this->assertSame("Ativo_7",json_decode($retorno)->data->descricao);
        $this->assertSame("Categoria_7",json_decode($retorno)->data->categoria);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)->data->data_cad));
        $this->assertSame($_ativo_7["organizacao"],json_decode($retorno)->data->organizacao);
        $this->assertSame(0,json_decode($retorno)->data->inativo);
        $this->assertSame($_ativo_8["id"],json_decode($retorno)->data->ativo_ambiente);
        unset($PUT);

        $PUT["id"] = $_ativo_7["id"];
        $PUT["descricao"] = "Ativo 7";
        $PUT["categoria"] = "Categoria 7";
        $PUT["inativo"] = "1";
        $PUT["ativo_ambiente"] = $_ativo_8["id"];
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = 3;
        $retorno = $AtivosController->putAtivo($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(403,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Sem permissão",json_decode($retorno)->message);
        unset($PUT);

        $PUT["id"] = $_ativo_7["id"];
        $PUT["descricao"] = "Ativo 7";
        $PUT["categoria"] = "Categoria 7";
        $PUT["inativo"] = "1";
        $PUT["ativo_ambiente"] = $_ativo_8["id"];
        $token["id"] = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $token["nivel"] = 1;
        $retorno = $AtivosController->putAtivo($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(403,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Sem permissão",json_decode($retorno)->message);
        unset($PUT);

        $PUT["id"] = $_ativo_7["id"];
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = 3;
        $retorno = $AtivosController->putAtivo($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(400,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Dados ausentes",json_decode($retorno)->message);
        unset($PUT);

        $PUT["descricao"] = "Ativo 7";
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = 3;
        $retorno = $AtivosController->putAtivo($PUT,$token);
        $this->assertJson($retorno);
        $this->assertSame(400,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Dados ausentes",json_decode($retorno)->message);
        unset($PUT);

    }

    public function testGetAtivo(): void
    {
        $AtivosController = new AtivosController();
        $_ativo_7 = (new AtivosBanco())->buscarAtivo(["descricao"=>"Ativo_7"])[0];
        $_ativo_8 = (new AtivosBanco())->buscarAtivo(["descricao"=>"Ativo_8"])[0];

        //id
        $ID = $_ativo_8["id"];
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = 1;
        $retorno = $AtivosController->getAtivo($token,$ID);
        $this->assertJson($retorno);
        $this->assertSame(1,count(json_decode($retorno)));
        $this->assertSame(200,http_response_code());
        $this->assertSame($_ativo_8["id"],json_decode($retorno)[0]->id);
        $this->assertSame("Ativo_8",json_decode($retorno)[0]->descricao);
        $this->assertSame("Categoria_8",json_decode($retorno)[0]->categoria);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[0]->data_cad));
        $this->assertSame($_ativo_8["organizacao"],json_decode($retorno)[0]->organizacao);
        $this->assertSame(0,json_decode($retorno)[0]->inativo);
        $this->assertSame(NULL,json_decode($retorno)[0]->ativo_ambiente);
        $this->assertSame(1,json_decode($retorno)[0]->conteudo_qtd);
        unset($ID,$token,$GET);

        //descricao
        $GET["descricao"] = "_8";
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = 1;
        $retorno = $AtivosController->getAtivo($token,NULL,$GET);
        $this->assertJson($retorno);
        $this->assertSame(1,count(json_decode($retorno)));
        $this->assertSame(200,http_response_code());
        $this->assertSame($_ativo_8["id"],json_decode($retorno)[0]->id);
        $this->assertSame("Ativo_8",json_decode($retorno)[0]->descricao);
        $this->assertSame("Categoria_8",json_decode($retorno)[0]->categoria);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[0]->data_cad));
        $this->assertSame($_ativo_8["organizacao"],json_decode($retorno)[0]->organizacao);
        $this->assertSame(0,json_decode($retorno)[0]->inativo);
        $this->assertSame(NULL,json_decode($retorno)[0]->ativo_ambiente);
        $this->assertSame(1,json_decode($retorno)[0]->conteudo_qtd);
        unset($token,$GET);

        //categoria
        $GET["categoria"] = "Categoria_8";
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = 1;
        $retorno = $AtivosController->getAtivo($token,NULL,$GET);
        $this->assertJson($retorno);
        $this->assertSame(1,count(json_decode($retorno)));
        $this->assertSame(200,http_response_code());
        $this->assertSame($_ativo_8["id"],json_decode($retorno)[0]->id);
        $this->assertSame("Ativo_8",json_decode($retorno)[0]->descricao);
        $this->assertSame("Categoria_8",json_decode($retorno)[0]->categoria);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[0]->data_cad));
        $this->assertSame($_ativo_8["organizacao"],json_decode($retorno)[0]->organizacao);
        $this->assertSame(0,json_decode($retorno)[0]->inativo);
        $this->assertSame(NULL,json_decode($retorno)[0]->ativo_ambiente);
        $this->assertSame(1,json_decode($retorno)[0]->conteudo_qtd);
        unset($token,$GET);

        //organizacao
        (new AtivosBanco())->updateSimples("Usuarios",["organizacao"=>"aeab3060-01fe-4809-9a55-01a50da7dacb"],["nome"=>"Usuario 1 test"]);
        $GET["organizacao"] = "aeab3060-01fe-4809-9a55-01a50da7dacb";
        $token["id"] = "c2aeea10-6cae-435c-bb6d-ae302cb6cb53";
        $token["nivel"] = 1;
        $retorno = $AtivosController->getAtivo($token,NULL,$GET);
        $this->assertJson($retorno);
        $this->assertSame(1,count(json_decode($retorno)));
        $this->assertSame(200,http_response_code());
        $this->assertSame("9cbd7405-ef8d-43f3-bc55-bcc78da4613d",json_decode($retorno)[0]->id);
        $this->assertSame("Ativo_2",json_decode($retorno)[0]->descricao);
        $this->assertSame("categoria_2",json_decode($retorno)[0]->categoria);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[0]->data_cad));
        $this->assertSame("aeab3060-01fe-4809-9a55-01a50da7dacb",json_decode($retorno)[0]->organizacao);
        $this->assertSame(0,json_decode($retorno)[0]->inativo);
        $this->assertSame(NULL,json_decode($retorno)[0]->ativo_ambiente);
        $this->assertSame(0,json_decode($retorno)[0]->conteudo_qtd);
        unset($token,$GET);

        //organizacao
        $GET["organizacao"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = 1;
        $retorno = $AtivosController->getAtivo($token,NULL,$GET);
        $this->assertJson($retorno);
        $this->assertSame(2,count(json_decode($retorno)));
        $this->assertSame(200,http_response_code());
        $this->assertSame("61aec667-2716-4672-8765-85a87226d05f",json_decode($retorno)[0]->id);
        $this->assertSame("Ativo_1",json_decode($retorno)[0]->descricao);
        $this->assertSame("categoria_1",json_decode($retorno)[0]->categoria);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[0]->data_cad));
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",json_decode($retorno)[0]->organizacao);
        $this->assertSame(0,json_decode($retorno)[0]->inativo);
        $this->assertSame(NULL,json_decode($retorno)[0]->ativo_ambiente);
        $this->assertSame(1,json_decode($retorno)[0]->conteudo_qtd);

        $this->assertSame($_ativo_8["id"],json_decode($retorno)[1]->id);
        $this->assertSame("Ativo_8",json_decode($retorno)[1]->descricao);
        $this->assertSame("Categoria_8",json_decode($retorno)[1]->categoria);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[1]->data_cad));
        $this->assertSame($_ativo_8["organizacao"],json_decode($retorno)[1]->organizacao);
        $this->assertSame(0,json_decode($retorno)[1]->inativo);
        $this->assertSame(NULL,json_decode($retorno)[1]->ativo_ambiente);
        $this->assertSame(1,json_decode($retorno)[1]->conteudo_qtd);
        unset($token,$GET);

        //ativo_ambiente
        $GET["ativo_ambiente"] = $_ativo_8["id"];
        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = 1;
        $retorno = $AtivosController->getAtivo($token,NULL,$GET);
        $this->assertJson($retorno);
        $this->assertSame(1,count(json_decode($retorno)));
        $this->assertSame(200,http_response_code());
        $this->assertSame($_ativo_7["id"],json_decode($retorno)[0]->id);
        $this->assertSame("Ativo_7",json_decode($retorno)[0]->descricao);
        $this->assertSame("Categoria_7",json_decode($retorno)[0]->categoria);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen(json_decode($retorno)[0]->data_cad));
        $this->assertSame($_ativo_7["organizacao"],json_decode($retorno)[0]->organizacao);
        $this->assertSame(0,json_decode($retorno)[0]->inativo);
        $this->assertSame($_ativo_8["id"],json_decode($retorno)[0]->ativo_ambiente);
        $this->assertSame(0,json_decode($retorno)[0]->conteudo_qtd);
        unset($token,$GET);

        $token["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $token["nivel"] = 1;
        $retorno = $AtivosController->getAtivo($token);
        $this->assertJson($retorno);
        $this->assertSame(400,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Dados ausentes",json_decode($retorno)->message);
        unset($token);

        $GET["categoria"] = "Categoria_8";
        $token["id"] = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $token["nivel"] = 1;
        $retorno = $AtivosController->getAtivo($token,NULL,$GET);
        $this->assertJson($retorno);
        $this->assertSame(404,http_response_code());
        $this->assertSame("error",json_decode($retorno)->status);
        $this->assertSame("Resultado não encontrado",json_decode($retorno)->message);
        unset($token,$GET);

    }
}