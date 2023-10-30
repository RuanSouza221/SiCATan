<?php
namespace SiCATan_api\Models;

final class InstalarBanco extends ConexaoBanco
{
    public function criarNivelAcesso(): array|false
    {
        $_sql = "CREATE TABLE Nivel_Acesso (
                    id INT(11)  PRIMARY KEY,
                    descricao   VARCHAR(50) 
                ) ENGINE = InnoDB;";

        $retorno = $this->executar($_sql);

        if(!$retorno){
            return $retorno;
        } else {
            unset($retorno);
            $retorno[0] = $this->insertSimples("Nivel_Acesso",["id" => "1", "descricao" => "Dono"]);
            $retorno[1] = $this->insertSimples("Nivel_Acesso",["id" => "2", "descricao" => "Administrador"]);
            $retorno[2] = $this->insertSimples("Nivel_Acesso",["id" => "3", "descricao" => "Normal"]);
            return $retorno;
        }
    }

    public function criarOrganizacao(): bool
    {
        $_sql = "CREATE TABLE Organizacao (
                    id CHAR(36) PRIMARY KEY UNIQUE,
                    descricao   VARCHAR(50) UNIQUE NOT NULL,
                    data_cad    DATETIME NOT NULL,
                    inativo     BOOL NOT NULL
                ) ENGINE = InnoDB;";

        return $this->executar($_sql);
    }

    public function criarUsuarios()
    {
        $_sql = "CREATE TABLE Usuarios (
                    id          CHAR(36) PRIMARY KEY UNIQUE,
                    nome        VARCHAR(60) NOT NULL,
                    email       VARCHAR(60) UNIQUE NOT NULL,
                    senha       VARCHAR(255) NOT NULL,
                    data_cad    DATETIME NOT NULL,
                    nivel       INT(11) NOT NULL,
                    organizacao CHAR(36),
                    inativo     BOOL NOT NULL,
                    FOREIGN KEY (organizacao) REFERENCES Organizacao(id) ON DELETE SET NULL,
                    FOREIGN KEY (nivel) REFERENCES Nivel_Acesso(id)
                ) ENGINE = InnoDB;";

        return $this->executar($_sql);
    }

    public function criarUsuarioLog()
    {
        $_sql = "CREATE TABLE Log_Usuarios (
                    id              CHAR(36) PRIMARY KEY UNIQUE,
                    id_usuario      CHAR(36) NOT NULL,
                    descricao_mod   VARCHAR(60) NOT NULL,
                    data_mod        DATETIME NOT NULL,
                    usuario_mod     CHAR(36) NOT NULL,
                    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id),
                    FOREIGN KEY (usuario_mod) REFERENCES Usuarios(id)
                ) ENGINE = InnoDB;";

        return $this->executar($_sql);
    }

    public function criarOrganizacaoLog()
    {
        $_sql = "CREATE TABLE Log_Organizacao (
                    id              CHAR(36) PRIMARY KEY UNIQUE,
                    id_organizacao  CHAR(36) NOT NULL,
                    descricao_mod   VARCHAR(60) NOT NULL,
                    data_mod        DATETIME NOT NULL,
                    usuario_mod     CHAR(36) NOT NULL,
                    FOREIGN KEY (id_organizacao) REFERENCES Organizacao(id),
                    FOREIGN KEY (usuario_mod) REFERENCES Usuarios(id)
                ) ENGINE = InnoDB;";

        return $this->executar($_sql);
    }

    public function criarUsuarioRequisicao()
    {
        $_sql = "CREATE TABLE Log_Requisicao (
                    id          CHAR(36) PRIMARY KEY UNIQUE,
                    token       VARCHAR(4096) NOT NULL,
                    endpoint    VARCHAR(200) NOT NULL,
                    data_cad    DATETIME NOT NULL,
                    usuario_cad CHAR(36) NOT NULL,
                    FOREIGN KEY (usuario_cad) REFERENCES Usuarios(id)
                ) ENGINE = InnoDB;";

        return $this->executar($_sql);
    }

    public function criarAtivos()
    {
        $_sql = "CREATE TABLE Ativos (
                    id          CHAR(36) PRIMARY KEY UNIQUE,
                    descricao   VARCHAR(60) NOT NULL,
                    data_cad    DATETIME NOT NULL,
                    categoria   VARCHAR(60) NOT NULL,
                    organizacao CHAR(36) NOT NULL,
                    inativo     BOOL NOT NULL,
                    FOREIGN KEY (organizacao) REFERENCES Organizacao(id)
                ) ENGINE = InnoDB;";

        return $this->executar($_sql);
    }

    public function criarLogAtivo()
    {
        $_sql = "CREATE TABLE Log_Ativos (
                    id              CHAR(36) PRIMARY KEY UNIQUE,
                    id_ativo_mod    CHAR(36) NOT NULL,
                    descricao_mod   VARCHAR(60) NOT NULL,
                    data_mod        DATETIME NOT NULL,
                    usuario_mod     CHAR(36) NOT NULL,
                    FOREIGN KEY (id_ativo_mod) REFERENCES Ativos(id),
                    FOREIGN KEY (usuario_mod) REFERENCES Usuarios(id)
                ) ENGINE = InnoDB;";

        return $this->executar($_sql);
    }

    public function criarAtivoAmbiente()
    {
        $_sql = "CREATE TABLE Ativo_Ambiente (
                    id                  CHAR(36) PRIMARY KEY UNIQUE,
                    id_ativo            CHAR(36) NOT NULL,
                    id_ativo_ambiente   CHAR(36) NOT NULL,
                    data_cad            DATETIME NOT NULL,
                    inativo             BOOL NOT NULL,
                    FOREIGN KEY (id_ativo) REFERENCES Ativos(id),
                    FOREIGN KEY (id_ativo_ambiente) REFERENCES Ativos(id),
                    UNIQUE (id_ativo,id_ativo_ambiente)
                ) ENGINE = InnoDB;";

        return $this->executar($_sql);
    }

    public function criarLogAtivoAmbiente()
    {
        $_sql = "CREATE TABLE Log_Ativo_Ambiente (
                    id                      CHAR(36) PRIMARY KEY UNIQUE,
                    id_ativo_ambiente_mod   CHAR(36) NOT NULL,
                    descricao_mod           VARCHAR(60) NOT NULL,
                    data_mod                DATETIME NOT NULL,
                    usuario_mod             CHAR(36) NOT NULL,
                    FOREIGN KEY (id_ativo_ambiente_mod) REFERENCES Ativo_Ambiente(id),
                    FOREIGN KEY (usuario_mod) REFERENCES Usuarios(id)
                ) ENGINE = InnoDB;";

        return $this->executar($_sql);
    }

}