<?php
namespace Cabeca\Factory;

use Cabeca\MySql\Query;

class DataHelper extends Query
{
    function __construct() 
    {
        parent::__construct(BD_GENERAL);        
    }
        
    /**
     * 
     * @param string $tabla
     * @return string
     */
    protected function getFields($tabla)
    {
        $datos = array();
        $tabla = $this->conexion->real_escape_string($tabla);    
        $fields = $this->conexion->query("show columns from $tabla");       
        while ($fila = $fields->fetch_object())
        {
            $datos[] = $fila;
        }       
        return $datos;
    }
    public function getDataMembers($tabla)
    {
        $datos = $this->getFields($tabla);
                
        $datamembers =  "";
        $public = "";
        foreach ($datos as $value) 
        {                        
            if(substr($value->Type, 0,3) == "int")
            {
                $asignation = " = 0;";
                $documentation = "/**\n* @var int\n*\n*/";
            }
            else 
            {
                $asignation = " = \"\";";
                $documentation = "/**\n* @var string\n*\n*/\n";
            }
            $public .= $documentation."public $".$value->Field.";\n";
            $datamembers .= "$"."this->".$value->Field.$asignation."\n";
        }
        return array("public"=>$public, "datamembers"=>$datamembers);
    }
    
    public function createTable($tabla)
    {
        $datos = array();
        $result = $this->conexion->query("show create table $tabla");
        while ($fila = $result->fetch_array())
        {
            $datos = $fila;
        }
        return $datos;
    }
    
    /**
     * 
     * @param string $tabla   
     * @return string
     */
    public function makeForm($tabla)
    {
        $datos = $this->getFields($tabla);
        if(count($datos) > 0)
        {            
            $html = "
            <div class='row gx-5 justify-content-center'>
                <div class='col-lg-8 col-xl-6'>
                    <form id='".$tabla."Form' data-sb-form-api-token='API_TOKEN'>";                   
                    foreach ($datos as $campo)
                    {
                        $html .= "
                            <div class='form-floating mb-3'>
                                <input class='form-control' id='".$campo->Field."' name='".$campo->Field."' type='text' data-sb-validations='required' />
                        <label for='".$campo->Field."'> ".$campo->Field."</label>
                        <div class='invalid-feedback' data-sb-feedback='".$campo->Field.":required'>".$campo->Field." es requerido.</div>
                    </div>";
                    }
            $html .= "</form></div></div>";
            return $html;
        }
    }
}