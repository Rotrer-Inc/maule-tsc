function objetoAjax(){
	var xmlhttp=false;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
		   xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (E) {
			xmlhttp = false;
  		}
	}

	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}

function eliminarDato(id){
		alert(id);

	divResultado = document.getElementById('resultado');
	valor=document.getElementById('monto_fijo').value;
	
	where='eliminar_dato';
		ajax=objetoAjax();

		ajax.open("GET", "modulos/PROCESOS_ajax.php?id="+id+"&where="+where+"&valor="+valor);
		divResultado.innerHTML= '<div align="center" style="margin:15px 0px 15px  0px"><img src="images/loader.gif"></div>';
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				divResultado.innerHTML = ajax.responseText
			}
		}
		ajax.send(null)
}



function monto_fijo(){
	divResultado = document.getElementById('resultado');
	valor=document.getElementById('monto_fijo').value
	
	where='monto_fijo';
	
		ajax=objetoAjax();

		ajax.open("GET", "modulos/PROCESOS_ajax.php?valor="+valor+"&where="+where);
		divResultado.innerHTML= '<div align="center" style="margin:15px 0px 15px  0px"><img src="images/loader.gif"></div>';
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				divResultado.innerHTML = ajax.responseText
			}
		}
		ajax.send(null)
}

function envio_single(){
	divResultado = document.getElementById('enviar');
	valor=document.getElementById('single').value
	if((valor>6000)&&(valor<999999)){
		window.location="recarga-paso2.php";
	}
	
	where='monto_single';
	
		ajax=objetoAjax();
		ajax.open("GET", "modulos/PROCESOS_ajax.php?valor="+valor+"&where="+where);
		divResultado.innerHTML= '<div align="center" style="margin:15px 0px 15px  0px"><img src="images/loader.gif"></div>';
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				divResultado.innerHTML = ajax.responseText
			}
		}
		ajax.send(null)
}