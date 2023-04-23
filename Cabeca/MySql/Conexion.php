<?php
namespace Cabeca\MySql;

use Cabeca\Config;

abstract class Conexion
{    
    public $conexion;
    public $base_datos;
    public function __construct(string $base_datos)
    {
        $this->conexion = new \mysqli(Config::DB_HOST, Config::DB_USER, Config::DB_PASSWORD, $base_datos);
        $this->base_datos = $base_datos;
    }
    
    /**
     * 
     * @param string $base_datos
     * @return bool
     */
    protected function estaConectado($base_datos = null):bool
    {
        if($base_datos !== null)
            $this->base_datos = $base_datos;
        return $this->conexion->real_connect(Config::DB_HOST, Config::DB_USER, Config::DB_PASSWORD, $this->base_datos);
    }
    
    /**
     *
     * @param string $base_datos
     * @return bool;
     */
    protected function selecionaBD(string $base_datos):bool
    {
        $this->base_datos = $base_datos;
        return $this->conexion->select_db($base_datos);
    }
    
    /**
     * 
     * @param string $nombre
     * @return bool|\mysqli_result
     */
    protected function crearBD(string $nombre)
    {
        $nombre = $this->conexion->real_escape_string($nombre);
        $query = "CREATE DATABASE IF NOT EXISTS $nombre DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci";
        return $this->conexion->query($query);
    }
}

