<?php
namespace Cabeca\Factory\Utilidades;


/**
 * COnvertir Array en StdClass
 * @author Marti
 *
 */
class StringHelper
{
    static function iniciales($nombre)
    {
        $partes = explode(" ", $nombre);
        if(count($partes)>=2)
            $iniciales = $partes[0][0]." ".$partes[1][0];
        else $iniciales = $partes[0][0];
        return strtoupper($iniciales);
    }
    
    static function procesaPath($path)
    {
        $crums = explode("/", $path);
        $html = '';
        foreach ($crums as $value)
        {
            
            if (strpos($value, ".") === false)
                $html .= " &frasl; ". strtoupper($value);
                elseif (strpos($value, "_") !== false)
                {
                    $current = explode("_", $value);
                    $html .= " &frasl; ". strtoupper($current[0]);
                }
                else
                {
                    $current = explode(".", $value);
                    $html .= " &frasl; ".strtoupper($current[0]);
                }
        }
        
        return $html;
    }
}

