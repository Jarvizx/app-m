<?php if($this->session->flashdata('message')): echo $this->session->flashdata('message'); endif;?>
	<br>

	<label for="buscar">Buscar:</label>
	<form action="<?php echo "/medicamentos/asignar/".$this->uri->segment(3)."/".$this->uri->segment(4)."/"; ?>">
		<div class="input-group">
		      	<input type="text" class="form-control" name="parametro_de_busqueda" placeholder="Buscar" value="<?php echo ( ! empty($this->input->get('parametro_de_busqueda'))) ? $this->input->get('parametro_de_busqueda') : "" ; ?>">
		      	<span class="input-group-btn">
		        	<button class="btn btn-primary" type="submit">Buscar</button>
		      	</span>
	    </div><!-- /input-group -->
	</form>

	<div class="form-group">
		<label for="numero de registro por pagina">Numero de registro por pagina</label>
		<select class="form-control limite_resultados" >
			<option value="20" <?php echo ($this->uri->segment(4) == 20) ? "selected" : ""; ?>>20</option>
			<option value="50" <?php echo ($this->uri->segment(4) == 50) ? "selected" : ""; ?>>50</option>
			<option value="100" <?php echo ($this->uri->segment(4) == 100) ? "selected" : ""; ?>>100</option>
		</select>
	</div>
	<hr>

	<label for="asignar a">Asignar a:</label>
	<div class="input-group">
		<select class="form-control asignar_usuario" >
			<?php foreach ($usuarios as $k_usuarios => $v_usuarios): ?>
				<option value="<?php echo $v_usuarios['id']; ?>"><?php echo $v_usuarios['first_name']." ".$v_usuarios['last_name']; ?></option>	
			<?php endforeach ?>
		</select>
		<span class="input-group-btn">
        	<button class="btn btn-primary boton_guardar_asignar_usuario" type="button">Guardar</button>
      	</span>
	</div>



	<table id="tabla_asignar" class="table">
		<thead>
			<tr>
				<th width="170"><input type="checkbox" class="chkboxAll" /> Seleccionar todos</th>
				<th># expediente</th>
				<th>Estado</th>
				<!--<th>Nombre</th>-->
				<!--<th>Usuario asignado</th>-->
			</tr>
		</thead>
		<tbody>
			<?php foreach ($lista_asignacion as $k_lista_asignacion => $v_lista_asignacion): ?>
				<tr>
					<td class="text-center">
						<input type="checkbox" class="chkbox" value="<?php echo $v_lista_asignacion->id; ?>" />
					</td>
					<td>
						<?php echo $v_lista_asignacion->expediente; ?>
					</td>
					<td>
						<?php echo $v_lista_asignacion->estado; ?>
					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>

	<ul class="pagination">
		<?php echo $this->pagination->create_links() ?>
	</ul>

	<script type="text/javascript">
		var lastChecked = null;

		$(document).ready(function() 
		{
			$(".boton_guardar_asignar_usuario").click(function(){
				var usuario_asignado = $(".asignar_usuario").val();
				//console.log(usuario_asignado);

			 	var expedientes_seleccionados = new Array();
		        $("input:checked").each(function() {
		        	expedientes_seleccionados.push($(this).val());
		        });
				
				console.log(expedientes_seleccionados);
				$.ajax({
					type: "POST",
					url: "/medicamentos/guardar_asignacion",
					data: {expedientes: expedientes_seleccionados, id_usuario: usuario_asignado}
				}).done(function(msg){
					console.log(msg,'ok...');
				});
			});

			$(".limite_resultados").change(function(){
				var valor_seleccionado = $(this).val()
				var url_host = window.location.origin;
				var url_parametros = window.location.pathname;
				var atributos = url_parametros.split("/");
				var parametro_de_busqueda;

				if (typeof atributos[3] == "undefined" || atributos[3] == "") 
				{
					atributos[3] = 0;
				};

				if (typeof atributos[3] == "undefined" || window.location.search == "") 
				{
					parametro_de_busqueda = "/";
				}
				else
				{
					parametro_de_busqueda = window.location.search;
				}

				var url_redirec = url_host +"/"+ atributos[1] +"/"+ atributos[2] +"/"+ atributos[3] +"/"+ valor_seleccionado + parametro_de_busqueda;
				//console.log(url_redirec, ' -- ', atributos);
				window.location.assign(url_redirec);
			})

			$(".chkboxAll").click(function(){
				var checkboxes = document.getElementsByTagName('input');
				console.log($(this).is(':checked'));
				if ($(this).is(':checked')) 
				{
					for (var i = 0; i < checkboxes.length; i++) 
					{
						if (checkboxes[i].type == 'checkbox') 
						{
							checkboxes[i].checked = true;
						}
					}
				} else {
					for (var i = 0; i < checkboxes.length; i++) 
					{
						if (checkboxes[i].type == 'checkbox') 
						{
							checkboxes[i].checked = false;
						}
					}
				}
			});
			var $chkboxes = $('.chkbox');
			$chkboxes.click(function(e) 
			{
				if(!lastChecked) 
				{
					lastChecked = this;
					return;
				}
				if(e.shiftKey) 
				{
					var start = $chkboxes.index(this);
					var end = $chkboxes.index(lastChecked);
					$chkboxes.slice(Math.min(start,end), Math.max(start,end)+ 1).prop('checked', lastChecked.checked);
				}
				lastChecked = this;
			});

			$('#tabla_asignar').DataTable({
				paging: false,
				searching: false,
				bFilter: false, 
				bInfo: false
			});
		});

	</script>