<?php
namespace Cabeca;

use Cabeca\Core\ArraytoObject;
use stdClass;

define("CONTROLLER_ELIMINADO", 1);
define("CONTROLLER_ACTIVO", 1);
define("CONTROLLER_INACTIVO", 0);
define("CONTROLLER_SUCCESS", 200);
define("CONTROLLER_ERROR", 400);
define("CONTROLLER_DATOS_VALIDOS",20);
define("CONTROLLER_DATOS_INVALIDOS",40);
abstract class Controller
{
    
    protected $status;
    protected $respuesta;
    protected $mensaje;

    protected $class;
    public function __construct($class)
    {        
        if(is_object($class))
            $this->class = $class;
        else
        {
            $this->class = new stdClass();
            $this->status = CONTROLLER_DATOS_INVALIDOS;
            $this->respuesta = 0;
            $this->mensaje = "La peticion no puede ser procesada, Datos Invalidados";   
        }        
    }
    protected function render()
    {
        return json_encode(array("status" => $this->status,"respuesta" => $this->respuesta,"mensaje" => $this->mensaje));
    }
    
    abstract function run($metodo, $arguments);
    
    protected function before($token, &$data)
    {       
        if( isset($this->status) &&  $this->status == CONTROLLER_DATOS_INVALIDOS)
            return false;
        if($token === $_SESSION["CSRF"])
        {
            if(isset($data["CSRF"]))
                unset($data["CSRF"]);
            if(is_iterable($data))
            {
                $data = ArraytoObject::convertir($data);
                foreach ($data as $key => $value) 
                {
                    if(!is_object($value))                                        
                        $data->$key = $this->class->conexion->real_escape_string($value); 
                }
            }
            return true;
        }
        else
        {
            $this->status = CONTROLLER_DATOS_INVALIDOS;
            return false;
        }        
    }
    
    protected function after($respuesta,$mensaje)
    {
        if(is_array($respuesta))
        {
            if(count($respuesta) > 0)
            {
                $this->status = CONTROLLER_SUCCESS;
                $this->respuesta = ArraytoObject::convertir($respuesta);
            }
            else 
            {
                $this->status = CONTROLLER_ERROR;
                $this->respuesta = 0;
            }
        }
        elseif($respuesta === 0)
        {
            $this->status = CONTROLLER_ERROR;
            $this->respuesta = 0;
        }
        else
        {
            $this->status = CONTROLLER_SUCCESS;
            $this->respuesta = $respuesta;
        }
        if(is_array($mensaje))
        {
            if(count($mensaje) > 0)
                $this->mensaje = ArraytoObject::convertir($mensaje);
            else $this->mensaje = 0;
        }
        else
        {
            $this->mensaje = $mensaje;
        }
        
    }
}

