<?php declare(strict_types=1);
require __DIR__ . '/vendor/autoload.php';

header('Content-Type: application/json');
header('Accept: application/json');
header('Cache-Control: no-cache');
header('X-Content-Type-Options: nosniff');


use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Validation\Validator;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Signer\Key\InMemory;
use SiCATan_api\Controllers;


//var_dump($_SERVER["REQUEST_URI"]);
//var_dump($_SERVER["REQUEST_METHOD"]);
//var_dump(explode("/",$_SERVER["REQUEST_URI"])[1]);
//var_dump("post ");
//var_dump(file_get_contents("php://input"));
//echo "json decode "; var_dump(json_decode(file_get_contents("php://input"),true));
//var_dump($_SERVER['HTTP_AUTHORIZATION']);
//var_dump(explode(" ",$_SERVER['HTTP_AUTHORIZATION'])[1]);
//echo "fim do trecho de var_dumps \n";

if(!in_array($_SERVER["REQUEST_METHOD"],["POST","GET","PUT"])){
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
    die();
}

$endpoint = explode("/",$_SERVER["REQUEST_URI"])[1];

if(!($endpoint == "login" XOR ($endpoint == "usuarios" && $_SERVER["REQUEST_METHOD"] == "POST" && !isset($_SERVER['HTTP_AUTHORIZATION'])))){
    if(!isset($_SERVER['HTTP_AUTHORIZATION']) || !isset(explode(" ",$_SERVER['HTTP_AUTHORIZATION'])[1])){
        http_response_code(401);
        echo json_encode(['error' => 'Token JWT ausente']);
        die();
    }
    $JWT = explode(" ",$_SERVER['HTTP_AUTHORIZATION'])[1];

    try {
        $JWT = (new Parser(new JoseEncoder()))->parse($JWT);

        $_valido = (new Validator())->validate($JWT, new SignedWith(new Sha256(),InMemory::base64Encoded(getenv("CHAVE_TOKEN"))));

    } catch (Exception $e) {
        //echo $e->getMessage();
    }

    if(empty($_valido)){
        http_response_code(401);
        echo json_encode(['error' => 'Token invalido']);
        die();
    }

    $token["id"] = $JWT->claims()->get('id');
    $token["nivel"] = $JWT->claims()->get('nivel');
    $token["email"] = $JWT->claims()->get('email');

    if((strlen($token["id"]) != 36) || (!in_array($token["nivel"],[1,2,3])) || (!filter_var($token["email"],FILTER_VALIDATE_EMAIL))){
        http_response_code(401);
        echo json_encode(['error' => 'Token invalido']);
        die();
    }
}

if(in_array($_SERVER["REQUEST_METHOD"],["POST","PUT"])){
    $_dados = json_decode(file_get_contents("php://input"),true);
    if(!is_array($_dados)){
        http_response_code(400);
        echo json_encode(['error' => 'Dados inadequados']);
        die();
    }
} else if($_SERVER["REQUEST_METHOD"] == "GET"){
    $_dados = count($_GET) > 0 ? $_GET : NULL;
    if(!empty(explode("/",$_SERVER["REQUEST_URI"])[2])){
        $_id = explode("/",$_SERVER["REQUEST_URI"])[2];
        if(str_contains($_id, "?")){
            $_id = explode("?", $_id)[0];
        }
    } else {
        if(str_contains($endpoint, "?")){
            $endpoint = explode("?", $endpoint)[0];
        }
        $_id = NULL;
    }
}

switch ($endpoint){
    case "usuarios":
        $Controller = new Controllers\UsuariosController();
        switch ($_SERVER["REQUEST_METHOD"]){
            case "POST":
                echo $Controller->criarConta($_dados,($token ?? NULL));
                die();
            case "PUT":
                echo $Controller->atualizaConta($_dados,$token);
                die();
            case "GET":
                echo $Controller->vizualizaConta($token,$_id,$_dados);
                die();
        }
        die();
    case "login":
        $Controller = new Controllers\UsuariosController();
        if($_SERVER["REQUEST_METHOD"] == "POST")
            echo $Controller->loginConta($_dados);
        die();
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Endpoint não encontrado']);
        die();
}

// /usuarios
// /login
