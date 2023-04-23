<?php
namespace App\Controllers;
use Cabeca\Controller;
use App\Modelos\<modelo>;

class <controller> extends Controller
{
    
	

    /**
     */
    public function __construct() 
    {
        $CLASS = new <modelo>();
        parent::__construct($CLASS);
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

}
