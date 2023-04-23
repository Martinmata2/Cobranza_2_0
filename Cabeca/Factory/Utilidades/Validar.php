<?php
namespace Cabeca\Factory\Utilidades;


class Validar
{
    /**
     * 
     * @param string $nombre
     * @param int $minlength
     * @return string|boolean
     */
    public static function Nombre($nombre, $minlength = 4)
    {
        $nombre = utf8_encode($nombre); //convertir � � y otros para examinar
        $nombre = preg_replace("/\s+/", "", $nombre); // remover espacios 
        if(strlen($nombre) <= $minlength)
            return "Contiene pocos caracteres";
        $pattern = '/^[A-Za-z\x{00C0}-\x{00FF}][A-Za-z\x{00C0}-\x{00FF}\'\-]+([\ A-Za-z\x{00C0}-\x{00FF}][A-Za-z\x{00C0}-\x{00FF}\'\-]+)*/u';
        if (preg_match($pattern, $nombre)) 
        {
            return true;
        }
        return "Contiene caracteres no admitidos";          
    }
    
    /**
     * 
     * @param string $nombre
     * @param int $minlength
     * @return string|boolean
     */
    public static function Usuario($nombre, $minlength = 4)
    {
        if(strlen($nombre) < $minlength)
            return "Contiene pocos caracteres";
        if (ctype_alnum($nombre))
        {
            if(strlen($nombre) >= $minlength)
                return true;
            else
                return "Contiene caracteres no admitidos";
        }       
    }
    
    /**
     * 
     * @param string $clave
     * @return boolean|string
     */
    public static function Clave($clave)
    {
        if (preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{4,12}$/', $clave) == 1)
        {
            return true;
        }
        else
        {
            return "Contiene caracteres no admitidos";
        }
    }
    
    /**
     * 
     * @param string $email
     * @return string|boolean
     */
    public static function Email($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false)
        {
            return "Email mal formado o invalido";
        }
        else
            return true;
    }
    
    /**
     * 
     * @param string $celular
     * @return string|boolean
     */
    public static function Telefono($celular)
    {
        // Filtrar numeros
        $filtro = filter_var($celular, FILTER_SANITIZE_NUMBER_INT);
        // Remover "-"
        $numeros = str_replace("-", "", $filtro);
        // Checar cantidad de numeros
        if (strlen($numeros) < 10 || strlen($numeros) > 14)
        {
            return "Numero invalido";
        }
        else
            return true;
    }
    
    public static function Rfc($rfc)
    {
        $regex = '/^[A-ZÑ]{3,4}([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])([A-Z\d]{3})$/';
        if(preg_match($regex, $rfc) === 1)
            return true;
        else return "RFC invalido";
    }
    public static function Codigobarras($codigo)
    {
        $codigo = preg_replace("/\s+/", "", $codigo);
        if(ctype_alnum($codigo))
            return true;
        elseif (ctype_digit($codigo))
            return true;
        elseif (ctype_alpha($codigo))
            return true;
        else return "Codigo invalido";
        //$token = new Acceso();
        //return $token->crearllave(64);
    }
    public static function Numero($codigo)
    {
        if(is_numeric($codigo))
            return true;
        else return "No es numerico";
    }
    
}

