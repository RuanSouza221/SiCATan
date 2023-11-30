<?php
namespace SiCATan_api\Controllers;

use Ramsey\Uuid\Uuid;
use SiCATan_api\Models\OrganizacaoBanco;
use SiCATan_api\Models\UsuariosBanco;

class OrganizacaoController
{
    private $OrganizacaoBanco;
    private $UsuarioBanco;

    public function __construct()
    {
        $this->OrganizacaoBanco = new OrganizacaoBanco();
        $this->UsuarioBanco = new UsuariosBanco();
    }

    public function postOrganizacao(array $_dados, array $_token): string
    {
        if(empty($_dados["descricao"])){
            http_response_code(401);
            return json_encode(["status" => "error", "message" => "Dados ausentes"]);

        }
        if($_token["nivel"] > 1){
            http_response_code(403);
            return json_encode(["status" => "error", "message" => "Sem permissão"]);
        }

        $usuario = $this->UsuarioBanco->buscarUsuario(["id"=>$_token["id"]]);

        if(!empty($usuario[0]["organizacao"])){
            http_response_code(401);
            return json_encode(["status" => "error", "message" => "já possui uma organização"]);
        }

        $_insert["id"] = Uuid::uuid4()->toString();
        $_insert["descricao"] = $_dados["descricao"];
        $_insert["data_cad"] = date("Y-m-d H:i:s");
        $_insert["inativo"] = 0;

        $retorno = $this->OrganizacaoBanco->criaOrganizacao($_insert);

        if($retorno == true){

            $retorno = $this->UsuarioBanco->updateSimples("Usuarios",["organizacao"=>$_insert["id"]],["id"=>$_token["id"]]);

            if($retorno == 1){
                http_response_code(201);
                return json_encode(["status" => "success", "message" => "Organização criada", "data" =>  $this->OrganizacaoBanco->buscarOrganizacao(["id" => $_insert["id"]])[0]]);
            } else {
                http_response_code(500);
                return json_encode(["status" => "error", "message" => "Erro Interno"]);
            }
        } else {
            http_response_code(500);
            return json_encode(["status" => "error", "message" => "Erro Interno"]);
        }

    }

    public function putOrganizacao(array $_dados, array $_token): string
    {
        foreach (["descricao","inativo"] as $campo){
            if(isset($_dados[$campo]) && (!empty($_dados[$campo]) || is_bool($_dados[$campo]) || is_int($_dados[$campo]))){
                $_update[$campo] = $_dados[$campo];
            }
        }
        if(empty($_dados["id"]) || empty($_update)){
            http_response_code(400);
            return json_encode(["status" => "error","message" => "Dados ausentes"]);
        }
        if($_token["nivel"] > 1) {
            http_response_code(403);
            return json_encode(["status" => "error","message" => "Sem permissão"]);
        }

        $modificador = $this->UsuarioBanco->buscarUsuario(["id"=>$_token["id"]])[0];
        if($modificador["organizacao"] != $_dados["id"]){
            http_response_code(403);
            return json_encode(["status" => "error","message" => "Sem permissão"]);
        }

        $_update["id"] = $_dados["id"];
        $_update["usuario_mod"] = $_token["id"];

        $_retorno_atualiza = $this->OrganizacaoBanco->atualizaOrganizacao($_update);

        if($_retorno_atualiza == true){
            http_response_code(200);
            return json_encode(["status"=>"success", "message"=>"Organização atualizada com sucesso", "data"=> $this->OrganizacaoBanco->buscarOrganizacao(["id"=>$_dados["id"]])[0]]);
        } else {
            http_response_code(500);
            return json_encode(["status" => "error","message" => "Erro Interno"]);
        }

    }

    public function getOrganizacao($_token, $_id): string
    {
        if(empty($_id)){
            http_response_code(400);
            return json_encode(["error" => "Dados ausentes"]);
        }
        $dados_requisitante = $this->UsuarioBanco->buscarUsuario(["id"=>$_token["id"]])[0];

        if($dados_requisitante["organizacao"] != $_id){
            http_response_code(403);
            return json_encode(["error" => "Usuario sem permissão"]);
        }

        $retorno = $this->OrganizacaoBanco->buscarOrganizacao(["id"=>$_id]);

        if(!is_array($retorno) && $retorno == false || count($retorno) > 1){
            http_response_code(500);
            return json_encode(["error" => "Erro Interno"]);
        } else if (count($retorno) == 0){
            http_response_code(404);
            return json_encode(["error" => "Resultado não encontrado"]);
        } else {
            http_response_code(200);
            return json_encode($retorno);
        }
    }
}