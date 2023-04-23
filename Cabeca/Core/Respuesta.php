<?php
namespace Cabeca\Core;

/**
 * Formateo de respuestas ajax
 * @author Marti
 *
 */
class Respuesta
{
    
    static function resultado($status, $respuesta = 0, $mensaje = "")
    {
        return json_encode(array("status" => $status,"respuesta" => $respuesta,"mensaje" => $mensaje));
    }
}

