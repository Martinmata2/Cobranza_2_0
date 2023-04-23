<?php
namespace App\Controllers;
use App\Modelos\Clientes;
use Cabeca\Controller;
use App\Modelos\Generico;
use Cabeca\Core\ArraytoObject;

class Simple extends Controller
{
    
	
    private $Tabla;
    private $Key;
    /**
     */
    public function __construct($base_datos, $tabla, $key) 
    {
        $CLASS = new Generico($base_datos, $tabla); 
        parent::__construct($CLASS);
        $this->Tabla = $tabla;
        $this->Key = $key;
    }

    /**
	 * @param mixed $metodo
	 * @param mixed $arguments
	 * @return mixed
	 */
	public function run($metodo, $arguments) 
    {
        if(method_exists($this,$metodo))
        {            
            if(is_array($arguments["data"]))
                $datos = $arguments["data"];
            else
                parse_str($arguments["data"], $datos);
            if(parent::before($arguments["token"],$datos))
            {
                $respuesta = $this->$metodo($datos);
                parent::after($respuesta,$this->class->mensaje);
            }
            echo $this->render();
        }
        else
        {
            $this->status = CONTROLLER_DATOS_INVALIDOS;
            $this->respuesta = 0;
            $this->mensaje = "La peticion no puede ser procesada, Datos Invalidados";   
            echo $this->render();
        }
	}
	
	private function Agregar($datos)
	{
	    $key = $this->Key;
        $datos = ArraytoObject::convertir($datos);        
	    if(isset($datos->$key) && $this->class->existe($key, $datos->$key))
	    {
	        $this->respuesta = $this->class->editar($datos, $datos->$key, $key);
	    }
	    else
	    {
	        $this->respuesta = $this->class->agregar($datos);
	    }
	    $this->mensaje = $this->class->mensaje;
	    return $this->respuesta;
	}	

    private function Lista($datos)
    {
        $clase = "App\\Modelos\\".ucwords($this->Tabla);
        $LISTA = new $clase($_SESSION["USR_BD"]);
        $this->respuesta = $LISTA->listaSelect(0);
        $this->mensaje = $LISTA->mensaje;
        return $this->respuesta;
    }

}
