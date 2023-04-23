<?php
namespace App\Modelos\Datos;

class ModulosD
{
    /**
* @var int
*
*/public $ModID;
/**
* @var string
*
*/
public $ModNombre;
/**
* @var int
*
*/public $ModRol;
/**
* @var string
*
*/
public $ModPath;
/**
* @var string
*
*/
public $ModIcon;
/**
* @var int
*
*/public $ModOrder;
/**
* @var string
*
*/
public $lastupdate;
 
    public function __construct($datos = null)
    {
        try
        {
            if ($datos == null)
            {
                $this->ModID = 0;
$this->ModNombre = "";
$this->ModRol = 0;
$this->ModPath = "";
$this->ModIcon = "";
$this->ModOrder = 0;
$this->lastupdate = "";

            }
            else
            {
                foreach ($datos as $k => $v)
                {
                    $this->$k = $v;
                }
            }
        }
        catch (\Exception $e)
        {}
    }
}