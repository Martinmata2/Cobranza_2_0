<?php
/**
 * @version v2020_2
 * @author Martin Mata
 */
namespace Cabeca\Factory\Utilidades;

class MPage
{
    /**
     * Incluye la pagina master
     * @param string $inicio
     * @param string $page
     */
	static function Render($inicio,$page)
	{
		include($inicio.$page);
	}

	/**
	 * Inicia la seccion mostrar
	 */
	static function BeginBlock()
	{
		ob_start();
	}

	/**
	 * Termina la seccion a mostrar
	 * @param string $name Nombre de la seccion
	 */
	static function EndBlock($name)
	{
		$data = ob_get_contents();
		define("_block_" . $name . "_", $data);
		ob_end_clean();
	}

	/**
	 * Crea un espacion para definir
	 * @param string $name
	 * @param boolean $clean
	 * @return mixed
	 */
	static function PlaceHolder($name, $clean = FALSE)
	{
		$temp = constant("_block_" . $name . "_");

		// If you want to insert the same content in different places of your master page, $clean = true!
		if ($clean) {
			define("_block_" . $name . "_", null);
		}

		return $temp;
	}

	/**
	 * Verifica que la seccion no exista anteriormente
	 * @param string $name
	 * @return boolean
	 */
	static function IsDefined($name)
	{
		return defined("_block_" . $name . "_");
	}
}