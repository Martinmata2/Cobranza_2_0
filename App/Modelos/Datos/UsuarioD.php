<?php
namespace App\Modelos\Datos;

class UsuarioD
{
    /**
* @var int
*
*/public $UsuID;
/**
* @var string
*
*/
public $UsuEmpresa;
/**
* @var int
*
*/public $UsuRol;
/**
* @var string
*
*/
public $UsuUsuario;
/**
* @var string
*
*/
public $UsuClave;
/**
* @var string
*
*/
public $UsuNombre;
/**
* @var string
*
*/
public $UsuEmail;
/**
* @var string
*
*/
public $UsuActivo;
/**
* @var string
*
*/
public $UsuToken;
/**
* @var string
*
*/
public $UsuBD;
/**
* @var string
*
*/
public $updated;
/**
* @var int
*
*/public $UsuPromotor;
/**
* @var string
*
*/
public $UsuArea;
/**
* @var int
*
*/public $UsuCliente;
/**
* @var string
*
*/
public $UsuSesion;
/**
* @var string
*
*/
public $UsuUsuarioPAC;
/**
* @var string
*
*/
public $UsuClavePAC;
 
    public function __construct($datos = null)
    {
        try
        {
            if ($datos == null)
            {
                $this->UsuID = 0;
$this->UsuEmpresa = "";
$this->UsuRol = 0;
$this->UsuUsuario = "";
$this->UsuClave = "";
$this->UsuNombre = "";
$this->UsuEmail = "";
$this->UsuActivo = "";
$this->UsuToken = "";
$this->UsuBD = "";
$this->updated = "";
$this->UsuPromotor = 0;
$this->UsuArea = "";
$this->UsuCliente = 0;
$this->UsuSesion = "";
$this->UsuUsuarioPAC = "";
$this->UsuClavePAC = "";

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