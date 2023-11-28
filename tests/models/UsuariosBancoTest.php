<?php declare(strict_types=1);
namespace models;

use PHPUnit\Framework\TestCase;
use SiCATan_api\Models\UsuariosBanco;

final class UsuariosBancoTest extends TestCase
{
    public function testInstanciaClasse(): void
    {
        $UsuariosBanco = new UsuariosBanco();

        $this->assertInstanceOf(UsuariosBanco::class, $UsuariosBanco);
    }

    public function testCriarUsuarioBanco(): void
    {
        $UsuariosBanco = new UsuariosBanco();

        $_dados["id"] = "c2aeea10-6cae-435c-bb6d-ae302cb6cb53";
        $_dados["nome"] = "Usuario_1";
        $_dados["email"] = "Usuario_1_1@mail.com";
        $_dados["senha"] = password_hash("teste de senha",PASSWORD_DEFAULT);
        $_dados["data_cad"] = date("Y-m-d H:i:s");
        $_dados["nivel"] = 1;
        $_dados["organizacao"] = NULL;
        $_dados["inativo"] = 0;
        $_dados["usuario_mod"] = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $retorno = $UsuariosBanco->criaUsuario($_dados);
        $this->assertTrue($retorno);

        unset($_dados["usuario_mod"]);
        $retorno = $UsuariosBanco->criaUsuario($_dados);
        $this->assertFalse($retorno["status"]);
        $this->assertSame("falha ao inserir",$retorno["motivo"]);

        $_dados["id"] = "";
        $_dados["nome"] = "Usuario_1";
        $_dados["email"] = "Usuario_1_1@mail.com";
        $_dados["senha"] = password_hash("teste de senha",PASSWORD_DEFAULT);
        $_dados["data_cad"] = date("Y-m-d H:i:s");
        $_dados["nivel"] = 1;
        $_dados["organizacao"] = NULL;
        $_dados["inativo"] = 0;
        $_dados["usuario_mod"] = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $retorno = $UsuariosBanco->criaUsuario($_dados);
        $this->assertFalse($retorno["status"]);
        $this->assertSame("campo vazio",$retorno["motivo"]);

        $_dados["id"] = "c2aeea10-6cae-435c-bb6d-ae302cb6cb53";
        $_dados["nome"] = "";
        $_dados["email"] = "Usuario_1@mail.com";
        $retorno = $UsuariosBanco->criaUsuario($_dados);
        $this->assertFalse($retorno["status"]);
        $this->assertSame("campo vazio",$retorno["motivo"]);

        $_dados["nome"] = "Usuario_1";
        $_dados["email"] = "";
        $_dados["senha"] = password_hash("teste de senha",PASSWORD_DEFAULT);
        $retorno = $UsuariosBanco->criaUsuario($_dados);
        $this->assertFalse($retorno["status"]);
        $this->assertSame("campo vazio",$retorno["motivo"]);

        $_dados["email"] = "Usuario_1_1@mail.com";
        $_dados["senha"] = "";
        $_dados["data_cad"] = date("Y-m-d H:i:s");
        $retorno = $UsuariosBanco->criaUsuario($_dados);
        $this->assertFalse($retorno["status"]);
        $this->assertSame("campo vazio",$retorno["motivo"]);

        $_dados["senha"] = password_hash("teste de senha",PASSWORD_DEFAULT);
        $_dados["data_cad"] = "";
        $_dados["nivel"] = 1;
        $retorno = $UsuariosBanco->criaUsuario($_dados);
        $this->assertFalse($retorno["status"]);
        $this->assertSame("campo vazio",$retorno["motivo"]);

        $_dados["data_cad"] = date("Y-m-d H:i:s");
        $_dados["nivel"] = "";
        $_dados["organizacao"] = NULL;
        $_dados["inativo"] = 0;
        $retorno = $UsuariosBanco->criaUsuario($_dados);
        $this->assertFalse($retorno["status"]);
        $this->assertSame("campo vazio",$retorno["motivo"]);
    }

    public function testAtualizarUsuarioBanco(): void
    {
        $UsuariosBanco = new UsuariosBanco();

        $_dados["id"] = "c2aeea10-6cae-435c-bb6d-ae302cb6cb53";
        $_dados["nome"] = "Usuario 1 teste";
        $_dados["usuario_mod"] = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $retorno = $UsuariosBanco->atualizaUsuario($_dados);
        $this->assertTrue($retorno);
        unset($_dados);

        $_dados["id"] = "c2aeea10-6cae-435c-bb6d-ae302cb6cb53";
        $_dados["email"] = "Usuario_1_1@mail.net";
        $_dados["usuario_mod"] = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $retorno = $UsuariosBanco->atualizaUsuario($_dados);
        $this->assertTrue($retorno);
        unset($_dados);

        $_dados["id"] = "c2aeea10-6cae-435c-bb6d-ae302cb6cb53";
        $_dados["senha"] = password_hash("teste de senha 1",PASSWORD_DEFAULT);
        $_dados["usuario_mod"] = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $retorno = $UsuariosBanco->atualizaUsuario($_dados);
        $this->assertTrue($retorno);
        unset($_dados);

        $_dados["id"] = "c2aeea10-6cae-435c-bb6d-ae302cb6cb53";
        $_dados["senha"] = password_hash("teste de senha 1",PASSWORD_DEFAULT);
        $_dados["usuario_mod"] = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $retorno = $UsuariosBanco->atualizaUsuario($_dados);
        $this->assertTrue($retorno);
        unset($_dados);

        $_dados["id"] = "c2aeea10-6cae-435c-bb6d-ae302cb6cb53";
        $_dados["nivel"] = 2;
        $_dados["usuario_mod"] = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $retorno = $UsuariosBanco->atualizaUsuario($_dados);
        $this->assertTrue($retorno);
        unset($_dados);

        $_dados["id"] = "c2aeea10-6cae-435c-bb6d-ae302cb6cb53";
        $_dados["inativo"] = 1;
        $_dados["usuario_mod"] = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $retorno = $UsuariosBanco->atualizaUsuario($_dados);
        $this->assertTrue($retorno);
        unset($_dados);

        $_dados["id"] = "c2aeea10-6cae-435c-bb6d-ae302cb6cb53";
        $_dados["nome"] = "Usuario 1 test";
        $_dados["email"] = "Usuario_1_1@mail.xyz";
        $_dados["senha"] = password_hash("teste de senha",PASSWORD_DEFAULT);
        $_dados["nivel"] = 3;
        $_dados["inativo"] = 0;
        $_dados["usuario_mod"] = "e9bc84ff-f66b-41e9-a0fe-d0a82a00a567";
        $retorno = $UsuariosBanco->atualizaUsuario($_dados);
        $this->assertTrue($retorno);
        unset($_dados);
    }

    public function testBuscarUsuarioBanco(): void
    {
        $UsuariosBanco = new UsuariosBanco();

        $_dados["id"] = "c2aeea10-6cae-435c-bb6d-ae302cb6cb53";

        $retorno = $UsuariosBanco->buscarUsuario($_dados,true);
        $this->assertIsArray($retorno);
        unset($_dados);
        $this->assertCount(1,$retorno);
        $this->assertCount(9,$retorno[0]);

        $this->assertSame("c2aeea10-6cae-435c-bb6d-ae302cb6cb53",$retorno[0]["id"]);
        $this->assertSame("Usuario 1 test",$retorno[0]["nome"]);
        $this->assertSame("Usuario_1_1@mail.xyz",$retorno[0]["email"]);
        $this->assertTrue(password_verify("teste de senha",$retorno[0]["senha"]));
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen($retorno[0]["data_cad"]));
        $this->assertSame(NULL,$retorno[0]["data_mod"]);
        $this->assertSame(3,$retorno[0]["nivel"]);
        $this->assertSame(NULL,$retorno[0]["organizacao"]);
        $this->assertSame(0,$retorno[0]["inativo"]);


        $_dados["nome"] = "Usuario%1";

        $retorno = $UsuariosBanco->buscarUsuario($_dados,true);
        $this->assertIsArray($retorno);
        unset($_dados);
        $this->assertCount(2,$retorno);
        $this->assertCount(9,$retorno[0]);

        $this->assertSame("302f91e2-57ac-47b4-9ea1-ce524cf77f9d",$retorno[0]["id"]);
        $this->assertSame("Usuario_1",$retorno[0]["nome"]);
        $this->assertSame("Usuario_1@mail.com",$retorno[0]["email"]);
        $this->assertSame("LoYa+VvQqy2A67Rfar#(",$retorno[0]["senha"]);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen($retorno[0]["data_cad"]));
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen($retorno[0]["data_mod"]));
        $this->assertSame(3,$retorno[0]["nivel"]);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",$retorno[0]["organizacao"]);
        $this->assertSame(0,$retorno[0]["inativo"]);

        $this->assertSame("c2aeea10-6cae-435c-bb6d-ae302cb6cb53",$retorno[1]["id"]);
        $this->assertSame("Usuario 1 test",$retorno[1]["nome"]);
        $this->assertSame("Usuario_1_1@mail.xyz",$retorno[1]["email"]);
        $this->assertTrue(password_verify("teste de senha",$retorno[1]["senha"]));
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen($retorno[1]["data_cad"]));
        $this->assertSame(NULL,$retorno[1]["data_mod"]);
        $this->assertSame(3,$retorno[1]["nivel"]);
        $this->assertSame(NULL,$retorno[1]["organizacao"]);
        $this->assertSame(0,$retorno[1]["inativo"]);


        $_dados["email"] = "Usuario_1_1@mail.xyz";

        $retorno = $UsuariosBanco->buscarUsuario($_dados,true);
        $this->assertIsArray($retorno);
        unset($_dados);
        $this->assertCount(1,$retorno);
        $this->assertCount(9,$retorno[0]);

        $this->assertSame("c2aeea10-6cae-435c-bb6d-ae302cb6cb53",$retorno[0]["id"]);
        $this->assertSame("Usuario 1 test",$retorno[0]["nome"]);
        $this->assertSame("Usuario_1_1@mail.xyz",$retorno[0]["email"]);
        $this->assertTrue(password_verify("teste de senha",$retorno[0]["senha"]));
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen($retorno[0]["data_cad"]));
        $this->assertSame(NULL,$retorno[0]["data_mod"]);
        $this->assertSame(3,$retorno[0]["nivel"]);
        $this->assertSame(NULL,$retorno[0]["organizacao"]);
        $this->assertSame(0,$retorno[0]["inativo"]);


        $_dados["nivel"] = "1";

        $retorno = $UsuariosBanco->buscarUsuario($_dados,true);
        $this->assertIsArray($retorno);
        unset($_dados);
        $this->assertCount(1,$retorno);
        $this->assertCount(9,$retorno[0]);

        $this->assertSame("e9bc84ff-f66b-41e9-a0fe-d0a82a00a567",$retorno[0]["id"]);
        $this->assertSame("Usuario_2",$retorno[0]["nome"]);
        $this->assertSame("Usuario_2@mail.com",$retorno[0]["email"]);
        $this->assertSame('),Ub[be#c"3/305hW&y>',$retorno[0]["senha"]);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen($retorno[0]["data_cad"]));
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen($retorno[0]["data_mod"]));
        $this->assertSame(1,$retorno[0]["nivel"]);
        $this->assertSame(NULL,$retorno[0]["organizacao"]);
        $this->assertSame(0,$retorno[0]["inativo"]);


        $_dados["organizacao"] = "9b31781c-c2fd-49c5-af37-1538ee2f7cdb";

        $retorno = $UsuariosBanco->buscarUsuario($_dados,true);
        $this->assertIsArray($retorno);
        unset($_dados);
        $this->assertCount(1,$retorno);
        $this->assertCount(9,$retorno[0]);

        $this->assertSame("302f91e2-57ac-47b4-9ea1-ce524cf77f9d",$retorno[0]["id"]);
        $this->assertSame("Usuario_1",$retorno[0]["nome"]);
        $this->assertSame("Usuario_1@mail.com",$retorno[0]["email"]);
        $this->assertSame("LoYa+VvQqy2A67Rfar#(",$retorno[0]["senha"]);
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen($retorno[0]["data_cad"]));
        $this->assertSame(strlen(date("Y-m-d H:i:s")),strlen($retorno[0]["data_mod"]));
        $this->assertSame(3,$retorno[0]["nivel"]);
        $this->assertSame("9b31781c-c2fd-49c5-af37-1538ee2f7cdb",$retorno[0]["organizacao"]);
        $this->assertSame(0,$retorno[0]["inativo"]);

    }
}