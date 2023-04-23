/*

Esta funcion da por hecho que las variables 
var classValid
var classInvalid
var INICIO  
fueron declaradas al inicio del documento que lo esta llamando
 */
function validar(form_data, objeto, success, unico, tipo, valor,campo) {
	if (typeof (unico) === 'undefined') unico = false;
	if (typeof (tipo) === 'undefined') unico = "";
	
	ajax_call2(INICIO + "Ajax/Validar/", form_data,
		empezamos,
		exito,
		error,
		terminamos
	);

	function exito(respuesta) 
	{
		var datos = $.parseJSON(respuesta);
		if (datos.status == success) 
		{
			if (unico) 
			{
				ajax_call(INICIO + "Ajax/Validar/Unico.php",
					{
						validar: "unico",
						tipo: tipo,
						value: valor,
						campo: campo
					},
					function(respuesta) 
					{
						var datos = $.parseJSON(respuesta);
						if (datos.status == success) 
						{
							$(objeto).removeClass(classInvalid);
							$(objeto).addClass(classValid);
						}
						else 
						{
							$(objeto).removeClass(classValid);
							$(objeto).addClass(classInvalid);
							$(objeto).notify(datos.mensaje, "error");
						}
					},
					function(error) 
					{
						$(objeto).removeClass(classInvalid);
						$(objeto).addClass(classValid);
						console.log(error);
					},
					function() { }
				);
			}
			else 
			{
				$(objeto).removeClass(classInvalid);
				$(objeto).addClass(classValid);
			}
		}
		else 
		{
			$(objeto).removeClass(classValid);
			$(objeto).addClass(classInvalid);
			$(objeto).notify(datos.mensaje, "error");
		}
	}

	function error(error) {};
	function terminamos(){};
	function empezamos(){};
	
}

