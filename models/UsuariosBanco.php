<?php
namespace SiCATan_api\Models;

class UsuariosBanco extends ConexaoBanco
{
    public function criaUsuario(array $_dados): array|true
    {
        $campos_obrigatorios = ["id","nome","email","senha","data_cad","nivel"];
        foreach ($campos_obrigatorios as $campo){
            if(empty($_dados[$campo]))
                return ["status" => false, "motivo" => "campo vazio"];
        }

        $_insert["id"] = $_dados["id"];
        $_insert["nome"] = $_dados["nome"];
        $_insert["email"] = $_dados["email"];
        $_insert["senha"] = $_dados["senha"];
        $_insert["data_cad"] = $_dados["data_cad"];
        $_insert["nivel"] = $_dados["nivel"];
        $_insert["organizacao"] = $_dados["organizacao"];
        $_insert["inativo"] = $_dados["inativo"];

        $retorno = $this->insertSimples("Usuarios", $_insert);

        //Implementar o sistema de log aqui
        //$retorno = $this->insertSimples("Log_usuario", $_insert);

        if($retorno == 1){
            return true;
        } else {
            return ["status" => false, "motivo" => "falha ao inserir"];
        }
    }

    public function atualizaUsuario(array $_dados): array|true
    {
        $_campos = ["nome", "email", "senha", "nivel", "inativo"];
        foreach ($_campos as $campo) {
            if (isset($_dados[$campo])) {
                $_update[$campo] = $_dados[$campo];
            }
        }

        $_where["id"] = $_dados["id"];

        if(empty($_where["id"])){
            return ["status" => false, "motivo" => "sem id"];
        }
        if(empty($_update) || count($_update) == 0) {
            return ["status" => false, "motivo" => "sem dados a atualizar"];
        }

        $retorno = $this->updateSimples("Usuarios",$_update,$_where);

        //inserir o fluxo do log

        if($retorno == 1){
            return true;
        } else {
            return ["status" => false, "motivo" => "falha no update"];
        }
    }

    public function buscarUsuario(array $_dados, bool $_login=false, string|null $_order=NULL): array
    {
        $_campos = ["id","email","nivel","organizacao","inativo"];
        foreach ($_campos as $campo){
            if(!empty($_dados[$campo])){
                $this->constroiBind($campo,$_dados[$campo]);
            }
        }
        if(!empty($_dados["nome"]))
            $this->constroiBind("nome",$_dados["nome"],"LIKE");

        if($_login)
            $_senha = "u.senha,";
        else
            $_senha = "";

        if(!empty($_order)){
            $order = "ORDER BY $_order ASC;";
        }

        $_sql = "SELECT u.id, u.nome, u.email, $_senha u.data_cad,
                    (select data_mod from Log_Usuarios where id_usuario=u.id order by data_mod desc limit 1) as data_mod,
                    u.nivel, u.organizacao, u.inativo
                FROM Usuarios u
                WHERE {$this->constroiWhere()}
                ". ($order ?? ";");

        return $this->executar($_sql);
    }

}