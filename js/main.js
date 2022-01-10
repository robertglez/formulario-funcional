function otroCorreo(){
  $('#enviado').hide();
  $('#error').hide();
  $('#enviar').show();
  $('.otroCorreo').hide();
}

$(document).ready(function(){
  $('#formulario').submit(function(event){

    //$('#campos').hide();
    $('#enviar').hide();
    $('#enviando').show();

    event.preventDefault(); // Evitando la acción por defecto del navegador

    $.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),
			timeout: 10000,
      success: function(data){
        $('#enviando').hide();
				console.info(data);
				var r = JSON.parse(data); // Decodificando el formato json recibido
				if(r.status=="200"){
					$('#enviado').show();
        }
				else{
					console.log(r.mensaje);
          $('#error').show();
          $('.otroCorreo').show();
        }
			},

		})
	});
});
