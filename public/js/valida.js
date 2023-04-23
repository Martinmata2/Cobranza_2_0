
$(document).ready(function()
{
	$(document).on("change",".valida", function()
	{
		var DATOS_VALIDOS = 200;
		var tipo = $(this).data("type");
		var unico = $(this).data("unico") == true;		
	 	var continua = true;
	 	var datos = $(this).data("validar");
	 	var valido = false;
		if(tipo == "length")
		{
		    continua = false;
			if(verifyLength($(this).val(),$(this).data("length") * 1))					
				validData($(this));					
			else									
				 invalidData($(this));										
		}		
		else if($(this).data("length"))
		{
		    if(verifyLength($(this).val(),$(this).data("length") * 1))					    
				validData($(this));
			else 
			{
			    continua = false;
				invalidData($(this));
			}
		}
		else if(tipo == "condition")  //FacMetodoPago|PUE-99;PPD+99)
		{				
			continua = false;
			
			var conditionvalue = $(this);
			var parte = $(this).data("condition").split("|");				
			var conditionid = parte[0];				
			var partes = parte[1].split(";");				
			$(partes).each(function(i,value)
			{
				
				var condition = value.split("+")										
				if(condition.length > 1 && !valido)
				{						
					
					if($("#"+conditionid).val() == condition[0] && $(conditionvalue).val() == condition[1])
					{							
						valido = true;
						validData($(conditionvalue));
					}
					
					else 
					{												
						valido = false;	
						invalidData($(conditionvalue));
					}
					
				}
				condition = value.split("-")
				if(condition.length > 1 && !valido)
				{												
					if($("#"+conditionid).val() != condition[0])
					{
						valido = false;
						invalidData($(conditionvalue));
					}
					else if($("#"+conditionid).val() == condition[0] && $(conditionvalue).val() == condition[1])
					{
						valido = false;
						invalidData($(conditionvalue));
					}
					else 
					{
						validData($(conditionvalue));
						valido = true;
					}
				}
				
			}); 
		}
		else if(continua)
		{
			var form_data = 
			{
				validar:$(this).data("type"),
				value: $(this).val()
			}						
							
			validar(form_data,$(this),DATOS_VALIDOS,unico,datos,$(this).val(),$(this).attr("id"));
		}		
				
	});
});
function verifyLength(value, length)
{
	return value.length >= length;
}
