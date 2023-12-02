<?php
namespace SiCATan_api\Models;

class AtivosBanco extends ConexaoBanco
{
    public function criaAtivo(array $_dados): array|true
    {
        $campos_obrigatorios = ["id","descricao","data_cad","categoria","organizacao"];
        foreach ($campos_obrigatorios as $campo){
            if(empty($_dados[$campo]))
                return ["status" => false, "motivo" => "campo vazio"];
        }

        $_insert["id"] = $_dados["id"];
        $_insert["descricao"] = $_dados["descricao"];
        $_insert["data_cad"] = $_dados["data_cad"];
        $_insert["categoria"] = $_dados["categoria"];
        $_insert["organizacao"] = $_dados["organizacao"];
        $_insert["inativo"] = $_dados["inativo"];
        $_insert["ativo_ambiente"] = $_dados["ativo_ambiente"];

        $retorno = $this->insertSimples("Ativos", $_insert);

        //Implementar o sistema de log aqui

        if($retorno == 1){
            return true;
        } else {
            return ["status" => false, "motivo" => "falha ao inserir"];
        }

    }

    public function atualizaAtivo(array $_dados): array|true
    {

        $_campos = ["descricao", "categoria", "inativo"];
        foreach ($_campos as $campo) {
            if (isset($_dados[$campo])) {
                $_update[$campo] = $_dados[$campo];
            }
        }
        if(array_key_exists("ativo_ambiente",$_dados)){
            $_update["ativo_ambiente"] = $_dados["ativo_ambiente"];
        }

        $_where["id"] = $_dados["id"];

        if(empty($_where["id"])){
            return ["status" => false, "motivo" => "sem id"];
        }
        if(empty($_update) || count($_update) == 0) {
            return ["status" => false, "motivo" => "sem dados a atualizar"];
        }

        $retorno = $this->updateSimples("Ativos",$_update,$_where);

        //inserir o fluxo do log

        if($retorno == 1){
            return true;
        } else {
            return ["status" => false, "motivo" => "falha no update"];
        }
    }

    public function buscarAtivo(array $_dados): array
    {
        $_campos = ["id","categoria","organizacao","ativo_ambiente"];
        foreach ($_campos as $campo){
            if(!empty($_dados[$campo])){
                $this->constroiBind($campo,$_dados[$campo]);
            }
        }

        if(array_key_exists("ativo_ambiente",$_dados) && $_dados["ativo_ambiente"] === NULL){
            $this->constroiBind("ativo_ambiente",NULL,"IS");
        }

        if(!empty($_dados["descricao"]))
            $this->constroiBind("descricao",$_dados["descricao"],"LIKE");

        $_sql = "SELECT at.id,at.descricao, at.data_cad, at.categoria,at.organizacao,at.inativo, at.ativo_ambiente,
                    (select count(id) from Ativos where at.id=ativo_ambiente) as conteudo_qtd
                FROM Ativos at
                WHERE {$this->constroiWhere()}
                ORDER BY LEFT(at.descricao,3),REGEXP_REPLACE(at.descricao, '[0-9]', ''),
                        IF(REGEXP_LIKE(at.descricao, '[0-9]+'), CAST(REGEXP_REPLACE(at.descricao, '[^0-9]+', ' ') AS SIGNED), 0),
                        at.descricao;";

        return $this->executar($_sql);
    }
}