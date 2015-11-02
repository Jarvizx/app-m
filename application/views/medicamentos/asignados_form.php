<?php $valores_tbl_rev_expediente = $tbl_rev_expediente->row(); ?>
<?php $valores_tbl_invima_medicamento = $tbl_invima_medicamento->row(); ?>
<img class="img-google-1">
<img class="img-google-2">
<img class="img-google-3">
<table class="table">
	<tbody>
		<tr>
			<th>
				Expediente
			</th>
			<th>
			 	<?php echo $valores_tbl_rev_expediente->NumeroExpediente; ?> 
			</th>
			<th></th>
		</tr>
		<tr>
			<th>
				Nombre Producto
			</th>
			<td>
			 	<input type="text" class="MarcaSignoDistintivoComercial" name="MarcaSignoDistintivoComercial" value="<?php echo $valores_tbl_rev_expediente->MarcaSignoDistintivoComercial ?> ">
			 	<input type="hidden" class="MarcaSignoDistintivoComercial" name="MarcaSignoDistintivoComercial" value="<?php echo $valores_tbl_rev_expediente->MarcaSignoDistintivoComercial ?> ">
			</td>
			<td>
				<?php echo $valores_tbl_invima_medicamento->nombre_del_producto; ?>
			</td>
		</tr>
		<tr>
			<th>
				Forma Comercializacion
			</th>
			<td>
				<?php 
					foreach ($tbl_referencia_identificador->result_array() as $k_tbl_referencia => $v_tbl_referencia)
					{
						if ($valores_tbl_rev_expediente->IdentificadorFormaComercializacion == $v_tbl_referencia['codigo']) 
						{
							echo sprintf('<input type="radio" name="IdentificadorFormaComercializacion" value="%s" checked> %s <br>', $v_tbl_referencia['codigo'], $v_tbl_referencia['nombre_codigo']);
						}
						else
						{
							echo sprintf('<input type="radio" name="IdentificadorFormaComercializacion" value="%s"> %s <br>', $v_tbl_referencia['codigo'], $v_tbl_referencia['nombre_codigo']);
						}
					}
				?>
			</td>
			<td>
				<?php echo $valores_tbl_invima_medicamento->generico; ?>
			</td>
		</tr>
		<tr>
			<th>
				Forma Farmaceutica
			</th>
			<td>
				<select>
					<?php 
						foreach ($tbl_referencia_ffm->result_array() as $k_tbl_referencia => $v_tbl_referencia)
						{
							if ($valores_tbl_rev_expediente->CodigoFormaFarmaceutica == $v_tbl_referencia['codigo']) 
							{
								echo sprintf('<option value="%s" selected> %s </option>', $v_tbl_referencia['codigo'], $v_tbl_referencia['nombre_codigo']);
							}
							else
							{
								echo sprintf('<option value="%s"> %s </option>', $v_tbl_referencia['codigo'], $v_tbl_referencia['nombre_codigo']);
							}
						}
					?>
				</select>
			</td>
			<td>
				<?php echo $valores_tbl_invima_medicamento->forma_farmaceutica; ?>
			</td>
		</tr>
		<tr>
			<th>
				Via Administracion
			</th>
			<td>
			 	<?php echo $valores_tbl_rev_expediente->CodigoViaAdministracion; ?> 
			 	<?php $CodigoViaAdministracion = explode("&", $valores_tbl_rev_expediente->CodigoViaAdministracion); ?> 
			 	<?php 
			 		$i = 0;
			 		$nuevo_array_codigo_via_administracion = array();
					foreach ($CodigoViaAdministracion as $k_CodigoViaAdministracion => $v_CodigoViaAdministracion)
					{
						foreach ($tbl_referencia_vad->result_array() as $k_tbl_referencia_vad => $v_tbl_referencia_vad)
						{
							if (($v_CodigoViaAdministracion * 1) == $v_tbl_referencia_vad['codigo']) 
							{
								$nuevo_array_codigo_via_administracion[$k_tbl_referencia_vad]['codigo'] = $v_tbl_referencia_vad['codigo'];
								$nuevo_array_codigo_via_administracion[$k_tbl_referencia_vad]['nombre_codigo'] = $v_tbl_referencia_vad['nombre_codigo'];
								$nuevo_array_codigo_via_administracion[$k_tbl_referencia_vad]['selected'] = true;
							}
							else
							{
								if ( ! empty($nuevo_array_codigo_via_administracion[$k_tbl_referencia_vad]['selected']))
								{	
									if ($nuevo_array_codigo_via_administracion[$k_tbl_referencia_vad]['selected'] != true) 
									{
										$nuevo_array_codigo_via_administracion[$k_tbl_referencia_vad]['codigo'] = $v_tbl_referencia_vad['codigo'];
										$nuevo_array_codigo_via_administracion[$k_tbl_referencia_vad]['nombre_codigo'] = $v_tbl_referencia_vad['nombre_codigo'];
										$nuevo_array_codigo_via_administracion[$k_tbl_referencia_vad]['selected'] = false;
									}
								}
								else
								{
									$nuevo_array_codigo_via_administracion[$k_tbl_referencia_vad]['codigo'] = $v_tbl_referencia_vad['codigo'];
									$nuevo_array_codigo_via_administracion[$k_tbl_referencia_vad]['nombre_codigo'] = $v_tbl_referencia_vad['nombre_codigo'];
									$nuevo_array_codigo_via_administracion[$k_tbl_referencia_vad]['selected'] = false;
								}
							}
						}
					}
			 	 ?>
				<select id="example-getting-started" multiple="multiple">
				 	<?php foreach ($nuevo_array_codigo_via_administracion as $k_nuevo_array_codigo_via_administracion => $v_nuevo_array_codigo_via_administracion): ?>
    					<option value="<?= $v_nuevo_array_codigo_via_administracion['codigo']?>" <?php echo ($v_nuevo_array_codigo_via_administracion['selected']) ? "selected" : "" ; ?>><?= $v_nuevo_array_codigo_via_administracion['nombre_codigo']?></option>
				 	<?php endforeach ?>
				</select>
			 	</pre>
			</td>
			<td>
				// pendiente
			</td>
		</tr>

<!--
IdentificadorTipoConcentracionEstandarizada
CantidadEstandarizadaPrincipioActivo
CodigoUnidadMedidaEstandarizadaPrincipioActivo
CantidadEstandarizadaMedicamentoContenidoPrincipioActivo
CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo
-->
	</tbody>
</table>

<table class="table">

	<thead>
		<tr>
			<th>Principio <br> Activo</th>
			<th>Tipo <br> Concentracion </th>
			<th>Cantidad<br>Principio<br>Activo</th>
			<th>Unidad</th>
			<th>Cantidad<br>Medicamento</th>
			<th>U.</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($tbl_rev_expediente_pa->result() as $k_tbl_rev_expediente_pa => $v_tbl_rev_expediente_pa): ?>
			<tr>
				<td><?php echo $v_tbl_rev_expediente_pa->NombrePrincipioActivo; ?></td>
				<td><?php echo $v_tbl_rev_expediente_pa->IdentificadorTipoConcentracionEstandarizada; ?></td>
				<td><?php echo $v_tbl_rev_expediente_pa->CantidadEstandarizadaPrincipioActivo; ?></td>
				<td><?php echo $v_tbl_rev_expediente_pa->CodigoUnidadMedidaEstandarizadaPrincipioActivo; ?></td>
				<td><?php echo $v_tbl_rev_expediente_pa->CantidadEstandarizadaMedicamentoContenidoPrincipioActivo; ?></td>
				<td><?php echo $v_tbl_rev_expediente_pa->CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo; ?></td>
			</tr>
		<?php endforeach ?>
	</tbody>
	
</table>

<!-- Initialize the plugin: -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#example-getting-started').multiselect();
        
        var urlImagenGoogle = "https://ajax.googleapis.com/ajax/services/search/images?v=1.0&q="+$(".MarcaSignoDistintivoComercial").val();
        $.ajax({
		  	type: 'GET',
		  	contentType: "application/json",
		  	dataType: 'jsonp',
			url: urlImagenGoogle,
			success: function(respuesta){
				console.log( "success", respuesta );
				$(".img-google-1").attr("src", respuesta.responseData.results[0].url);
				$(".img-google-2").attr("src", respuesta.responseData.results[1].url);
				$(".img-google-3").attr("src", respuesta.responseData.results[2].url);
			}
		});

    });
</script>



