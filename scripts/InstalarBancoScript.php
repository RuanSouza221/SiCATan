<?php declare(strict_types=1);

require_once "../models/ConexaoBanco.php";
require_once "../models/InstalarBanco.php";

use SiCATan_api\models\InstalarBanco;

$InstalarBanco = new InstalarBanco();

$NivelAcesso = $InstalarBanco->criarNivelAcesso();
if(is_array($NivelAcesso) && count($NivelAcesso) === 3){
    echo "Tabela NivelAcesso Criada com sucesso \n";
} else {
    die("Tabela NivelAcesso Não Criada com sucesso \n");
}
if($InstalarBanco->criarOrganizacao()){
    echo "Tabela Organizacao Criada com sucesso \n";
} else {
    die("Tabela Organizacao Não Criada com sucesso \n");
}
if($InstalarBanco->criarUsuarios()){
    echo "Tabela Usuarios Criada com sucesso \n";
} else {
    die("Tabela Usuarios Não Criada com sucesso \n");
}
if($InstalarBanco->criarUsuarioLog()){
    echo "Tabela Log_Usuarios Criada com sucesso \n";
} else {
    die("Tabela Log_Usuarios Não Criada com sucesso \n");
}
if($InstalarBanco->criarOrganizacaoLog()){
    echo "Tabela Log_Organizacao Criada com sucesso \n";
} else {
    die("Tabela Log_Organizacao Não Criada com sucesso \n");
}
if($InstalarBanco->criarUsuarioRequisicao()){
    echo "Tabela Log_Requisicao Criada com sucesso \n";
} else {
    die("Tabela Log_Requisicao Não Criada com sucesso \n");
}
if($InstalarBanco->criarAtivos()){
    echo "Tabela Ativos Criada com sucesso \n";
} else {
    die("Tabela Ativos Não Criada com sucesso \n");
}
if($InstalarBanco->criarLogAtivo()){
    echo "Tabela Log_Ativos Criada com sucesso \n";
} else {
    die("Tabela Log_Ativos Não Criada com sucesso \n");
}

die();