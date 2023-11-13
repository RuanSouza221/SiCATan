<?php
namespace SiCATan_api\Controllers;

use SiCATan_api\Models\UsuariosBanco;
use Ramsey\Uuid\Uuid;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Signer\Key\InMemory;

class UsuariosController
{
    private $UsuariosBanco;

    public function __construct()
    {
        $this->UsuariosBanco = new UsuariosBanco();
    }

    public function criarConta(array $_dados, array|null $_token = NULL): string
    {
        if(is_array($_token)){
            if($_token["nivel"] != 1){
                http_response_code(403);
                //implemente a saida aqui
                return json_encode(["errors" => ["message" => "Usuario sem permissão"]]);
            }
            $verifica_organizacao = $this->UsuariosBanco->buscarUsuario(["id" => $_token["id"]]);
            if(empty($verifica_organizacao[0]["organizacao"])){
                http_response_code(400);
                //implemente a saida aqui
                return json_encode(["errors" => ["message" => "Organização não encontrada"]]);
            }
        }

        $_campos = ["nome", "email", "senha"];
        foreach ($_campos as $campo) {
            if(empty($_dados[$campo])){
                http_response_code(400);
                //implemente a saida aqui
                return json_encode(["errors" => ["message" => "Dados ausentes"]]);
            }
        }
        $_dados["senha"] = password_hash($_dados["senha"],PASSWORD_DEFAULT);

        if(filter_var($_dados["email"], FILTER_VALIDATE_EMAIL) == false){
            http_response_code(400);
            //implemente a saida aqui
            return json_encode(["errors" => ["message" => "Email Invalido"]]);
        }

        $verifica_email = $this->UsuariosBanco->buscarUsuario(["email" => $_dados["email"]]);

        if(count($verifica_email) > 0){
            http_response_code(400);
            //implemente a saida aqui
            return json_encode(["errors" => ["message" => "Email já existe"]]);
        }

        $_insert["id"] = Uuid::uuid4()->toString();
        $_insert["nome"] = $_dados["nome"];
        $_insert["email"] = $_dados["email"];
        $_insert["senha"] = $_dados["senha"];
        $_insert["data_cad"] = date("Y-m-d H:i:s");
        $_insert["inativo"] = 0;
        if(isset($verifica_organizacao[0]["organizacao"])){
            $_insert["nivel"] = $_dados["nivel"];
            $_insert["organizacao"] = $verifica_organizacao[0]["organizacao"];
            $_insert["usuario_mod"] = $_token["id"];
        } else{
            $_insert["nivel"] = 1;
            $_insert["organizacao"] = NULL;
            $_insert["usuario_mod"] = $_insert["id"];
        }

        $retorno_cria = $this->UsuariosBanco->criaUsuario($_insert);

        if($retorno_cria == true){
            http_response_code(201);
            $retorno_user = $this->UsuariosBanco->buscarUsuario(["id" => $_insert["id"]]);
            //implemente a saida aqui
            return json_encode(["data" => $retorno_user[0]]);
        }else {
            http_response_code(500);
            //implemente a saida aqui
            return json_encode(["errors" => ["message" => "Erro Interno"]]);
        }

    }

    public function loginConta(array $_dados): string
    {
        foreach (["email", "senha"] as $campo){
            if(empty($_dados[$campo])){
                http_response_code(400);
                //implemente a saida aqui
                return json_encode(["status" => "error","message" => "Dados ausentes"]);
            }
        }
        if(filter_var($_dados["email"], FILTER_VALIDATE_EMAIL) == false) {
            http_response_code(400);
            //implemente a saida aqui
            return json_encode(["status" => "error", "message" => "Email invalido"]);
        }

        $dados_usuario = $this->UsuariosBanco->buscarUsuario(["email" => $_dados["email"]],true);

        if(!is_array($dados_usuario) || count($dados_usuario) != 1 || !password_verify($_dados["senha"],$dados_usuario[0]["senha"])){
            http_response_code(401);
            //implemente a saida aqui
            return json_encode(["status" => "error", "message" => "Falha na autenticação. Verifique suas credenciais"]);
        }

        $token = (new Builder(new JoseEncoder(),ChainedFormatter::default()))
        ->withClaim('id', $dados_usuario[0]["id"])
        ->withClaim('nivel', $dados_usuario[0]["nivel"])
        ->withClaim('email', $dados_usuario[0]["email"])
        ->getToken(new Sha256(), InMemory::base64Encoded(getenv("CHAVE_TOKEN")));

        http_response_code(200);
        //implemente a saida aqui
        return json_encode(["status" => "success", "message" => "Login bem-sucedido", "data" => ["access_token" => $token->toString()]]);
    }

    public function atualizaConta(array $_dados, array $_token): string
    {
        foreach (["nome","email","senha","nivel","inativo"] as $campo){
            if(isset($_dados[$campo]) && (!empty($_dados[$campo]) || is_bool($_dados[$campo]) || is_int($_dados[$campo]))){
                $_update[$campo] = $_dados[$campo];
            }
        }

        if(empty($_dados["id"]) || empty($_update)){
            http_response_code(400);
            //implemente a saida aqui
            return json_encode(["status" => "error","message" => "Dados ausentes"]);
        }

        if(isset($_update["email"])){
            if(filter_var($_update["email"],FILTER_VALIDATE_EMAIL) == false){
                http_response_code(400);
                //implemente a saida aqui
                return json_encode(["status" => "error","message" => "Email invalido"]);
            }
            if(count($this->UsuariosBanco->buscarUsuario(["email" => $_update["email"]])) > 0){
                http_response_code(400);
                //implemente a saida aqui
                return json_encode(["status" => "error","message" => "Email já existe"]);
            }
        }

        $dados_modificador = $this->UsuariosBanco->buscarUsuario(["id" => $_token["id"]]);
        $dados_modificado = $this->UsuariosBanco->buscarUsuario(["id" => $_dados["id"]]);
        if(count($dados_modificado) == 0){
            http_response_code(404);
            //implemente a saida aqui
            return json_encode(["status" => "error","message" => "Usuario não encontrado"]);
        }
        if($_dados["id"] != $_token["id"]){
            if(isset($_update["senha"])){
                http_response_code(403);
                //implemente a saida aqui
                return json_encode(["status" => "error","message" => "Sem permissão para modificar"]);
            }
            if(empty($dados_modificador[0]["organizacao"]) || $dados_modificado[0]["organizacao"] != $dados_modificador[0]["organizacao"]){
                http_response_code(403);
                //implemente a saida aqui
                return json_encode(["status" => "error","message" => "Sem permissão para modificar"]);
            }
            if($_token["nivel"] > 2){
                http_response_code(403);
                //implemente a saida aqui
                return json_encode(["status" => "error","message" => "Sem permissão para modificar"]);
            }
            if($_token["nivel"] > 1 && isset($_update["inativo"]) && $_update["inativo"] == 1){
                http_response_code(403);
                //implemente a saida aqui
                return json_encode(["status" => "error","message" => "Sem permissão para modificar"]);
            }
        }
        if($_token["nivel"] > 1){
            if(isset($_update["nivel"]) && $_update["nivel"] < $_token["nivel"]){
                http_response_code(403);
                //implemente a saida aqui
                return json_encode(["status" => "error","message" => "Sem permissão para modificar"]);
            }
        }

        if(($_dados["id"] == $_token["id"] && $_token["nivel"] == 1) || $dados_modificado[0]["nivel"] == 1){
            if((isset($_update["nivel"]) && $_update["nivel"] > 1) || (isset($_update["inativo"]) && $_update["inativo"] == 1)){
                $dados_organizacao_nivel_1 = $this->UsuariosBanco->buscarUsuario(["organizacao"=>$dados_modificado[0]["organizacao"],"nivel"=>1,"inativo"=>0]);

                if(count($dados_organizacao_nivel_1) < 2){
                    http_response_code(400);
                    //implemente a saida aqui
                    return json_encode(["status" => "error","message" => "Não pode ser realizado, escale outro usuário primeiro"]);
                }
            }
        }

        if(isset($_update["senha"]))
            $_update["senha"] = password_hash($_update["senha"],PASSWORD_DEFAULT);

        $_update["id"] = $_dados["id"];
        $_update["usuario_mod"] = $_token["id"];

        $retorno_atualiza = $this->UsuariosBanco->atualizaUsuario($_update);

        if($retorno_atualiza == true){
            http_response_code(200);
            //implemente a saida aqui
            return json_encode(["status" => "success", "message" => "Usuario atualizado com sucesso", "data" => $this->UsuariosBanco->buscarUsuario(["id" => $_dados["id"]])[0]]);
        } else {
            http_response_code(500);
            //implemente a saida aqui
            return json_encode(["status" => "error","message" => "Erro Interno"]);
        }
    }

    public function vizualizaConta(array $_token, string|null $_id=NULL, array|null $_dados=NULL): string
    {
        foreach (["nome","nivel","organizacao"] as $campo){
            if(!empty($_dados[$campo])){
                $_where[$campo] = $_dados[$campo];
            }
        }
        if(empty($_id) && empty($_where)){
            http_response_code(400);
            //implemente a saida aqui
            return json_encode(["error" => "Dados ausentes"]);
        }

        $dados_requisitante = $this->UsuariosBanco->buscarUsuario(["id"=>$_token["id"]]);

        if(!empty($_where) && ($_token["nivel"] > 2 || empty($dados_requisitante[0]["organizacao"]))){
            http_response_code(403);
            //implemente a saida aqui
            return json_encode(["error" => "Usuario sem permissão"]);
        }

        if(!empty($_id)){
            $_where["id"] = $_id;

            $dados_solicitados = $this->UsuariosBanco->buscarUsuario(["id"=>$_id]);
            if(count($dados_solicitados) == 0){
                http_response_code(404);
                //implemente a saida aqui
                return json_encode(["error" => "Resultado não encontrado"]);
            }

            if($dados_requisitante[0]["organizacao"] != $dados_solicitados[0]["organizacao"]){
                http_response_code(403);
                //implemente a saida aqui
                return json_encode(["error" => "Usuario sem permissão"]);
            }
        }
        if(isset($_where["organizacao"]) && $_where["organizacao"] != $dados_requisitante[0]["organizacao"]){
            http_response_code(403);
            //implemente a saida aqui
            return json_encode(["error" => "Usuario sem permissão"]);
        }

        foreach (["nome"] as $campo) {
            if(isset($_dados["order"]) && $_dados["order"] == $campo){
                $_order = $campo;
            }
        }

        $retorno = $this->UsuariosBanco->buscarUsuario($_where,_order: ($_order ?? NULL));

        if(!is_array($retorno) && $retorno == false){
            http_response_code(500);
            //implemente a saida aqui
            return json_encode(["error" => "Erro Interno"]);
        } else if (count($retorno) == 0){
            http_response_code(404);
            //implemente a saida aqui
            return json_encode(["error" => "Resultado não encontrado"]);
        } else {
            http_response_code(200);
            //implemente a saida aqui
            return json_encode($retorno);
        }

    }

}