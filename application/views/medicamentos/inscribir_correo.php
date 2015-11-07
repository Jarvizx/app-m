<div class="form-group">
    <label for="agregarCorreo">Agregar Correo</label>
	<input type="email" class="form-control" id="agregarCorreo" placeholder="Email" required>
	<button class="btn btn-primary agregar-correo" type="button">Inscribir Correo</button>
	<p class="mostrar-mensaje"></p>
</div>
<script type="text/javascript">
	$(".agregar-correo").click(function(){
		if($("#agregarCorreo")[0].checkValidity())
		{
			$.ajax({
				type: "POST",
				url: "/medicamentos/enviar_correo",
				data: { correo: $("#agregarCorreo").val() }
			}).done(function(respuesta){
				console.log('respuesta: ', respuesta);
			});
		}
		else
		{
			$(".mostrar-mensaje").html('<p class="text-warning">Correo no valido</p>');
		}

	});
</script>	