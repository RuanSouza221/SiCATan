<?php declare(strict_types=1);
namespace models;

use PHPUnit\Framework\TestCase;
use SiCATan_api\Models\ConexaoBanco;

final class ConexaoBancoTest extends TestCase
{

    public function testInstanciaClasse(): void
    {

        $ConexaoBanco = new ConexaoBanco();

        $this->assertInstanceOf(ConexaoBanco::class, $ConexaoBanco);

    }

    public function testConexao(): void
    {
        $ConexaoBanco = new ConexaoBanco();

        $consulta = $ConexaoBanco->executar("select now() as timestamp;");

        $this->assertIsArray($consulta);
        $this->assertSame("timestamp", array_keys($consulta[0])[0]);

        $consulta = $ConexaoBanco->executar("select DATABASE() as base;");

        $this->assertIsArray($consulta);
        $this->assertSame("base", array_keys($consulta[0])[0]);
        $this->assertSame(strtolower(getenv("DATABASE_NAME")), $consulta[0]["base"]);
    }

    public function testExecutarCreateTable(): void
    {
        $ConexaoBanco = new ConexaoBanco();

        $sql = "DROP TABLE IF EXISTS TabelaTeste;";

        $consulta = $ConexaoBanco->executar($sql);

        $this->assertIsNotArray($consulta);
        $this->assertTrue($consulta);

        $sql = "CREATE TABLE TabelaTeste (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    colunainicial VARCHAR(50)
                );";

        $consulta = $ConexaoBanco->executar($sql);

        $this->assertIsNotArray($consulta);
        $this->assertTrue($consulta);

    }

    public function testExecutarAlterTable(): void
    {
        $ConexaoBanco = new ConexaoBanco();

        $sql = "ALTER TABLE TabelaTeste ADD descricao varchar(60) NOT NULL;";

        $consulta = $ConexaoBanco->executar($sql);

        $this->assertIsNotArray($consulta);
        $this->assertTrue($consulta);
    }

    public function testExecutarInsert(): void
    {
        $ConexaoBanco = new ConexaoBanco();

        $sql = "INSERT INTO TabelaTeste (descricao)
                VALUES (:descricao);";

        $ConexaoBanco->BindValor["descricao"] = "descricaoTeste1";

        $consulta = $ConexaoBanco->executar($sql);

        $this->assertIsNotArray($consulta);
        $this->assertSame(1,$consulta);

        $ConexaoBanco->BindValor["descricao"] = "descricaoTeste_2";

        $consulta = $ConexaoBanco->executar($sql);

        $this->assertIsNotArray($consulta);
        $this->assertSame(2,$consulta);
    }

    public function testExecutarUpdate(): void
    {
        $ConexaoBanco = new ConexaoBanco();

        $sql = "UPDATE TabelaTeste 
                SET descricao = :descricao 
                WHERE id = :id;";

        $ConexaoBanco->BindValor["descricao"] = "descricaoTeste_1";
        $ConexaoBanco->BindValor["id"] = "1";

        $consulta = $ConexaoBanco->executar($sql);

        $this->assertIsNotArray($consulta);
        $this->assertSame(1,$consulta);

    }

    public function testExecutarSelect(): void
    {
        $ConexaoBanco = new ConexaoBanco();

        $sql = "SELECT * FROM TabelaTeste;";

        $consulta = $ConexaoBanco->executar($sql);

        $this->assertIsArray($consulta);
        $this->assertSame("descricaoTeste_1", $consulta[0]["descricao"]);
        $this->assertSame("descricaoTeste_2", $consulta[1]["descricao"]);
        $this->assertSame(2,count($consulta));

    }

    public function testExecutarDelete(): void
    {
        $ConexaoBanco = new ConexaoBanco();

        $sql = "DELETE FROM TabelaTeste WHERE id=:id";

        $ConexaoBanco->BindValor["id"] = "1";

        $consulta = $ConexaoBanco->executar($sql);

        $this->assertIsNotArray($consulta);
        $this->assertSame(1,$consulta);

        $sql = "SELECT * FROM TabelaTeste;";

        $consulta = $ConexaoBanco->executar($sql);

        $this->assertIsArray($consulta);
        $this->assertSame("descricaoTeste_2", $consulta[0]["descricao"]);
        $this->assertSame(1,count($consulta));

    }

    public function testConstroiBind(): void
    {
        $ConexaoBanco = new ConexaoBanco();

        $ConexaoBanco->constroiBind("descricao","testedescricao_5");
        $this->assertSame("testedescricao_5", $ConexaoBanco->BindValor["descricao"]);
        $this->assertSame("descricao = :descricao",$ConexaoBanco->BindSql["descricao"]);

        $ConexaoBanco->constroiBind("id",4);
        $this->assertSame(4, $ConexaoBanco->BindValor["id"]);
        $this->assertSame("id = :id",$ConexaoBanco->BindSql["id"]);

        $ConexaoBanco->constroiBind("id",4,">");
        $this->assertSame(4, $ConexaoBanco->BindValor["id"]);
        $this->assertSame("id > :id",$ConexaoBanco->BindSql["id"]);

        $ConexaoBanco->constroiBind("id",array(2,4),"BETWEEN");
        $this->assertSame(2,$ConexaoBanco->BindValor["id_1"]);
        $this->assertSame(4,$ConexaoBanco->BindValor["id_2"]);
        $this->assertSame("id BETWEEN :id_1 AND :id_2", $ConexaoBanco->BindSql["id"]);

        $ConexaoBanco->constroiBind("id",array(3,2,1),"IN");
        $this->assertSame(3,$ConexaoBanco->BindValor["id_0"]);
        $this->assertSame(2,$ConexaoBanco->BindValor["id_1"]);
        $this->assertSame(1,$ConexaoBanco->BindValor["id_2"]);
        $this->assertSame("id IN (:id_0,:id_1,:id_2)",$ConexaoBanco->BindSql["id"]);

        $ConexaoBanco->constroiBind("descricao","testedescricao_5","LIKE");
        $this->assertSame("%testedescricao_5%", $ConexaoBanco->BindValor["descricao"]);
        $this->assertSame("descricao LIKE :descricao", $ConexaoBanco->BindSql["descricao"]);

    }

    public function testConstroiWhere(): void
    {
        $ConexaoBanco = new ConexaoBanco();

        $ConexaoBanco->constroiBind("descricao","testedescricao_5");
        $ConexaoBanco->constroiBind("id","5");

        $Where = $ConexaoBanco->constroiWhere();

        $this->assertSame("descricao = :descricao AND id = :id",$Where);
    }

    public function testConstroiSet(): void
    {
        $ConexaoBanco = new ConexaoBanco();

        $ConexaoBanco->constroiBind("descricao","testedescricao_5");
        $ConexaoBanco->constroiBind("id","5");

        $Set = $ConexaoBanco->constroiSet();

        $this->assertSame("descricao = :descricao, id = :id", $Set);

    }

    public function testInsertSimples(): void
    {
        $ConexaoBanco = new ConexaoBanco();

        $insert["descricao"] = "descricaoTeste_3";

        $consulta = $ConexaoBanco->insertSimples("TabelaTeste",$insert);

        $this->assertIsNotArray($consulta);
        $this->assertSame(3,$consulta);

        $insert["descricao"] = "descricaoTeste4";

        $consulta = $ConexaoBanco->insertSimples("TabelaTeste",$insert);

        $this->assertIsNotArray($consulta);
        $this->assertIsInt($consulta);
        $this->assertSame(4,$consulta);
    }

    public function testUpdateSimples(): void
    {
        $ConexaoBanco = new ConexaoBanco();

        $update["descricao"] = "descricaoTeste_4";
        $where["id"] = "4";

        $consulta = $ConexaoBanco->updateSimples("TabelaTeste",$update,$where);

        $this->assertIsNotArray($consulta);
        $this->assertIsInt($consulta);
        $this->assertSame(1,$consulta);
    }

    public function testSelectSimples(): void
    {
        $ConexaoBanco = new ConexaoBanco();

        $colunas[] = "id";
        $colunas[] = "descricao";
        $where["id"] = "4";
        $where["descricao"] = "descricaoTeste_4";

        $consulta = $ConexaoBanco->selectSimples("TabelaTeste",$where,$colunas);

        $this->assertIsArray($consulta);
        $this->assertSame(1,count($consulta));
        $this->assertSame(4,$consulta[0]["id"]);
        $this->assertSame("descricaoTeste_4",$consulta[0]["descricao"]);
    }
}