<?php
namespace Cabeca;

abstract class Model extends PermisosBD implements BasedatosInterface
{

    public $tabla;
    public function __construct(string $base_datos = BD_GENERAL, string $tabla = "")
    {
        parent::__construct($base_datos);
        $this->tabla = $tabla;
        
    }
    
    /**
     * 
     * @param String $id
     * @param string $campo
     * @param int $usuario
     * @return int
     */
    public function borrar(String $id, string $campo, int $usuario = 0)
    {
        return $this->eliminar($this->tabla, $id, $campo, $usuario);
    }
    
    /**
     * 
     * @param string $id
     * @param string $campo
     * @param string $condicion
     * @return array|number|\stdClass
     */
    public function obtener(string $id, string $campo="", string $condicion = "0")
    {
        if($id == "0")
        {
            return $this->consulta("*", $this->tabla,$condicion);
        }
        else 
        {
            $respuesta = $this->consulta("*", $this->tabla, "$campo = '$id'");
            if(count($respuesta) > 0)
                return $respuesta[0];
            else return 0;
        }
    }
    
    /**
     * 
     * @param \stdClass $datos
     * @param string $id
     * @param string $campo
     * @param string $condicion
     * @param int $usuario
     * @return number
     */
    public function editar(\stdClass $datos, string $id, string $campo="", string $condicion = "0", int $usuario = 0)
    {
        return $this->modificar($this->tabla, $datos, $id, $campo, $usuario);
    }
    
    /**
     * 
     * @param \stdClass $datos
     * @return number
     */
    public function agregar(\stdClass $datos)
    {
        return $this->insertar($this->tabla, $datos);
    }
    
    /**
     * 
     * @param string $campo
     * @param string $valor
     * @return boolean
     */
    public function existe(string $campo, string $valor)
    {
        $respuesta = $this->consulta("*", $this->tabla, "$campo = '$valor'");
        if($respuesta !== 0 && count($respuesta) > 0)
            return true;
        else return false;
    }
    
    public function import(string $remote, string $update = "")
    {
        $sql = "INSERT into $this->tabla 
            SELECT * from $remote".".$this->tabla;";
        
        if ($resultado = $this->conexion->query("SHOW TABLES LIKE '$this->tabla'"))
        {
            if ($resultado->num_rows > 0 && strlen($sql) > 10) $this->conexion->multi_query($sql);
        }        
        if ($resultado = $this->conexion->query("SHOW TABLES LIKE '$this->tabla'"))
        {
            if ($resultado->num_rows > 0 && strlen($update) > 10) $this->conexion->multi_query($update);
        }   
    }
}

