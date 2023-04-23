<?php
/**
 *
 * @version 2022_1
 * @author Martin Mata
 */

namespace App\Core;

use App\Modelos\Datos\UsuarioD;
use App\Modelos\Usuario;
use Cabeca\Core\Cookie;
use Cabeca\Core\Encode;


define("LOGIN_SUCCESS",200);
define("LOGIN_ERROR", 400);
define("LOGIN_DATO_INVALIDO",0);
define("LOGIN_DATO_VALIDO", 1);
class Acceso extends Usuario
{

    private $cookieoptions = array();
    public function __construct()
    {
        parent::__construct(BD_GENERAL);
        $this->cookieoptions = array("expiry"=>Cookie::AWeek,"path"=>"/","domain"=>true,"secure"=>true, "httponly"=>true,"globalremove"=>true);
    }
    
    /**
     *
     * @param string $usuario
     * @param string $clave
     * @return UsuarioD|number
     */
    public function login($usuario, $clave)
    {
        $usuario = $this->conexion->real_escape_string($usuario);
        $clave = $this->conexion->real_escape_string($clave);
        $login = $this->consulta("*", $this->tabla, "UsuUsuario = '".$usuario."' AND UsuClave = '".$clave."' AND  UsuActivo = ".USU_ACTIVO);
        if (count($login) > 0)
        {
            $this->data = new UsuarioD($login[0]);
            $this->resetValores();
            $this->sessionIniciar();
            $this->recuerdame();
            return $login[0];            
        }
        else
        {
            $this->mensaje = "Usuario o contraseña equivocados";
            return 0;
        }
    }
    
    
    /**
     * Asigna valores en session
     *
     *
     * @return boolean
     */
    private function resetValores()
    {
        @session_start();
        $_SESSION["USR_ROL"] = $this->data->UsuRol;
        $_SESSION["USR_NOMBRE"] = $this->data->UsuNombre;
        $_SESSION["USR_ID"] = $this->data->UsuID;
        $_SESSION["USR_BD"] = $this->data->UsuBD;
        $_SESSION["USR_EMPRESA"] = $this->data->UsuEmpresa;
        if(!isset($_SESSION["CSRF"]))
            $_SESSION["CSRF"] = session_id();
        /**
         * *** Aqui se pueden agregar otros valores necesarios en session ******
         */
        return true;
            
    }
    
    /**
     * Activar session deb base de datos
     * @return boolean
     */
    private function sessionIniciar()
    {
        $data = new \stdClass();
        $data->UsuSesion = session_id();
        return ($this->editar($data, $this->data->UsuID, "UsuID") !== 0);
    }
    
    private function recuerdame()
    {        
        $token = $this->crearllave(24);
        $data = new \stdClass();
        $data->UsuToken = $token;
        $this->editar($data, $this->data->UsuID, "UsuID");
        
        Cookie::Remove("auth",$this->cookieoptions);
        Cookie::Remove("usrtoken",$this->cookieoptions);
        Cookie::Set("auth",session_id(), $this->cookieoptions);
        Cookie::Set("usrtoken",$token,$this->cookieoptions); 
        
    }
    
    /**
     * Destruye la session
     *
     * @param int $id
     *
     */
    public function sesionDestruir($id)
    {
        @session_destroy();
        $data = new \stdClass();
        $data->UsuSesion = 0;
        $data->UsuToken = 0;
        $this->editar($data, $id, "UsuID");
        Cookie::Remove("auth",$this->cookieoptions);
        Cookie::Remove("usrtoken",$this->cookieoptions);
        unset($_SESSION["CSRF"]);
    }
    
    /**
     * Activa el recien creado usuario
     *
     * @param int $uid
     * @param string $actcode
     */
    public function activarUsuario($uid, $actcode)
    {
        $uid = Encode::decode($uid);
        if ($this->consulta("*", $this->tabla,  "(UsuID = '$uid' and UsuToken = '$actcode') and UsuActivo = ".USU_INACTIVO) !== 0)
        {
            $datos = new \stdClass();
            $datos->UsuActivo = USU_ACTIVO;            
            return $this->editar($datos, $uid, "UsuID");
        }
        return 0;
    }
    
    /**
     * Verifica si El Cliente tiene iniciada Session
     *
     * @return bool
     */
    public function estaLogueado()
    {
        @session_start();
        if (isset($_SESSION["USR_ID"]))
        {
            return true;
        }
        elseif (Cookie::Exists('auth') && Cookie::Get('auth') !== false)
        {
            $credenciales = $this->consulta("*", $this->tabla, "UsuToken = '".Cookie::Get("usrtoken")."'");
            if(count($credenciales) > 0)
            {
                $this->data = new UsuarioD($credenciales[0]);
                $this->resetValores();
                $this->sessionIniciar();
                //$this->recuerdame();
                return true;
            }
            else return false;
        }
        else
            return false;       
    }
    
    
    /**
     * Crea una cadena aleatoria con un numero de caracteres
     *
     * @param int $length
     * @return string clave de $lenth caracteres
     */
    public function crearllave($length = 16)
    {
        if ($length <= 0) {
            return false;
        }
        $code = "";
        $chars = "abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
        srand((double) microtime() * 1000000);
        for ($i = 0; $i < $length; $i ++) {
            $code = $code . substr($chars, rand() % strlen($chars), 1);
        }
        return $code;
    }
}

