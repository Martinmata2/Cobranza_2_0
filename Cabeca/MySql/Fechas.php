<?php
namespace Cabeca\MySql;

use Cabeca\Core\FechaLetras;

/**
 * @version 1_000
 * 2017
 * @author Martin Mata
 *
 */


class Fechas extends Query
{	
	function __construct($base_datos = BD_GENERAL)
	{
		parent::__construct($base_datos);
		
	}
	
	
	/**
	 * 
	 * @param string $inicio
	 * @param string $final
	 * @param string $campofecha
	 * @return \stdClass|number
	 * 
	 */
	public function rango($inicio, $final, $campofecha)
	{
	    $rango = new \stdClass();
	    $rango->rango = "Desde: ".FechaLetras::fechaLetras($inicio)."  Hasta: ".FechaLetras::fechaLetras($final);
	    $rango->where = "$campofecha between '".$inicio."' AND '".$final."'";
	    $tiempo =  explode("-",$inicio);
	    $temp = explode("-", $final);
	    $rango->mespasado = str_replace("0","", $tiempo[1]);
	    $rango->actual = str_replace("0","", $temp[1]);
	    $rango->year = $temp[0];
	    return $rango;
	    
	}
	
	
	public function mespasadol($campofecha)
	{
	    $rango = new \stdClass();
	    $tiempo =  date("Y-n-d", strtotime("first day of previous month"));
	    $rango->rango = "Desde: ".FechaLetras::fechaLetras($tiempo)."  Hasta: ".FechaLetras::fechaLetras(date("Y-m-d"));
	    $rango->where = "$campofecha >= DATE_ADD(LAST_DAY(DATE_SUB(NOW(), INTERVAL 2 MONTH)), INTERVAL 1 DAY)";	    
	    $temp = explode("-", $tiempo);
	    $rango->mespasado = $temp[1];
	    $rango->actual = $temp[1] *1 + 1;
	    $rango->year = $temp[0];
	    return $rango;	    
	}
	public function estemes($campofecha)
	{
	    $rango = new \stdClass();
	    $tiempo =  date("Y-n-d", strtotime("first day of this month"));
	    $rango->rango = "Desde: ".FechaLetras::fechaLetras($tiempo)."  Hasta: ".FechaLetras::fechaLetras(date("Y-m-d"));
	    $rango->where = "$campofecha >= DATE_ADD(LAST_DAY(DATE_SUB(NOW(), INTERVAL 1 MONTH)), INTERVAL 1 DAY)";
	    $temp = explode("-", $tiempo);	   
	    $rango->actual = $temp[1] *1 + 1;
	    $rango->year = $temp[0];
	    return $rango;	    
	}
	
	public function hoy($campofecha)
	{
	    $rango = new \stdClass();
	    $tiempo =  date("Y-n-d");
	    $rango->rango = "Hoy: ".FechaLetras::fechaLetras($tiempo);
	    $rango->where = "date($campofecha) = CURDATE()";
	    $temp = explode("-", $tiempo);
	    $rango->actual = $temp[1];
	    $rango->year = $temp[0];
	    return $rango;
	}
	
	public function datepicker()
	{
	    
	    return " <form>
    	    <div class='row'>
    			<div class='col-md-4'>
    				<div class='form-floating mb-3'>
                        <input class='hour' type='hidden' id='hourfrom' size='3' value='00:01:00' name='hourfrom'/>
    					<input class='form-control datepicker' autocomplete='off' type='text' id='datefrom' name='datefrom'/>
    					<label for='datefrom'> Desde</label>
    				</div>
    			</div>
    			<div class='col-md-4'>
    				<div class='form-floating mb-3'>
                        <input class='hour' type='hidden' id='hourto' size='3' value='23:59:59' name='hourto'/>
    					<input class='form-control datepicker' autocomplete='off' type='text' id='dateto' /> 
                        <label for='dateto'> Hasta</label>
    				</div>
    			</div>
                <div class='col-md-4'>
        			<div class='button-group text-center'>
    					<button class='btn btn-primary' id='search_date'
    						type='submit'>Enviar</button>
    				</div>
                </div>
            </div>
        </form>   
                    
        <script type='text/javascript'>
            $(document).ready(function()
		      {
                $(document).on('change', '#datefrom', function()
                {
                    $('#dateto').val($('#datefrom').val());
                });
                $('#search_date').click(function()
	    		{
	    			window.location = '?datefrom='+jQuery('#datefrom').val()+' '+jQuery('#hourfrom').val()+'&dateto='+jQuery('#dateto').val()+' '+jQuery('#hourto').val();
                    return false;
	            });
              });  
        </script>		   
	    ";		
	}
	
	
	/**
	 * lista de productos mas vendidos
	 * @param string $periodo
	 * @param int $cantidad
	 * @return number|object[]
	 */
	public function masvendido(string $periodo, int $cantidad)
	{
	    $resultado = $this->consulta("SUM(DdeCantidad) as cantidad, ProDescripcion",
	        "ventas inner join ventadetalles on FacID = DdeDocumento
             inner join productos on DdeProducto = ProID",
	        "date(FacFecha) between $periodo","cantidad desc","DdeProducto",$cantidad );
	    
	    return $resultado;
	}
	public function query($campos, $tabla, $where = "0", $orderby = "0", $groupby = "0", $limit = "0", $usuario = 0)
	{
	    return $this->consulta($campos, $tabla, $where, $orderby, $groupby, $limit, $usuario);
	}
}