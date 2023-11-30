<?php
namespace SiCATan_api\Models;

class OrganizacaoBanco extends ConexaoBanco
{
    public function criaOrganizacao(array $_dados): array|true
    {
        $campos_obrigatorios = ["id","descricao","data_cad"];
        foreach ($campos_obrigatorios as $campo){
            if(empty($_dados[$campo]))
                return ["status" => false, "motivo" => "campo vazio"];
        }
        if(!in_array($_dados["inativo"],[0,1])){
            return ["status" => false, "motivo" => "campo vazio"];
        }

        $_insert["id"] = $_dados["id"];
        $_insert["descricao"] = $_dados["descricao"];
        $_insert["data_cad"] = $_dados["data_cad"];
        $_insert["inativo"] = $_dados["inativo"];

        $retorno = $this->insertSimples("Organizacao", $_insert);

        //Implementar o sistema de log aqui

        if($retorno == 1){
            return true;
        } else {
            return ["status" => false, "motivo" => "falha ao inserir"];
        }
    }

    public function atualizaOrganizacao(array $_dados): array|true
    {
        $_campos = ["descricao", "inativo"];
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

        $retorno = $this->updateSimples("Organizacao",$_update,$_where);

        //inserir o fluxo do log

        if($retorno == 1){
            return true;
        } else {
            return ["status" => false, "motivo" => "falha no update"];
        }
    }

    public function buscarOrganizacao(array $_dados): array
    {
        return $this->selectSimples("Organizacao",["id"=>$_dados["id"]]);
    }
}