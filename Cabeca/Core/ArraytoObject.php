<?php

/**
 * @version v2020_2
 * @author Martin Mata
 */
namespace Cabeca\Core;
use Exception;

/**
 * Convierte array a object stdclass
 * @author Martin
 *
 */
class ArraytoObject
{
    /**
     * Converte lo contenido en post, get o un array a una clase
     * @param array $array
     * @param \stdClass $class
     */
    static function convertir($array, $class = null)
    {
        if(!is_object($class))
        {
            $class = new \stdClass();
        }
        try
        {
            foreach ($array as $key => $value)
            {
                if(is_array($value))
                {
                    $class->$key = self::convertir($value);                    
                }
                else $class->$key = $value;
            }
        }
        catch (Exception $e)
        {;}
        return $class;
    }
}