<?php


/**
 * Validaciones de datos
 * @version v2022_2 
 * @author Martin Mata
 */

use App\Core\Acceso;
use Cabeca\Core\Respuesta;
use Cabeca\Factory\Utilidades\Validar;

set_include_path(get_include_path() . PATH_SEPARATOR . realpath(dirname(__FILE__) . "/../../../"));

include_once 'autoload.php';
@session_start();

$LOGIN = new Acceso();

define("VALIDAR_DATO_INVALIDO",400);
define("VALIDAR_DATO_VALIDO", 200);
switch ($_POST["validar"])
{
    case "nombre":
        $respuesta = Validar::Nombre($_POST["value"],2);
        if($respuesta !== true)
            echo Respuesta::resultado(VALIDAR_DATO_INVALIDO,0,$respuesta);
        else echo Respuesta::resultado(VALIDAR_DATO_VALIDO,1,"OK");
        break;
    case "usuario":
        $respuesta = Validar::Usuario($_POST["value"],4);
        if($respuesta !== true)
            echo Respuesta::resultado(VALIDAR_DATO_INVALIDO,0,$respuesta);
        else
        {            
            echo Respuesta::resultado(VALIDAR_DATO_INVALIDO,0,$respuesta);
        }
        break;
    case "email":
        $respuesta = Validar::Email($_POST["value"]);
        if($respuesta !== true)
            echo Respuesta::resultado(VALIDAR_DATO_INVALIDO,0,$respuesta);
            else echo Respuesta::resultado(VALIDAR_DATO_VALIDO,1,"OK");
            break;
    case "clave":
        $respuesta = Validar::Clave($_POST["value"]);
        if($respuesta !== true)
            echo Respuesta::resultado(VALIDAR_DATO_INVALIDO,0,$respuesta);
        else echo Respuesta::resultado(VALIDAR_DATO_VALIDO,1,"OK");
        break;
    case "telefono":
        $respuesta = Validar::Telefono($_POST["value"]);
        if($respuesta !== true)
            echo Respuesta::resultado(VALIDAR_DATO_INVALIDO,0,$respuesta);
            else echo Respuesta::resultado(VALIDAR_DATO_VALIDO,1,"OK");
            break;
    case "rfc":
        $respuesta = Validar::Rfc($_POST["value"]);
        if($respuesta !== true)
            echo Respuesta::resultado(VALIDAR_DATO_INVALIDO,0,$respuesta);
        else echo Respuesta::resultado(VALIDAR_DATO_VALIDO,1,"OK");
        break;
    case "codigo":
        $respuesta = Validar::Codigobarras($_POST["value"]);
        if($respuesta !== true)
            echo Respuesta::resultado(VALIDAR_DATO_INVALIDO,0,$respuesta);
        else echo Respuesta::resultado(VALIDAR_DATO_VALIDO,1,"OK");            
        break;
    case "numero":
        $respuesta = Validar::Numero($_POST["value"]);
        if($respuesta !== true)
            echo Respuesta::resultado(VALIDAR_DATO_INVALIDO,0,$respuesta);
            else echo Respuesta::resultado(VALIDAR_DATO_VALIDO,1,"OK");
            break;
    default:
        echo Respuesta::resultado(LOGIN_ERROR, 0, "No hay datos");
        break;
}

?>
