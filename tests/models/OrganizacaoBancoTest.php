<?php declare(strict_types=1);
namespace models;

use PHPUnit\Framework\TestCase;
use SiCATan_api\Models\OrganizacaoBanco;

final class OrganizacaoBancoTest extends TestCase
{
    public function testInstanciaClasse(): void
    {
        $OrganizacaoBanco = new OrganizacaoBanco();

        $this->assertInstanceOf(OrganizacaoBanco::class, $OrganizacaoBanco);
    }

    public function testCriarOrganizacaoBanco(): void
    {
        $OrganizacaoBanco = new OrganizacaoBanco();

        $_dados["id"] = "0a4935e0-9fbc-4147-8119-3b273cbc8c9f";
        $_dados["descricao"] = "Organizacao_1_1";
        $_dados["data_cad"] = date("Y-m-d H:i:s");
        $_dados["inativo"] = 0;
        $_dados["usuario_mod"] = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $retorno = $OrganizacaoBanco->criaOrganizacao($_dados);
        $this->assertTrue($retorno);

        unset($_dados["usuario_mod"]);
        $retorno = $OrganizacaoBanco->criaOrganizacao($_dados);
        $this->assertFalse($retorno["status"]);
        $this->assertSame("falha ao inserir",$retorno["motivo"]);

        $_dados["id"] = "";
        $_dados["descricao"] = "Organizacao_4";
        $_dados["data_cad"] = date("Y-m-d H:i:s");
        $_dados["inativo"] = 0;
        $_dados["usuario_mod"] = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $retorno = $OrganizacaoBanco->criaOrganizacao($_dados);
        $this->assertFalse($retorno["status"]);
        $this->assertSame("campo vazio",$retorno["motivo"]);

        $_dados["id"] = "32415d9a-ade3-4f82-8e31-8b63f69bd563";
        $_dados["descricao"] = "";
        $_dados["data_cad"] = date("Y-m-d H:i:s");
        $retorno = $OrganizacaoBanco->criaOrganizacao($_dados);
        $this->assertFalse($retorno["status"]);
        $this->assertSame("campo vazio",$retorno["motivo"]);

        $_dados["descricao"] = "Organizacao_4";
        $_dados["data_cad"] = "";
        $_dados["inativo"] = 0;
        $retorno = $OrganizacaoBanco->criaOrganizacao($_dados);
        $this->assertFalse($retorno["status"]);
        $this->assertSame("campo vazio",$retorno["motivo"]);

        $_dados["data_cad"] = date("Y-m-d H:i:s");
        $_dados["inativo"] = "";
        $retorno = $OrganizacaoBanco->criaOrganizacao($_dados);
        $this->assertFalse($retorno["status"]);
        $this->assertSame("campo vazio",$retorno["motivo"]);

    }

    public function testAtualizarOrganizacaoBanco(): void
    {
        $OrganizacaoBanco = new OrganizacaoBanco();

        $_dados["id"] = "0a4935e0-9fbc-4147-8119-3b273cbc8c9f";
        $_dados["descricao"] = "Organizacao_1 test";
        $_dados["usuario_mod"] = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $retorno = $OrganizacaoBanco->atualizaOrganizacao($_dados);
        $this->assertTrue($retorno);
        unset($_dados);

        $_dados["id"] = "0a4935e0-9fbc-4147-8119-3b273cbc8c9f";
        $_dados["inativo"] = 1;
        $_dados["usuario_mod"] = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $retorno = $OrganizacaoBanco->atualizaOrganizacao($_dados);
        $this->assertTrue($retorno);
        unset($_dados);

        $_dados["id"] = "0a4935e0-9fbc-4147-8119-3b273cbc8c9f";
        $_dados["inativo"] = 0;
        $_dados["usuario_mod"] = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $retorno = $OrganizacaoBanco->atualizaOrganizacao($_dados);
        $this->assertTrue($retorno);
        unset($_dados);

        $_dados["id"] = "";
        $_dados["inativo"] = 0;
        $_dados["usuario_mod"] = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $retorno = $OrganizacaoBanco->atualizaOrganizacao($_dados);
        $this->assertFalse($retorno["status"]);
        $this->assertSame("sem id",$retorno["motivo"]);
        unset($_dados);

        $_dados["id"] = "0a4935e0-9fbc-4147-8119-3b273cbc8c9f";
        $_dados["usuario_mod"] = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $retorno = $OrganizacaoBanco->atualizaOrganizacao($_dados);
        $this->assertFalse($retorno["status"]);
        $this->assertSame("sem dados a atualizar",$retorno["motivo"]);

    }

    public function testBuscarOrganizacaoBanco(): void
    {
        $OrganizacaoBanco = new OrganizacaoBanco();

        $_dados["id"] = "0a4935e0-9fbc-4147-8119-3b273cbc8c9f";

        $retorno = $OrganizacaoBanco->buscarOrganizacao($_dados);
        $this->assertIsArray($retorno);
        $this->assertCount(1,$retorno);
        $this->assertCount(4,$retorno[0]);
        $this->assertSame("0a4935e0-9fbc-4147-8119-3b273cbc8c9f",$retorno[0]["id"]);
        $this->assertSame("Organizacao_1 test",$retorno[0]["descricao"]);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen($retorno[0]["data_cad"]));
        $this->assertSame(0,$retorno[0]["inativo"]);

        $_dados["id"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";

        $retorno = $OrganizacaoBanco->buscarOrganizacao($_dados);
        $this->assertIsArray($retorno);
        $this->assertCount(1,$retorno);
        $this->assertCount(4,$retorno[0]);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",$retorno[0]["id"]);
        $this->assertSame("Organização_1",$retorno[0]["descricao"]);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen($retorno[0]["data_cad"]));
        $this->assertSame(0,$retorno[0]["inativo"]);

    }
}