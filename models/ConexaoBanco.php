<?php
namespace SiCATan_api\Models;

use Exception;
use PDO;
use PDOException;

class ConexaoBanco
{

    private PDO $db;

    public array $BindValor;
    public array $BindSql;

    public function __construct()
    {
        $_CONFIG['host'] = getenv("DATABASE_HOST");
        $_CONFIG['user'] = getenv("DATABASE_USER");
        $_CONFIG['pass'] = getenv("DATABASE_PASS");
        $_CONFIG['name'] = getenv("DATABASE_NAME");

        try {
            $this->db = new PDO(
                "mysql:host={$_CONFIG['host']};dbname={$_CONFIG['name']}",
                $_CONFIG['user'],
                $_CONFIG['pass'],
                [
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'",
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]
            );
        } catch (PDOException $e){
            //logica para o log em arquivo
        }

    }

    public function executar(string $_sql): array|int|bool
    {
        try {
            $_query = $this->db->prepare($_sql);

            if(!empty($this->BindValor)){
                foreach($this->BindValor as $chave => $valor){
                    $_query->bindValue(":$chave",$valor);
                }
                unset($this->BindValor);
                unset($this->BindSql);
            }

            $resultado = $_query->execute();

            return match (strtoupper(explode(" ", $_sql)[0])) {
                "INSERT","UPDATE", "DELETE" => $_query->rowCount(),
                "SELECT" => $_query->fetchAll(),
                default => $resultado,
            };
        } catch (Exception $e) {
            //logica para o log em arquivo
            return false;
        }
    }

    public function constroiBind(string $_campo, string|array|int $_valor, string $_operador="=" ): void
    {
        switch ($_operador) {
            case "BETWEEN":
                $this->BindValor[$_campo."_1"] = $_valor[0];
                $this->BindValor[$_campo."_2"] = $_valor[1];
                $this->BindSql[$_campo] = "$_campo $_operador :{$_campo}_1 AND :{$_campo}_2";
                break;
            case "LIKE":
                $this->BindValor[$_campo] = "%$_valor%";
                $this->BindSql[$_campo] = "$_campo $_operador :$_campo";
                break;
            case "IN":
                $this->BindSql[$_campo] = "$_campo $_operador (";
                foreach($_valor as $chave => $valor){
                    $this->BindValor[$_campo."_".$chave] = $valor;
                    $this->BindSql[$_campo] .= $chave > 0 ?  ",": null;
                    $this->BindSql[$_campo] .= ":{$_campo}_$chave";
                }
                $this->BindSql[$_campo] .= ")";
                break;
            default:
                $this->BindValor[$_campo] = $_valor;
                $this->BindSql[$_campo] = "$_campo $_operador :$_campo";
        }

    }

    public function constroiWhere(): string|false
    {
        return implode(" AND ",$this->BindSql);
    }

    public function constroiSet(): string|false
    {
        $set = implode(", ",$this->BindSql);
        unset($this->BindSql);
        return $set;
    }

    public function insertSimples(string $_tabela, array $_insert): int|false
    {
        foreach ($_insert as $chave => $valor)
            $this->constroiBind($chave,$valor);

        $_sql = "INSERT INTO $_tabela SET {$this->constroiSet()};";

        return $this->executar($_sql);
    }

    public function updateSimples($_tabela, array $_update, array $_where): int|false
    {
        foreach ($_update as $chave => $valor){
            $this->constroiBind($chave,$valor);
        }
        $set = $this->constroiSet();

        foreach ($_where as $chave => $valor){
            $this->constroiBind($chave,$valor);
        }

        $_sql = "UPDATE $_tabela SET $set WHERE {$this->constroiWhere()};";

        return $this->executar($_sql);
    }

    public function selectSimples(string $_tabela, array $_where = [], array $_colunas = ['*']): array|false
    {
        $select = implode(', ',$_colunas);

        if(count($_where) > 0){
            foreach ($_where as $chave => $valor){
                $this->constroiBind($chave,$valor);
            }
            $where = "WHERE {$this->constroiWhere()}";
        }



        $_sql ="SELECT $select 
                FROM $_tabela 
                $where";

        return $this->executar($_sql);
    }

}