<?php declare(strict_types=1);
namespace models;

use PHPUnit\Framework\TestCase;
use SiCATan_api\Models\AtivosBanco;

final class AtivosBancoTest extends TestCase
{
    public function testInstanciaClasse(): void
    {
        $AtivosBanco = new AtivosBanco();

        $this->assertInstanceOf(AtivosBanco::class, $AtivosBanco);
    }

    public function testCriarAtivoBanco(): void
    {
        $AtivosBanco = new AtivosBanco();

        $_dados["id"] = "ec77e882-dcf4-4dfe-8697-ca6c7bbc7b9f";
        $_dados["descricao"] = "Ativo_4";
        $_dados["data_cad"] = date("Y-m-d H:i:s");
        $_dados["categoria"] = "categoria_1";
        $_dados["organizacao"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $_dados["inativo"] = 0;
        $_dados["ativo_ambiente"] = NULL;
        $_dados["usuario_mod"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $retorno = $AtivosBanco->criaAtivo($_dados);
        $this->assertTrue($retorno);

        $_dados["id"] = "093eb7d8-6fea-4683-bc0a-2a0cb3e38432";
        $_dados["descricao"] = "Ativo_5";
        $_dados["data_cad"] = date("Y-m-d H:i:s");
        $_dados["categoria"] = "categoria_5";
        $_dados["organizacao"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $_dados["inativo"] = 0;
        $_dados["ativo_ambiente"] = "ec77e882-dcf4-4dfe-8697-ca6c7bbc7b9f";
        $_dados["usuario_mod"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $retorno = $AtivosBanco->criaAtivo($_dados);
        $this->assertTrue($retorno);

        $_dados["id"] = "";
        $_dados["descricao"] = "Ativo_6";
        $_dados["data_cad"] = date("Y-m-d H:i:s");
        $_dados["categoria"] = "categoria_1";
        $_dados["organizacao"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $_dados["inativo"] = 0;
        $_dados["ativo_ambiente"] = NULL;
        $_dados["usuario_mod"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $retorno = $AtivosBanco->criaAtivo($_dados);
        $this->assertFalse($retorno["status"]);
        $this->assertSame("campo vazio",$retorno["motivo"]);

        $_dados["id"] = "ec77e882-dcf4-4dfe-8697-ca6c7bbc7b9f";
        $_dados["descricao"] = "";
        $_dados["data_cad"] = date("Y-m-d H:i:s");
        $retorno = $AtivosBanco->criaAtivo($_dados);
        $this->assertFalse($retorno["status"]);
        $this->assertSame("campo vazio",$retorno["motivo"]);

        $_dados["descricao"] = "Ativo_6";
        $_dados["data_cad"] = "";
        $_dados["categoria"] = "categoria_1";
        $retorno = $AtivosBanco->criaAtivo($_dados);
        $this->assertFalse($retorno["status"]);
        $this->assertSame("campo vazio",$retorno["motivo"]);

        $_dados["data_cad"] = date("Y-m-d H:i:s");
        $_dados["categoria"] = "";
        $_dados["organizacao"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $retorno = $AtivosBanco->criaAtivo($_dados);
        $this->assertFalse($retorno["status"]);
        $this->assertSame("campo vazio",$retorno["motivo"]);

        $_dados["categoria"] = "categoria_1";
        $_dados["organizacao"] = "";
        $_dados["inativo"] = 0;
        $retorno = $AtivosBanco->criaAtivo($_dados);
        $this->assertFalse($retorno["status"]);
        $this->assertSame("campo vazio",$retorno["motivo"]);

    }

    public function testAtualizarAtivoBanco(): void
    {
        $AtivosBanco = new AtivosBanco();

        $_dados["id"] = "ec77e882-dcf4-4dfe-8697-ca6c7bbc7b9f";
        $_dados["descricao"] = "Ativo 4";
        $_dados["usuario_mod"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $retorno = $AtivosBanco->atualizaAtivo($_dados);
        $this->assertTrue($retorno);
        unset($_dados);

        $_dados["id"] = "ec77e882-dcf4-4dfe-8697-ca6c7bbc7b9f";
        $_dados["categoria"] = "Categoria 4";
        $_dados["usuario_mod"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $retorno = $AtivosBanco->atualizaAtivo($_dados);
        $this->assertTrue($retorno);
        unset($_dados);

        $_dados["id"] = "ec77e882-dcf4-4dfe-8697-ca6c7bbc7b9f";
        $_dados["inativo"] = 1;
        $_dados["usuario_mod"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $retorno = $AtivosBanco->atualizaAtivo($_dados);
        $this->assertTrue($retorno);
        unset($_dados);

        $_dados["id"] = "ec77e882-dcf4-4dfe-8697-ca6c7bbc7b9f";
        $_dados["descricao"] = "Ativo_4";
        $_dados["categoria"] = "Categoria_1";
        $_dados["inativo"] = 0;
        $_dados["usuario_mod"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $retorno = $AtivosBanco->atualizaAtivo($_dados);
        $this->assertTrue($retorno);
        unset($_dados);

        $_dados["id"] = "093eb7d8-6fea-4683-bc0a-2a0cb3e38432";
        $_dados["ativo_ambiente"] = NULL;
        $_dados["usuario_mod"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $retorno = $AtivosBanco->atualizaAtivo($_dados);
        $this->assertTrue($retorno);
        unset($_dados);

        $_dados["id"] = "093eb7d8-6fea-4683-bc0a-2a0cb3e38432";
        $_dados["ativo_ambiente"] = "ec77e882-dcf4-4dfe-8697-ca6c7bbc7b9f";
        $_dados["usuario_mod"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $retorno = $AtivosBanco->atualizaAtivo($_dados);
        $this->assertTrue($retorno);
        unset($_dados);

    }

    public function testBuscarAtivoBanco(): void
    {
        $AtivosBanco = new AtivosBanco();

        $_dados["id"] = "ec77e882-dcf4-4dfe-8697-ca6c7bbc7b9f";

        $retorno = $AtivosBanco->buscarAtivo($_dados);
        $this->assertIsArray($retorno);
        unset($_dados);
        $this->assertCount(1,$retorno);
        $this->assertCount(8,$retorno[0]);
        $this->assertSame("ec77e882-dcf4-4dfe-8697-ca6c7bbc7b9f",$retorno[0]["id"]);
        $this->assertSame("Ativo_4",$retorno[0]["descricao"]);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen($retorno[0]["data_cad"]));
        $this->assertSame("Categoria_1",$retorno[0]["categoria"]);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",$retorno[0]["organizacao"]);
        $this->assertSame(0,$retorno[0]["inativo"]);
        $this->assertSame(NULL,$retorno[0]["ativo_ambiente"]);
        $this->assertSame(1,$retorno[0]["conteudo_qtd"]);

        $_dados["categoria"] = "categoria_1";

        $retorno = $AtivosBanco->buscarAtivo($_dados);
        $this->assertIsArray($retorno);
        unset($_dados);
        $this->assertCount(2,$retorno);
        $this->assertCount(8,$retorno[0]);
        $this->assertSame("61aec667-2716-4672-8765-85a87226d05f",$retorno[0]["id"]);
        $this->assertSame("Ativo_1",$retorno[0]["descricao"]);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen($retorno[0]["data_cad"]));
        $this->assertSame("categoria_1",$retorno[0]["categoria"]);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",$retorno[0]["organizacao"]);
        $this->assertSame(0,$retorno[0]["inativo"]);
        $this->assertSame(NULL,$retorno[0]["ativo_ambiente"]);
        $this->assertSame(1,$retorno[0]["conteudo_qtd"]);
        $this->assertSame("ec77e882-dcf4-4dfe-8697-ca6c7bbc7b9f",$retorno[1]["id"]);
        $this->assertSame("Ativo_4",$retorno[1]["descricao"]);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen($retorno[1]["data_cad"]));
        $this->assertSame("Categoria_1",$retorno[1]["categoria"]);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",$retorno[1]["organizacao"]);
        $this->assertSame(0,$retorno[1]["inativo"]);
        $this->assertSame(NULL,$retorno[1]["ativo_ambiente"]);
        $this->assertSame(1,$retorno[1]["conteudo_qtd"]);

        $_dados["organizacao"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";

        $retorno = $AtivosBanco->buscarAtivo($_dados);
        $this->assertIsArray($retorno);
        unset($_dados);
        $this->assertCount(6,$retorno);
        $this->assertCount(8,$retorno[0]);
        $this->assertSame("61aec667-2716-4672-8765-85a87226d05f",$retorno[0]["id"]);
        $this->assertSame("Ativo_1",$retorno[0]["descricao"]);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen($retorno[0]["data_cad"]));
        $this->assertSame("categoria_1",$retorno[0]["categoria"]);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",$retorno[0]["organizacao"]);
        $this->assertSame(0,$retorno[0]["inativo"]);
        $this->assertSame(NULL,$retorno[0]["ativo_ambiente"]);
        $this->assertSame(1,$retorno[0]["conteudo_qtd"]);
        $this->assertSame("ec77e882-dcf4-4dfe-8697-ca6c7bbc7b9f",$retorno[2]["id"]);
        $this->assertSame("Ativo_4",$retorno[2]["descricao"]);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen($retorno[2]["data_cad"]));
        $this->assertSame("Categoria_1",$retorno[2]["categoria"]);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",$retorno[2]["organizacao"]);
        $this->assertSame(0,$retorno[2]["inativo"]);
        $this->assertSame(NULL,$retorno[2]["ativo_ambiente"]);
        $this->assertSame(1,$retorno[2]["conteudo_qtd"]);

        $_dados["descricao"] = "%_2";

        $retorno = $AtivosBanco->buscarAtivo($_dados);
        $this->assertIsArray($retorno);
        unset($_dados);
        $this->assertCount(1,$retorno);
        $this->assertCount(8,$retorno[0]);
        $this->assertSame("9cbd7405-ef8d-43f3-bc55-bcc78da4613d",$retorno[0]["id"]);
        $this->assertSame("Ativo_2",$retorno[0]["descricao"]);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen($retorno[0]["data_cad"]));
        $this->assertSame("categoria_2",$retorno[0]["categoria"]);
        $this->assertSame("aeab3060-01fe-4809-9a55-01a50da7dacb",$retorno[0]["organizacao"]);
        $this->assertSame(0,$retorno[0]["inativo"]);
        $this->assertSame(NULL,$retorno[0]["ativo_ambiente"]);
        $this->assertSame(0,$retorno[0]["conteudo_qtd"]);

        $_dados["ativo_ambiente"] = "ec77e882-dcf4-4dfe-8697-ca6c7bbc7b9f";

        $retorno = $AtivosBanco->buscarAtivo($_dados);
        $this->assertIsArray($retorno);
        unset($_dados);
        $this->assertCount(1,$retorno);
        $this->assertCount(8,$retorno[0]);
        $this->assertSame("093eb7d8-6fea-4683-bc0a-2a0cb3e38432",$retorno[0]["id"]);
        $this->assertSame("Ativo_5",$retorno[0]["descricao"]);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen($retorno[0]["data_cad"]));
        $this->assertSame("categoria_5",$retorno[0]["categoria"]);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",$retorno[0]["organizacao"]);
        $this->assertSame(0,$retorno[0]["inativo"]);
        $this->assertSame("ec77e882-dcf4-4dfe-8697-ca6c7bbc7b9f",$retorno[0]["ativo_ambiente"]);
        $this->assertSame(0,$retorno[0]["conteudo_qtd"]);

    }
}