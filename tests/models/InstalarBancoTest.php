<?php declare(strict_types=1);
namespace models;

use PHPUnit\Framework\TestCase;
use SiCATan_api\Models\InstalarBanco;

final class InstalarBancoTest extends TestCase
{
    public function testInstanciaClasse(): void
    {
        $InstalarBanco = new InstalarBanco();

        $this->assertInstanceOf(InstalarBanco::class, $InstalarBanco);
    }

    public function testCriarNivelAcesso(): void
    {
        $InstalarBanco = new InstalarBanco();

        $retorno = $InstalarBanco->criarNivelAcesso();

        $this->assertNotFalse($retorno);

        $this->assertIsArray($retorno);

        $this->assertSame(1,$retorno[0]);
        $this->assertSame(1,$retorno[1]);
        $this->assertSame(1,$retorno[2]);

        $retorno = $InstalarBanco->selectSimples("Nivel_Acesso");

        $this->assertIsArray($retorno);
        $this->assertSame(3,count($retorno));
        $this->assertSame(1,$retorno[0]["id"]);
        $this->assertSame(2,$retorno[1]["id"]);
        $this->assertSame(3,$retorno[2]["id"]);
        $this->assertSame("Dono",$retorno[0]["descricao"]);
        $this->assertSame("Administrador",$retorno[1]["descricao"]);
        $this->assertSame("Normal",$retorno[2]["descricao"]);
    }

    public function testCriarOrganizacao(): void
    {
        $InstalarBanco = new InstalarBanco();

        $retorno = $InstalarBanco->criarOrganizacao();

        $this->assertTrue($retorno);

        $insert["data_cad"] = date("Y-m-d H:i:s");
        $insert["inativo"] = 0;

        $insert["id"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $insert["descricao"] = "Organização_1";
        $retorno = $InstalarBanco->insertSimples("Organizacao", $insert);
        $this->assertSame(1,$retorno);

        $insert["id"] = "aeab3060-01fe-4809-9a55-01a50da7dacb";
        $insert["descricao"] = "Organização_2";
        $retorno = $InstalarBanco->insertSimples("Organizacao", $insert);
        $this->assertSame(1,$retorno);

        $retorno = $InstalarBanco->selectSimples("Organizacao");

        $this->assertIsArray($retorno);
        $this->assertCount(2,$retorno);
        $this->assertCount(4,$retorno[0]);

        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",$retorno[0]["id"]);
        $this->assertSame("Organização_1",$retorno[0]["descricao"]);
        $this->assertSame($insert["data_cad"],$retorno[0]["data_cad"]);
        $this->assertSame(0,$retorno[0]["inativo"]);

        $this->assertSame("aeab3060-01fe-4809-9a55-01a50da7dacb",$retorno[1]["id"]);
        $this->assertSame("Organização_2",$retorno[1]["descricao"]);
        $this->assertSame($insert["data_cad"],$retorno[1]["data_cad"]);
        $this->assertSame(0,$retorno[1]["inativo"]);

    }

    public function testCriarUsuarios(): void
    {
        $InstalarBanco = new InstalarBanco();

        $retorno = $InstalarBanco->criarUsuarios();

        $this->assertTrue($retorno);

        $insert["data_cad"] = date("Y-m-d H:i:s");
        $insert["inativo"] = 0;

        $insert["id"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $insert["nome"] = "Usuario_1";
        $insert["email"] = "Usuario_1@mail.com";
        $insert["senha"] = 'LoYa+VvQqy2A67Rfar#(';
        $insert["nivel"] = "3";
        $insert["organizacao"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $retorno = $InstalarBanco->insertSimples("Usuarios", $insert);
        $this->assertSame(1,$retorno);

        $insert["id"] = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $insert["nome"] = "Usuario_2";
        $insert["email"] = "Usuario_2@mail.com";
        $insert["senha"] = '),Ub[be#c"3/305hW&y>';
        $insert["nivel"] = "1";
        $insert["organizacao"] = "";
        $retorno = $InstalarBanco->insertSimples("Usuarios", $insert);
        $this->assertSame(1,$retorno);

        $insert["id"] = "45944060-2428-4629-b394-d95471e7a6db";
        $insert["nome"] = "Usuario_3";
        $insert["email"] = "Usuario_3@mail.com";
        $insert["senha"] = '),Ub[be#c"3/305hW&y>';
        $insert["nivel"] = "150";
        $insert["organizacao"] = "";
        $retorno = $InstalarBanco->insertSimples("Usuarios", $insert);
        $this->assertFalse($retorno);

        $insert["id"] = "5b817ac2-8d21-4c7e-b140-d12549f897be";
        $insert["nivel"] = "1";
        $insert["organizacao"] = "idnaoexistente";
        $retorno = $InstalarBanco->insertSimples("Usuarios", $insert);
        $this->assertFalse($retorno);

        $retorno = $InstalarBanco->selectSimples("Usuarios");

        $this->assertIsArray($retorno);
        $this->assertCount(2,$retorno);
        $this->assertCount(8,$retorno[0]);

        $this->assertSame("302f91e2-57ac-47b4-9ea1-ce524cf77f9d",$retorno[0]["id"]);
        $this->assertSame("Usuario_1",$retorno[0]["nome"]);
        $this->assertSame("Usuario_1@mail.com",$retorno[0]["email"]);
        $this->assertSame("LoYa+VvQqy2A67Rfar#(",$retorno[0]["senha"]);
        $this->assertSame("3",$retorno[0]["nivel"]);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",$retorno[0]["organizacao"]);
        $this->assertSame($insert["data_cad"],$retorno[0]["data_cad"]);
        $this->assertSame(0,$retorno[0]["inativo"]);

        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",$retorno[1]["id"]);
        $this->assertSame("Usuario_2",$retorno[1]["nome"]);
        $this->assertSame("Usuario_2@mail.com",$retorno[1]["email"]);
        $this->assertSame('),Ub[be#c"3/305hW&y>',$retorno[1]["senha"]);
        $this->assertSame("1",$retorno[1]["nivel"]);
        $this->assertSame("",$retorno[1]["organizacao"]);
        $this->assertSame($insert["data_cad"],$retorno[1]["data_cad"]);
        $this->assertSame(0,$retorno[1]["inativo"]);

    }

    public function testCriarLogUsuario(): void
    {
        $InstalarBanco = new InstalarBanco();

        $retorno = $InstalarBanco->criarUsuarioLog();

        $this->assertTrue($retorno);

        $insert["data_mod"] = date("Y-m-d H:i:s");

        $insert["id"] = "32d6dd16-0adf-43f0-80e3-519306479555";
        $insert["id_usuario"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $insert["descricao_mod"] = "descricao_mod_1";
        $insert["usuario_mod"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $retorno = $InstalarBanco->insertSimples("Log_Usuarios", $insert);
        $this->assertSame(1,$retorno);

        $insert["id"] = "98238c98-880d-45ed-bbad-19e72fb3ac5d";
        $insert["id_usuario"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $insert["descricao_mod"] = "descricao_mod_2";
        $insert["usuario_mod"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $retorno = $InstalarBanco->insertSimples("Log_Usuarios", $insert);
        $this->assertSame(1,$retorno);

        $insert["id"] = "28c6d6b5-9748-4043-a661-bb2b6df0b8bd";
        $insert["id_usuario"] = "idinexistente";
        $insert["descricao_mod"] = "descricao_mod_3";
        $insert["usuario_mod"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $retorno = $InstalarBanco->insertSimples("Log_Usuarios", $insert);
        $this->assertFalse($retorno);

        $insert["id_usuario"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $insert["usuario_mod"] = "idinexistente";
        $retorno = $InstalarBanco->insertSimples("Log_Usuarios", $insert);
        $this->assertFalse($retorno);

        $insert["id_usuario"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $insert["usuario_mod"] = "";
        $retorno = $InstalarBanco->insertSimples("Log_Usuarios", $insert);
        $this->assertFalse($retorno);

        $insert["id_usuario"] = "";
        $insert["usuario_mod"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $retorno = $InstalarBanco->insertSimples("Log_Usuarios", $insert);
        $this->assertFalse($retorno);

        $retorno = $InstalarBanco->selectSimples("Log_Usuarios");

        $this->assertIsArray($retorno);
        $this->assertCount(2,$retorno);
        $this->assertCount(5,$retorno[0]);

        $this->assertSame("32d6dd16-0adf-43f0-80e3-519306479555",$retorno[0]["id"]);
        $this->assertSame("302f91e2-57ac-47b4-9ea1-ce524cf77f9d",$retorno[0]["id_usuario"]);
        $this->assertSame("descricao_mod_1",$retorno[0]["descricao_mod"]);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",$retorno[0]["usuario_mod"]);
        $this->assertSame($insert["data_mod"],$retorno[0]["data_mod"]);

        $this->assertSame("98238c98-880d-45ed-bbad-19e72fb3ac5d",$retorno[1]["id"]);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",$retorno[1]["id_usuario"]);
        $this->assertSame("descricao_mod_2",$retorno[1]["descricao_mod"]);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",$retorno[1]["usuario_mod"]);
        $this->assertSame($insert["data_mod"],$retorno[1]["data_mod"]);
    }

    public function testCriarLogOrganizacao(): void
    {
        $InstalarBanco = new InstalarBanco();

        $retorno = $InstalarBanco->criarOrganizacaoLog();

        $this->assertTrue($retorno);

        $insert["data_mod"] = date("Y-m-d H:i:s");

        $insert["id"] = "fb720834-76a3-11ee-b962-0242ac120002";
        $insert["id_organizacao"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $insert["descricao_mod"] = "descricao_mod_1";
        $insert["usuario_mod"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $retorno = $InstalarBanco->insertSimples("Log_Organizacao", $insert);
        $this->assertSame(1,$retorno);

        $insert["id"] = "50a0311e-a18e-4d36-84e3-d301d4151484";
        $insert["id_organizacao"] = "aeab3060-01fe-4809-9a55-01a50da7dacb";
        $insert["descricao_mod"] = "descricao_mod_2";
        $insert["usuario_mod"] = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $retorno = $InstalarBanco->insertSimples("Log_Organizacao", $insert);
        $this->assertSame(1,$retorno);

        $insert["id"] = "e7fab30c-b4a8-4fdf-bdd9-98a448a3a976";
        $insert["id_organizacao"] = "aeab3060-01fe-4809-9a55-01a50da7dacb";
        $insert["descricao_mod"] = "descricao_mod_3";
        $insert["usuario_mod"] = "idinexistente";
        $retorno = $InstalarBanco->insertSimples("Log_Organizacao", $insert);
        $this->assertFalse($retorno);

        $insert["id_organizacao"] = "idinexistente";
        $insert["usuario_mod"] = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $retorno = $InstalarBanco->insertSimples("Log_Organizacao", $insert);
        $this->assertFalse($retorno);

        $insert["id_organizacao"] = "";
        $insert["usuario_mod"] = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $retorno = $InstalarBanco->insertSimples("Log_Organizacao", $insert);
        $this->assertFalse($retorno);

        $insert["id_organizacao"] = "aeab3060-01fe-4809-9a55-01a50da7dacb";
        $insert["usuario_mod"] = "";
        $retorno = $InstalarBanco->insertSimples("Log_Organizacao", $insert);
        $this->assertFalse($retorno);

        $retorno = $InstalarBanco->selectSimples("Log_Organizacao");

        $this->assertIsArray($retorno);
        $this->assertCount(2,$retorno);
        $this->assertCount(5,$retorno[0]);

        $this->assertSame("fb720834-76a3-11ee-b962-0242ac120002",$retorno[0]["id"]);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",$retorno[0]["id_organizacao"]);
        $this->assertSame("descricao_mod_1",$retorno[0]["descricao_mod"]);
        $this->assertSame("302f91e2-57ac-47b4-9ea1-ce524cf77f9d",$retorno[0]["usuario_mod"]);
        $this->assertSame($insert["data_mod"],$retorno[0]["data_mod"]);

        $this->assertSame("50a0311e-a18e-4d36-84e3-d301d4151484",$retorno[1]["id"]);
        $this->assertSame("aeab3060-01fe-4809-9a55-01a50da7dacb",$retorno[1]["id_organizacao"]);
        $this->assertSame("descricao_mod_2",$retorno[1]["descricao_mod"]);
        $this->assertSame("e9bc84ff-f66b-41e9-a0fe-d0a82a00a567",$retorno[1]["usuario_mod"]);
        $this->assertSame($insert["data_mod"],$retorno[1]["data_mod"]);

    }

    public function testCriarLogRequisicao(): void
    {
        $InstalarBanco = new InstalarBanco();

        $retorno = $InstalarBanco->criarUsuarioRequisicao();

        $this->assertTrue($retorno);

        $insert["data_cad"] = date("Y-m-d H:i:s");

        $insert["id"] = "8ad704ac-a53c-418a-b38b-12eee4bf6d68";
        $insert["token"] = "eyJhbGciOiJIUzI1NiJ9.eyJ1c2VybmFtZSI6InVzZXJuYW1lXzEifQ.5IkbcTrWwSvLRvqKPAVAgtx9c1rnx1SsTsAGAvK_txI";
        $insert["endpoint"] = "/teste/1";
        $insert["usuario_cad"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $retorno = $InstalarBanco->insertSimples("Log_Requisicao", $insert);
        $this->assertSame(1,$retorno);

        $insert["id"] = "7ec21c87-1dd9-4e44-9b30-4466a0d895ea";
        $insert["token"] = "eyJhbGciOiJIUzI1NiJ9.eyJ1c2VybmFtZSI6InVzZXJuYW1lXzIifQ.KmA_bqgwOV0mTgMyq0mUdtW20w-BxRCm_YyX2XtwZIo";
        $insert["endpoint"] = "/teste/2";
        $insert["usuario_cad"] = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $retorno = $InstalarBanco->insertSimples("Log_Requisicao", $insert);
        $this->assertSame(1,$retorno);

        $insert["id"] = "1938bcd5-54ee-4443-9707-be724be7e598";
        $insert["token"] = "eyJhbGciOiJIUzI1NiJ9.eyJ1c2VybmFtZSI6InVzZXJuYW1lXzMifQ.hol3GOCgGIrBTaU4rfdADgoBKxuaO2QuQWztjgRir5Q";
        $insert["endpoint"] = "/teste/3";
        $insert["usuario_cad"] = "idinexistente";
        $retorno = $InstalarBanco->insertSimples("Log_Requisicao", $insert);
        $this->assertFalse($retorno);

        $insert["usuario_cad"] = "";
        $retorno = $InstalarBanco->insertSimples("Log_Requisicao", $insert);
        $this->assertFalse($retorno);

        $retorno = $InstalarBanco->selectSimples("Log_Requisicao");

        $this->assertIsArray($retorno);
        $this->assertCount(2,$retorno);
        $this->assertCount(5,$retorno[0]);

        $this->assertSame("8ad704ac-a53c-418a-b38b-12eee4bf6d68",$retorno[0]["id"]);
        $this->assertSame("eyJhbGciOiJIUzI1NiJ9.eyJ1c2VybmFtZSI6InVzZXJuYW1lXzEifQ.5IkbcTrWwSvLRvqKPAVAgtx9c1rnx1SsTsAGAvK_txI",$retorno[0]["token"]);
        $this->assertSame("/teste/1",$retorno[0]["endpoint"]);
        $this->assertSame("302f91e2-57ac-47b4-9ea1-ce524cf77f9d",$retorno[0]["usuario_cad"]);
        $this->assertSame($insert["data_cad"],$retorno[0]["data_cad"]);

        $this->assertSame("7ec21c87-1dd9-4e44-9b30-4466a0d895ea",$retorno[1]["id"]);
        $this->assertSame("eyJhbGciOiJIUzI1NiJ9.eyJ1c2VybmFtZSI6InVzZXJuYW1lXzIifQ.KmA_bqgwOV0mTgMyq0mUdtW20w-BxRCm_YyX2XtwZIo",$retorno[1]["token"]);
        $this->assertSame("/teste/2",$retorno[1]["endpoint"]);
        $this->assertSame("e9bc84ff-f66b-41e9-a0fe-d0a82a00a567",$retorno[1]["usuario_cad"]);
        $this->assertSame($insert["data_cad"],$retorno[1]["data_cad"]);
    }

    public function testCriarAtivos(): void
    {
        $InstalarBanco = new InstalarBanco();

        $retorno = $InstalarBanco->criarAtivos();

        $this->assertTrue($retorno);

        $insert["data_cad"] = date("Y-m-d H:i:s");

        $insert["id"] = "9cbd7405-ef8d-43f3-bc55-bcc78da4613d";
        $insert["descricao"] = "descricao_1";
        $insert["categoria"] = "categoria_1";
        $insert["organizacao"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";
        $insert["inativo"] = "0";
        $retorno = $InstalarBanco->insertSimples("Ativos", $insert);
        $this->assertSame(1,$retorno);

        $insert["id"] = "61aec667-2716-4672-8765-85a87226d05f";
        $insert["descricao"] = "descricao_2";
        $insert["categoria"] = "categoria_2";
        $insert["organizacao"] = "aeab3060-01fe-4809-9a55-01a50da7dacb";
        $insert["inativo"] = "0";
        $retorno = $InstalarBanco->insertSimples("Ativos", $insert);
        $this->assertSame(1,$retorno);

        $insert["id"] = "b0ff18e6-a738-4a50-bbca-0e17d3dbec80";
        $insert["descricao"] = "descricao_3";
        $insert["categoria"] = "categoria_3";
        $insert["organizacao"] = "idinexistente";
        $insert["inativo"] = "0";
        $retorno = $InstalarBanco->insertSimples("Ativos", $insert);
        $this->assertFalse($retorno);

        $insert["organizacao"] = "";
        $retorno = $InstalarBanco->insertSimples("Ativos", $insert);
        $this->assertFalse($retorno);

        $retorno = $InstalarBanco->selectSimples("Ativos");

        $this->assertIsArray($retorno);
        $this->assertCount(2,$retorno);
        $this->assertCount(6,$retorno[0]);

        $this->assertSame("9cbd7405-ef8d-43f3-bc55-bcc78da4613d",$retorno[0]["id"]);
        $this->assertSame("descricao_1",$retorno[0]["descricao"]);
        $this->assertSame("categoria_1",$retorno[0]["categoria"]);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",$retorno[0]["organizacao"]);
        $this->assertSame("0",$retorno[0]["inativo"]);
        $this->assertSame($insert["data_cad"],$retorno[0]["data_cad"]);

        $this->assertSame("61aec667-2716-4672-8765-85a87226d05f",$retorno[1]["id"]);
        $this->assertSame("descricao_2",$retorno[1]["descricao"]);
        $this->assertSame("categoria_2",$retorno[1]["categoria"]);
        $this->assertSame("aeab3060-01fe-4809-9a55-01a50da7dacb",$retorno[1]["organizacao"]);
        $this->assertSame("0",$retorno[1]["inativo"]);
        $this->assertSame($insert["data_cad"],$retorno[1]["data_cad"]);
    }

    public function testCriarLogAtivo(): void
    {
        $InstalarBanco = new InstalarBanco();

        $retorno = $InstalarBanco->criarLogAtivo();

        $this->assertTrue($retorno);

        $insert["data_mod"] = date("Y-m-d H:i:s");

        $insert["id"] = "4b5a6428-f9a0-4610-af83-95398df0bae8";
        $insert["id_ativo_mod"] = "9cbd7405-ef8d-43f3-bc55-bcc78da4613d";
        $insert["descricao_mod"] = "descricao_mod_1";
        $insert["usuario_mod"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $retorno = $InstalarBanco->insertSimples("Log_Ativos", $insert);
        $this->assertSame(1,$retorno);

        $insert["id"] = "c7567ce6-9014-465f-a23c-f1d318e62f05";
        $insert["id_ativo_mod"] = "61aec667-2716-4672-8765-85a87226d05f";
        $insert["descricao_mod"] = "descricao_mod_2";
        $insert["usuario_mod"] = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $retorno = $InstalarBanco->insertSimples("Log_Ativos", $insert);
        $this->assertSame(1,$retorno);

        $insert["id"] = "a3cae107-9e91-458e-a699-79508796d0e6";
        $insert["id_ativo_mod"] = "idinexistente";
        $insert["descricao_mod"] = "descricao_mod_3";
        $insert["usuario_mod"] = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $retorno = $InstalarBanco->insertSimples("Log_Ativos", $insert);
        $this->assertFalse($retorno);

        $insert["id_ativo_mod"] = "61aec667-2716-4672-8765-85a87226d05f";
        $insert["usuario_mod"] = "idinexistente";
        $retorno = $InstalarBanco->insertSimples("Log_Ativos", $insert);
        $this->assertFalse($retorno);

        $insert["id_ativo_mod"] = "61aec667-2716-4672-8765-85a87226d05f";
        $insert["usuario_mod"] = "";
        $retorno = $InstalarBanco->insertSimples("Log_Ativos", $insert);
        $this->assertFalse($retorno);

        $insert["id_ativo_mod"] = "";
        $insert["usuario_mod"] = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $retorno = $InstalarBanco->insertSimples("Log_Ativos", $insert);
        $this->assertFalse($retorno);

        $retorno = $InstalarBanco->selectSimples("Log_Ativos");

        $this->assertIsArray($retorno);
        $this->assertCount(2,$retorno);
        $this->assertCount(5,$retorno[0]);

        $this->assertSame("4b5a6428-f9a0-4610-af83-95398df0bae8",$retorno[0]["id"]);
        $this->assertSame("9cbd7405-ef8d-43f3-bc55-bcc78da4613d",$retorno[0]["id_ativo_mod"]);
        $this->assertSame("descricao_mod_1",$retorno[0]["descricao_mod"]);
        $this->assertSame("302f91e2-57ac-47b4-9ea1-ce524cf77f9d",$retorno[0]["usuario_mod"]);
        $this->assertSame($insert["data_mod"],$retorno[0]["data_mod"]);

        $this->assertSame("c7567ce6-9014-465f-a23c-f1d318e62f05",$retorno[1]["id"]);
        $this->assertSame("61aec667-2716-4672-8765-85a87226d05f",$retorno[1]["id_ativo_mod"]);
        $this->assertSame("descricao_mod_2",$retorno[1]["descricao_mod"]);
        $this->assertSame("e9bc84ff-f66b-41e9-a0fe-d0a82a00a567",$retorno[1]["usuario_mod"]);
        $this->assertSame($insert["data_mod"],$retorno[1]["data_mod"]);
    }

    public function testCriarAtivoAmbiente(): void
    {
        $InstalarBanco = new InstalarBanco();

        $retorno = $InstalarBanco->criarAtivoAmbiente();

        $this->assertTrue($retorno);

        $insert["data_cad"] = date("Y-m-d H:i:s");

        $insert["id"] = "349c6a56-e963-4b95-9d10-e7a60891048c";
        $insert["id_ativo"] = "61aec667-2716-4672-8765-85a87226d05f";
        $insert["id_ativo_ambiente"] = "9cbd7405-ef8d-43f3-bc55-bcc78da4613d";
        $insert["Inativo"] = "0";
        $retorno = $InstalarBanco->insertSimples("Ativo_Ambiente", $insert);
        $this->assertSame(1,$retorno);

        $insert["id"] = "0c801eb0-2f38-4a28-a907-9d31128fb58d";
        $insert["id_ativo"] = "61aec667-2716-4672-8765-85a87226d05f";
        $insert["id_ativo_ambiente"] = "9cbd7405-ef8d-43f3-bc55-bcc78da4613d";
        $insert["Inativo"] = "0";
        $retorno = $InstalarBanco->insertSimples("Ativo_Ambiente", $insert);
        $this->assertFalse($retorno);

        $insert["id_ativo"] = "idinexistente";
        $insert["id_ativo_ambiente"] = "9cbd7405-ef8d-43f3-bc55-bcc78da4613d";
        $retorno = $InstalarBanco->insertSimples("Ativo_Ambiente", $insert);
        $this->assertFalse($retorno);

        $insert["id_ativo"] = "61aec667-2716-4672-8765-85a87226d05f";
        $insert["id_ativo_ambiente"] = "idinexistente";
        $retorno = $InstalarBanco->insertSimples("Ativo_Ambiente", $insert);
        $this->assertFalse($retorno);

        $insert["id_ativo"] = "61aec667-2716-4672-8765-85a87226d05f";
        $insert["id_ativo_ambiente"] = "";
        $retorno = $InstalarBanco->insertSimples("Ativo_Ambiente", $insert);
        $this->assertFalse($retorno);

        $insert["id_ativo"] = "";
        $insert["id_ativo_ambiente"] = "9cbd7405-ef8d-43f3-bc55-bcc78da4613d";
        $retorno = $InstalarBanco->insertSimples("Ativo_Ambiente", $insert);
        $this->assertFalse($retorno);

        $retorno = $InstalarBanco->selectSimples("Ativo_Ambiente");

        $this->assertIsArray($retorno);
        $this->assertCount(2,$retorno);
        $this->assertCount(5,$retorno[0]);

        $this->assertSame("349c6a56-e963-4b95-9d10-e7a60891048c",$retorno[0]["id"]);
        $this->assertSame("61aec667-2716-4672-8765-85a87226d05f",$retorno[0]["id_ativo"]);
        $this->assertSame("9cbd7405-ef8d-43f3-bc55-bcc78da4613d",$retorno[0]["id_ativo_ambiente"]);
        $this->assertSame("0",$retorno[0]["Inativo"]);
        $this->assertSame($insert["data_cad"],$retorno[0]["data_cad"]);

    }

    public function testCriarLogAtivoAmbiente(): void
    {
        $InstalarBanco = new InstalarBanco();

        $retorno = $InstalarBanco->criarLogAtivoAmbiente();

        $this->assertTrue($retorno);

        $insert["data_mod"] = date("Y-m-d H:i:s");

        $insert["id"] = "95fe517c-5144-4a30-a960-9b2cfd9d6bc7";
        $insert["id_ativo_ambiente_mod"] = "349c6a56-e963-4b95-9d10-e7a60891048c";
        $insert["descricao_mod"] = "descricao_mod_1";
        $insert["usuario_mod"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $retorno = $InstalarBanco->insertSimples("Log_Ativo_Ambiente", $insert);
        $this->assertSame(1,$retorno);

        $insert["id"] = "8fa038ee-e099-481f-9cf5-e9ead6498d12";
        $insert["id_ativo_ambiente_mod"] = "349c6a56-e963-4b95-9d10-e7a60891048c";
        $insert["descricao_mod"] = "descricao_mod_2";
        $insert["usuario_mod"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $retorno = $InstalarBanco->insertSimples("Log_Ativo_Ambiente", $insert);
        $this->assertSame(1,$retorno);

        $insert["id"] = "4c6f7202-f007-4a8c-a4da-8f0317cf38de";
        $insert["id_ativo_ambiente_mod"] = "idinexistente";
        $insert["descricao_mod"] = "descricao_mod_3";
        $insert["usuario_mod"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $retorno = $InstalarBanco->insertSimples("Log_Ativo_Ambiente", $insert);
        $this->assertFalse($retorno);

        $insert["id_ativo_ambiente_mod"] = "349c6a56-e963-4b95-9d10-e7a60891048c";
        $insert["usuario_mod"] = "idinexistente";
        $retorno = $InstalarBanco->insertSimples("Log_Ativo_Ambiente", $insert);
        $this->assertFalse($retorno);

        $insert["id_ativo_ambiente_mod"] = "349c6a56-e963-4b95-9d10-e7a60891048c";
        $insert["usuario_mod"] = "";
        $retorno = $InstalarBanco->insertSimples("Log_Ativo_Ambiente", $insert);
        $this->assertFalse($retorno);

        $insert["id_ativo_ambiente_mod"] = "";
        $insert["usuario_mod"] = "302f91e2-57ac-47b4-9ea1-ce524cf77f9d";
        $retorno = $InstalarBanco->insertSimples("Log_Ativo_Ambiente", $insert);
        $this->assertFalse($retorno);

        $retorno = $InstalarBanco->selectSimples("Log_Ativo_Ambiente");

        $this->assertIsArray($retorno);
        $this->assertCount(2,$retorno);
        $this->assertCount(5,$retorno[0]);

        $this->assertSame("95fe517c-5144-4a30-a960-9b2cfd9d6bc7",$retorno[0]["id"]);
        $this->assertSame("349c6a56-e963-4b95-9d10-e7a60891048c",$retorno[0]["id_ativo_ambiente_mod"]);
        $this->assertSame("descricao_mod_1",$retorno[0]["descricao_mod"]);
        $this->assertSame("302f91e2-57ac-47b4-9ea1-ce524cf77f9d",$retorno[0]["usuario_mod"]);
        $this->assertSame($insert["data_mod"],$retorno[0]["data_mod"]);

        $this->assertSame("95fe517c-5144-4a30-a960-9b2cfd9d6bc7",$retorno[1]["id"]);
        $this->assertSame("349c6a56-e963-4b95-9d10-e7a60891048c",$retorno[1]["id_ativo_ambiente_mod"]);
        $this->assertSame("descricao_mod_2",$retorno[1]["descricao_mod"]);
        $this->assertSame("302f91e2-57ac-47b4-9ea1-ce524cf77f9d",$retorno[1]["usuario_mod"]);
        $this->assertSame($insert["data_mod"],$retorno[1]["data_mod"]);

    }

}