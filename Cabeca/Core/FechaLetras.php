<?php 

/**
 * @version 2020_2
 * 2017
 * @author Martin Mata
 */
namespace Cabeca\Core;

class FechaLetras
{
    /**
     * Regresa la fecha en letras   Lunes, 4 de Julio del 2014
     * @param string $fecha
     * @return string
     */
    static function fechaLetras($fecha)
    {
        $stampa =  strtotime($fecha);
        $week_days = array ("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
        $months = array ("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $year_now = date ("Y",$stampa); $month_now = date ("n",$stampa); $day_now = date ("j",$stampa); $week_day_now = date ("w", $stampa);
        $date = $week_days[$week_day_now] . ", " . $day_now . " de " . $months[$month_now] . " de " . $year_now;
        return $date;
    }
    /**
     * agrega minutos a la hora que se da,
     *	@param string $datetime
     *	@param int $add minutos a agregar
     *	@return string
     */
    static function addMinutes($hora, $add)
    {
        $agregar = "+$add minutes";
        return date('Y-m-d H:i:s', strtotime($agregar, strtotime($hora)));
    }
    
    /**
     * agrega dias a la fecha que se da,
     *	@param string $date
     *	@param int $add dias a agregar
     *	@return string
     */
    static function addDays($date, $add)
    {
        $agregar = "+$add days";
        return date('Y-m-d', strtotime($agregar, strtotime($date)));
    }
    
    /**
     * 
     * @param string $fecha
     * @return string
     */
    static function mes($fecha)
    {
        $stampa =  strtotime($fecha);       
        $months = array ("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $month_now = date ("n",$stampa); 
        $date = $months[$month_now];
        return $date;
    }
    /**
     *
     * @param string $fecha
     * @return string
     */
    static function year($fecha)
    {
        $stampa =  strtotime($fecha);   
        $date = date ("Y",$stampa);
        return $date;
    }
    
    /**
     * 
     * @param string $fecha1
     * @param string $fecha2
     * @return number
     */
    static function diffDias($fecha1,$fecha2)
    {
        $diff = strtotime($fecha1) - strtotime($fecha2);
        return floor($diff/3600/24);
    }
}