<?php
namespace SiCATan_api\Controllers;

use Ramsey\Uuid\Uuid;
use SiCATan_api\Models\AtivosBanco;
use SiCATan_api\Models\UsuariosBanco;

class AtivosController
{
    private $AtivosBanco;
    private $UsuarioBanco;


    public function __construct()
    {
        $this->AtivosBanco = new AtivosBanco();
        $this->UsuarioBanco = new UsuariosBanco();

    }

    public function postAtivo(array $_dados, array $_token): string
    {
        $_campos = ["descricao", "categoria"];
        foreach ($_campos as $campo) {
            if(empty($_dados[$campo])){
                http_response_code(400);
                return json_encode(["status" => "error", "message" => "Dados ausentes"]);
            }
        }
        $requisitante = $this->UsuarioBanco->buscarUsuario(["id"=>$_token["id"]]);

        if($_token["nivel"] > 2 || empty($requisitante[0]["organizacao"])){
            http_response_code(403);
            return json_encode(["status" => "error", "message" => "Usuario sem permissão"]);
        }

        if(array_key_exists("ativo_ambiente",$_dados)){
            $_insert["ativo_ambiente"] = $_dados["ativo_ambiente"];
        } else {
            $_insert["ativo_ambiente"] = NULL;
        }

        $_insert["id"] = Uuid::uuid4()->toString();
        $_insert["descricao"] = $_dados["descricao"];
        $_insert["data_cad"] = date("Y-m-d H:i:s");
        $_insert["categoria"] = $_dados["categoria"];
        $_insert["organizacao"] = $requisitante[0]["organizacao"];
        $_insert["inativo"] = 0;

        $retorna_cria = $this->AtivosBanco->criaAtivo($_insert);

        if($retorna_cria == true){
            http_response_code(201);
            return json_encode(["status" => "success", "message" => "Ativo criado", "data" =>$this->AtivosBanco->buscarAtivo(["id"=>$_insert["id"]])[0]]);
        }else {
            http_response_code(500);
            return json_encode(["status" => "error", "message" => "Erro Interno"]);
        }

    }

    public function putAtivo(array $_dados, array $_token): string
    {
        foreach (["descricao","inativo","categoria"] as $campo){
            if(isset($_dados[$campo]) && (!empty($_dados[$campo]) || is_bool($_dados[$campo]) || is_int($_dados[$campo]))){
                $_update[$campo] = $_dados[$campo];
            }
        }
        if(array_key_exists("ativo_ambiente",$_dados)){
            $_update["ativo_ambiente"] = $_dados["ativo_ambiente"];
        }
        if(empty($_dados["id"]) || empty($_update)){
            http_response_code(400);
            return json_encode(["status" => "error","message" => "Dados ausentes"]);
        }

        $requisitante = $this->UsuarioBanco->buscarUsuario(["id"=>$_token["id"]])[0];
        $dados_ativo = $this->AtivosBanco->buscarAtivo(["id"=>$_dados["id"]])[0];

        if(($_token["nivel"] > 2) || ($requisitante["organizacao"] != $dados_ativo["organizacao"])) {
            http_response_code(403);
            return json_encode(["status" => "error","message" => "Sem permissão"]);
        }

        $_update["id"] = $_dados["id"];
        $_update["usuario_mod"] = $_token["id"];

        $_retorno_atualiza = $this->AtivosBanco->atualizaAtivo($_update);

        if($_retorno_atualiza == true){
            http_response_code(200);
            return json_encode(["status"=>"success", "message"=>"Ativo atualizado com sucesso", "data"=> $this->AtivosBanco->buscarAtivo(["id"=>$_dados["id"]])[0]]);
        } else {
            http_response_code(500);
            return json_encode(["status" => "error","message" => "Erro Interno"]);
        }
    }

    public function getAtivo(array $_token, ?string $_id=NULL, ?array $_dados=NULL): string
    {
        foreach (["descricao","organizacao","categoria","ativo_ambiente"] as $campo){
            if(!empty($_dados[$campo])){
                $_where[$campo] = $_dados[$campo];
            }
        }
        if(empty($_id) && empty($_where)){
            http_response_code(400);
            return json_encode(["status" => "error","message" => "Dados ausentes"]);
        }

        if(is_array($_dados) && array_key_exists("organizacao",$_dados)){
            $_where["ativo_ambiente"] = NULL;
        }

        $dados_requisitante = $this->UsuarioBanco->buscarUsuario(["id"=>$_token["id"]])[0];

        $_where["organizacao"] = $dados_requisitante["organizacao"];

        if(empty($_where["organizacao"])){
            http_response_code(404);
            return json_encode(["status" => "error","message" => "Resultado não encontrado"]);
        }

        if(!empty($_id)){
            $_where["id"] = $_id;
        }

        $retorno = $this->AtivosBanco->buscarAtivo($_where);

        if(!is_array($retorno) && $retorno == false){
            http_response_code(500);
            return json_encode(["status" => "error","message" => "Erro Interno"]);
        } else if (count($retorno) == 0){
            http_response_code(404);
            return json_encode(["status" => "error","message" => "Resultado não encontrado"]);
        } else {
            http_response_code(200);
            return json_encode($retorno);
        }

    }
}