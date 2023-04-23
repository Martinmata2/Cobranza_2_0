<?php

/**
 * Verifica que el campo sea unico
 * @version v2023_2
 * @author Martin Mata
 */
use App\Modelos\Clientes;

use App\Modelos\Productos;
use App\Modelos\Usuario;
use App\Modelos\Ventas;
use Cabeca\Core\Respuesta;

set_include_path(get_include_path() . PATH_SEPARATOR . realpath(dirname(__FILE__) . "/../../../"));

include_once 'autoload.php';
@session_start();

define("VALIDAR_DATO_INVALIDO", 400);
define("VALIDAR_DATO_VALIDO", 200);
switch ($_POST["validar"])
{
    case "unico":
        switch ($_POST["tipo"])
        {
            case "Usuario":
                $USUARIO = new Usuario();
                $respuesta = $USUARIO->existe($_POST["campo"], $_POST["value"]);
                if ($respuesta === true)
                    echo Respuesta::resultado(VALIDAR_DATO_INVALIDO, 0, $_POST["campo"] . " ya existe");
                else
                    echo Respuesta::resultado(VALIDAR_DATO_VALIDO, 1, "OK");
                break;

            case "Producto":
                $PRODUCTO = new Productos($_SESSION["USR_BD"]);
                $respuesta = $PRODUCTO->existe($_POST["campo"], $_POST["value"]);
                if ($respuesta === true)
                    echo Respuesta::resultado(VALIDAR_DATO_INVALIDO, 0, $_POST["campo"] . " ya existe");
                else
                    echo Respuesta::resultado(VALIDAR_DATO_VALIDO, 1, "OK");
                break;
            case "Cliente":
                $CLIENTE = new Clientes($_SESSION["USR_BD"]);
                $respuesta = $CLIENTE->existe($_POST["campo"], $_POST["value"]);
                if ($respuesta === true)
                    echo Respuesta::resultado(VALIDAR_DATO_INVALIDO, 0, $_POST["campo"] . " ya existe");
                else
                    echo Respuesta::resultado(VALIDAR_DATO_VALIDO, 1, "OK");
                break;
            case "Venta":
                $VENTA = new Ventas($_SESSION["USR_BD"]);
                $respuesta = $VENTA->existe($_POST["campo"], $_POST["value"]);
                if ($respuesta === true)
                    echo Respuesta::resultado(VALIDAR_DATO_INVALIDO, 0, $_POST["campo"] . " ya existe");
                else
                    echo Respuesta::resultado(VALIDAR_DATO_VALIDO, 1, "OK");
                break;
            default:
                echo Respuesta::resultado(VALIDAR_DATO_INVALIDO, 0, "No hay datos");
                break;
        }
        break;
    default:
        echo Respuesta::resultado(VALIDAR_DATO_INVALIDO, 0, "No hay datos");
        break;
}