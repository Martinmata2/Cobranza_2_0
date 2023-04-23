/**
 * Realiza un llamado POST a ajax
 * @param url string archivo a ejecutar
 * @param data array datos
 * @param success function en caso de exito
 * @param error function en caso de error
 * @param cache boolean usar memoria
 * @returns void
 */
	function ajax_call(url,data,success,error,cache)
	{
		if (typeof(cache)==='undefined') cache = false;
		$.ajax(
		{
			type: "POST",
			url: url,			
			data: data,
			beforeSend: function() 
			{		   
				$("#trabajando").addClass("loading");
		    },
			cache:cache,
			success: success,
			error:error	
		});
	}
	
	/**
 * Realiza un llamado POST a ajax
 * @param url string archivo a ejecutar
 * @param data array datos
 * @param success function en caso de exito
 * @param error function en caso de error
 * @param cache boolean usar memoria
 * @returns void
 */
	function ajax_call2(url,data,begin,success,error,complete,cache)
	{
		if (typeof(cache)==='undefined') cache = false;
		$.ajax(
		{
			type: "POST",
			url: url,			
			data: data,
			beforeSend:begin,
			cache:cache,
			success: success,
			error:error,
			complete:complete			
		});
	}

	function ajax_call_json(url,data,begin,success,error,complete,cache)
	{
		if (typeof(cache)==='undefined') cache = false;
		$.ajax(
		{
			dataType:"json",
			type: "POST",
			url: url,			
			data: data,
			beforeSend:begin,
			cache:cache,
			success: success,
			error:error,
			complete:complete			
		});
	}

	function ajax_file(url, data,begin,success,error,complete,cache)
	{
		if (typeof(cache)==='undefined') cache = false;
		console.log(JSON.stringify(data));
		$.ajax(
		{
			url: url,
    		type:"POST",
			enctype:"multipart/form-data",
    		data:data,
    		contentType:false,
    		processData:false,
    		beforeSend:begin,
			cache:cache,
			success:success,
			error:error,
			complete:complete
		});		
	}

/**
 * realiza un llamado GET a ajax
 * @param url string archivo a ejecutar
 * @param data array datos
 * @param success function en caso de exito
 * @param error function en caso de error
 * @param cache boolean usar memoria
 * @returns void
 */	
	function ajax_get(url,data,success,error,cache)
	{
		if (typeof(cache)==='undefined') cache = false;
		$.ajax(
		{
			type: "GET",
			url: url,			
			data: data,
			beforeSend: function() 
			{		   
				$("#trabajando").addClass("loading");
		    },
			cache:cache,
			success: success,
			error:error,
			complete:termnamos			
		});
	}
	
	
	/**
	 * 
	 * @param que string que fue el error
	 * @param donde string donde sucedio 
	 * @param inicio string ubicacion del llamado a error
	 * @returns void
	 */
	function archiva_error(que, donde, inicio)
	{
		$.ajax(
		{
			traditional: true,
			type: 'POST',
			url: inicio+'ajax/error.php',
			data:
			{
				que:JSON.stringify(que),
				donde:donde
			},
			cache: false,
			success: function(response)
			{
				;
			},
			error: function(response)
			{
				;//errores(response,3,false);
			}
		});
	}
	
	/**
	 * 
	 * @returns fecha de hoy en formato php YYYY-MM-DD HH:MM:SS
	 */
	function fecha_hoy () 
	{
		  now = new Date();
		  year = "" + now.getFullYear();
		  month = "" + (now.getMonth() + 1); if (month.length == 1) { month = "0" + month; }
		  day = "" + now.getDate(); if (day.length == 1) { day = "0" + day; }
		  hour = "" + now.getHours(); if (hour.length == 1) { hour = "0" + hour; }
		  minute = "" + now.getMinutes(); if (minute.length == 1) { minute = "0" + minute; }
		  second = "" + now.getSeconds(); if (second.length == 1) { second = "0" + second; }
		  return year + "-" + month + "-" + day + " " + hour + ":" + minute + ":" + second;
	}
	
	function calendario()
	{
		$( ".datepicker" ).datepicker( $.datepicker.regional[ "es" ] );
		// bind change event to select
		$(".datepicker").datepicker(
	    {
	       "disabled":false,
	       "dateFormat":"yy-mm-dd",
	       "changeMonth": true,
	       "changeYear": true,
	       "firstDay": 1,
	       "showOn":'both',
	       "yearRange": "c-200:+0",
		 }).next('button').button(
		 {
			 icons:
			 {
				 primary: 'ui-icon-calendar'
			 },
			 text:false
	     }).css({'font-size':'80%', 'margin-left':'2px', 'margin-top':'-5px'});
	}
	