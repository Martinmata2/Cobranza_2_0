<?php
namespace App\Modelos;

use Cabeca\Model;
use Cabeca\Factory\Utilidades\Validar;
use App\Modelos\Datos\UsuarioD;
use Cabeca\BasedatosInterface;
use Cabeca\Interfaces\GridInterface;
use Cabeca\Interfaces\ListaInterface;

//Definiciones para estandarizar valores
define("USU_ELIMINADO", 1);
define("USU_ACTIVO", 1);
define("USU_INACTIVO", 0);
define("USU_SUCCESS", 200);
define("USU_ERROR", 400);
define("USU_DATOS_VALIDOS",200);
define("USU_DATOS_INVALIDOS",400);
class Usuario extends Model implements BasedatosInterface, GridInterface, ListaInterface
{
   
      
    /**
     *
     * @var array
     */
    public $mensaje;
    /**
     *
     * @var UsuarioD
     */
    public $data;
    
    public function __construct( $base_datos = BD_GENERAL)
    {
           
        $this->base_datos = $base_datos;
        $this->mensaje = array();        
        parent::__construct($base_datos, "usuarios");
        
        //Inicar tabla
        $this->create();
        $this->update();
    }

    public function borrar(string $id, string $campo="UsuID",  int $usuario = 0)
    {
        if($this->isAdmin($_SESSION["USR_ROL"]))
           return  parent::borrar($id,$campo,$usuario);
        else return 0;
    }

    public function validar()
    {
        $this->mensaje = array();
        //nombre
        $validacion = Validar::Nombre(trim($this->data->UsuNombre));
        if($validacion !== true)
        {
            $this->mensaje["UsuNombre"] = $validacion;
        }
        //nombre
        $validacion = $this->existe("UsuNombre",trim($this->data->UsuNombre));
        if($validacion)
        {
            $this->mensaje["UsuNombre"] = "Existe otro Usuario con este nombre";
        }
        if(count($this->mensaje) > 0)
        {
            $this->mensaje["status"] = USU_DATOS_INVALIDOS;
            return $this->mensaje;
        }
        else
        {
            $this->mensaje["status"] = USU_DATOS_VALIDOS;
            return true;
        }             
    }   

    public function agregar($datos)
    {
        if($this->isAdmin($_SESSION["USR_ROL"]))
        {
            $this->data = new UsuarioD($datos);
            if($this->validar() === true)
            {               
                return parent::agregar($datos);
            }
            else return 0;
        }
        else return 0;
    }      
    
    private function create()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `usuarios` (
        `UsuID` int(11) NOT NULL AUTO_INCREMENT,
        `UsuEmpresa` tinytext COLLATE utf8_spanish2_ci NOT NULL,
        `UsuRol` int(11) NOT NULL,
        `UsuUsuario` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
        `UsuClave` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
        `UsuNombre` varchar(70) COLLATE utf8_spanish2_ci NOT NULL,
        `UsuEmail` tinytext COLLATE utf8_spanish2_ci NOT NULL,
        `UsuActivo` tinyint(1) NOT NULL,
        `UsuToken` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
        `UsuBD` varchar(40) COLLATE utf8_spanish2_ci NOT NULL,
        `updated` tinyint(1) NOT NULL,
        `UsuPromotor` int(11) NOT NULL,
        `UsuArea` tinytext COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Municipios separados por comas',
        `UsuCliente` int(11) NOT NULL,
        `UsuSesion` varchar(45) COLLATE utf8_spanish2_ci NOT NULL COMMENT '0 funeraria, 1 generico',
        `UsuUsuarioPAC` varchar(40) COLLATE utf8_spanish2_ci NOT NULL COMMENT 'ya-facturo',
        `UsuClavePAC` varchar(40) COLLATE utf8_spanish2_ci NOT NULL COMMENT 'ya-facturo',
        PRIMARY KEY (`UsuID`),
        UNIQUE KEY `UsuUsuario` (`UsuUsuario`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci";
        
        if ($resultado = $this->conexion->query("SHOW TABLES LIKE '$this->tabla'"))
        {
            if ($resultado->num_rows == 0) $this->conexion->multi_query($sql);
        }               
    }
        
    private function update()
    {
        $update = "";        
        if ($resultado = $this->conexion->query("SHOW TABLES LIKE '$this->tabla'"))
        {
            if ($resultado->num_rows > 0 && strlen($update) > 10) $this->conexion->multi_query($update);
        }   
    }
        
	/**
	 * Devuelve los campos del datagrid
	 *
	 * @param array|null $arguments
	 * @return array
	 */
	public function grid($arguments = null) 
    {
        return array(
            array("title"=> "ID",               "name"=>"UsuID",                "editable"=>false,              "width"=>"40",              "hidden"=>true,                 "hidedlg"=>true,            "export"=>false),
            array("title"=>"Nombre",  "name"=>"UsuNombre",            "editable"=>true,               "width"=>"100",             "align"=>"center",
                "link"=>$arguments["link"],     "linkoptions"=>"class='box'"),
            array("title"=>"Rol",               "name"=>"UsuRol",               "editable"=>true,               "width"=>"40",              "align"=>"center",
                "editoptions"=>array("value"=>$arguments["rol"]),               "edittype"=>"select",           "op"=>"eq",                 "formatter"=>"select",
                "searchoptions"=>array("value"=>"0:Selecciona;".$arguments["rol"]),             "stype"=> "select" ),           
            array("title"=>"Activo",            "name"=>"UsuActivo",            "editable"=>true,               "width"=>"20",              "align"=>"left",
                "formatter"=>"checkbox",        "edittype"=>"checkbox",         "editoptions"=>array("value"=>"1:0")),
            array("title"=>"Permisos",          "name"=>"permisos",             "editable"=>false,              "width"=>"20",              "align"=>"left",                "search"=>false,
                "default"=>$arguments["permiso"])
        );
	}
	
	/**
	 * Devuelve la forma a usar para agregar datos
	 *
	 * @param mixed $arguments
	 * @return string
	 */
	public function forma($arguments = null) 
    {

        if ($arguments["editar"] != true)
            $disable = "disabled";
        else
            $disable = "";
        return " <div id='usuarioPanel'>
        	<form id='usuarioForm'>
        		<div class='row gx-5'>
        			<div class='col-lg-4 col-md-6'>
        				<input type='hidden' id='UsuID' name='UsuID' /> <input id='CSRF' type='hidden'
        					value='".$_SESSION["CSRF"]."' />        				                    
        				<div class='form-floating mb-3'>
        					<input class='form-control border-danger valida' id='UsuNombre'
        						name='UsuNombre' type='text' data-type='nombre' data-unico='true' data-validar='Usuario' /> 
        					<label for='UsuNombre'> Nombre Completo</label>					
        				</div>
                    </div>
                    <div class='col-lg-4 col-md-6'>
                        <div class='form-floating mb-3'>
        					<input class='form-control' id='UsuEmpresa'
        						name='UsuEmpresa' type='text' value='".$arguments["empresa"]."' $disable /> 
        					<label for='UsuEmpresa'> Empresa</label>					
        				</div>
                    </div>
                    <div class='col-lg-4 col-md-6'>
                        <div class='form-floating mb-3'>
        					<input class='form-control' id='UsuBD'
        						name='UsuBD' type='text' value='".$arguments["bd"]."' $disable /> 
        					<label for='UsuBD'> Base de datos</label>					
        				</div>
                    </div>
                    <div class='col-lg-4 col-md-6'>
                        <div class='form-floating mb-3'>
        					<input class='form-control' id='UsuUsuario'
        						name='UsuUsuario' type='text' /> 
        					<label for='UsuUsuario'> Usuario</label>					
        				</div>
                    </div>
                    <div class='col-lg-4 col-md-6'>
                        <div class='form-floating mb-3'>
        					<input class='form-control' id='UsuClave'
        						name='UsuClave' type='password'  /> 
        					<label for='UsuClave'> Clave</label>					
        				</div>
                    </div>
                    <div class='col-lg-4 col-md-6'>
                        <div class='form-floating mb-3'>
        					<select class='form-select' id='UsuRol'
        						name='UsuRol'>".
        						$arguments["rol"]
        					."</select>
        					<label for='UsuRol'> Funci&oacute;n</label>					
        				</div>
                                				        				
        			</div>
        
        		
        			 <div class='col-lg-4 col-md-6'>
        				<!-- Submit Button-->
        				<div class='button-group text-center'>
        					<button class='btn btn-primary' id='submitButtonUsuario'
        						type='submit'>Enviar</button>
        					<button class='btn btn-danger' id='resetButtonUsuario'
        						type='reset'>Limpiar</button>
        				</div>
        			</div>
        		</div>
        	</form>
        </div>";
	}
	
	/**
	 * Devuelve la forma a usar para agregar datos modal
	 *
	 * @param mixed $arguments
	 * @return string
	 */
	public function modal($arguments = null) 
    {
        return "No se ha implementado";
	}
	/**
	 * Regresa lista formateada para select
	 *
	 * @param int $seleccionado
	 * @param string $condicion
	 * @param string $ordenado
	 * @return mixed
	 */
	public function listaSelect($seleccionado, $condicion = "0", $ordenado = "0") 
    {
        return $this->options("UsuID as id, UsuNombre as nombre", $this->tabla, "id", $seleccionado, $condicion, $ordenado);
	}
	
	/**
	 * Regresa lista foemateada en Json
	 *
	 * @param int $seleccionado
	 * @param string $condicion
	 * @param string $ordenado
	 * @return mixed
	 */
	public function listaJson($seleccionado, $condicion = "0", $ordenado = "0") 
    {
        return "NO se ha implementado";
	}
}

