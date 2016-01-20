<?php 
//echo '<br>mostrar_input_correo '.$mostrar_input_correo;
//echo '<br>es_anonimo '.$es_anonimo;
if ($mostrar_input_correo == true && $es_anonimo == 0) 
{
	// Si no es usuario anomino y tampoco tiene algun correo por get, cargo esta vista
	$this->layout->view('medicamentos/inscribir_correo',null);
	exit();
}
?>
<?php $valores_tbl_rev_expedientes = $tbl_rev_expedientes->row(); ?>
<?php $valores_tbl_invima_medicamento = $tbl_invima_medicamento->row(); ?>
<!--<div class="img-expediente">
	<img class="img-google-1 img-thum">
	<img class="img-google-2 img-thum">
	<img class="img-google-3 img-thum">
</div>-->
<table class="table">
	<tbody>
		<tr>
			<th>
				Expediente
			</th>
			<th>
			 	<?= $valores_tbl_rev_expedientes->NumeroExpediente; ?> 
			 	<input type="hidden" class="NumeroExpediente" value="<?= $valores_tbl_rev_expedientes->NumeroExpediente; ?>">
			</th>
			<th></th>
		</tr>
		<!---->
		<tr>
			<th>
				Nombre Producto
			</th>
			<?php 
				if (strpos(soundex($valores_tbl_invima_medicamento->nombre_del_producto), soundex($valores_tbl_rev_expedientes->MarcaSignoDistintivoComercial)) !== false ) 
				{
					$color = 'bg-warning';
					if ($valores_tbl_invima_medicamento->nombre_del_producto == $valores_tbl_rev_expedientes->MarcaSignoDistintivoComercial) 
					{
						$color = 'bg-success';
					}
				}
				else
				{
					$color = 'bg-danger';
				}
			?>
			<td class="<?=$color;?>">
			 	<input type="text" class="MarcaSignoDistintivoComercial form_app_m form-control" data-json='{"tabla":"tbl_rev_expedientes", "llave":"<?= $valores_tbl_rev_expedientes->id ?>", "valor_viejo":"<?= $valores_tbl_rev_expedientes->MarcaSignoDistintivoComercial; ?>", "campo":"MarcaSignoDistintivoComercial"}' name="MarcaSignoDistintivoComercial" value="<?= $valores_tbl_rev_expedientes->MarcaSignoDistintivoComercial; ?>">
			 	<br>
			 	<b>Invima: </b><?= $valores_tbl_invima_medicamento->nombre_del_producto; ?>
			 	<input type="hidden" class="nombre_del_producto_invima" value="<?= $valores_tbl_invima_medicamento->nombre_del_producto; ?>">
			 	<hr>
			 	<div class="comentario">
			 		<?= ( ! empty($comentarios_MarcaSignoDistintivoComercial->texto)) ? $comentarios_MarcaSignoDistintivoComercial->texto : "";?>
			 	</div>
			</td>
			<td>
				<form tipoComentario="comentario_en_td_solo" data-comentario='{"clase": "MarcaSignoDistintivoComercial"}'>
					<b>Comentario: </b><br>
					<textarea rows="1" cols="40" class="form-control" name="comentario"></textarea><br>
					<input class="r_nombre_del_producto_invima" type="radio" name="estado_revision" value="Ok"> Ok 
					<input class="r_nombre_del_producto_invima" type="radio" name="estado_revision" value="Rev. Lab"> Rev. Lab 
					<input class="r_nombre_del_producto_invima" type="radio" name="estado_revision" value="Rev. Super"> Rev. Super 
					<input class="r_nombre_del_producto_invima" type="radio" name="estado_revision" value="Pendiente"> Pendiente 					
				</form>
			</td>
		</tr>
		<!---->
		<tr>
			<th>
				Forma Comercializacion
			</th>
			<?php 
				foreach ($tbl_referencia_identificador->result_array() as $k_tbl_referencia => $v_tbl_referencia)
				{
					if ($valores_tbl_rev_expedientes->IdentificadorFormaComercializacion == $v_tbl_referencia['codigo']) 
					{
						$IdentificadorFormaComercializacion[] = sprintf('<input type="radio" class="form_app_m" name="IdentificadorFormaComercializacion" value="%s" checked> %s <br>', $v_tbl_referencia['codigo'], $v_tbl_referencia['nombre_codigo']);
						$valor_actual_IdentificadorFormaComercializacion = $valores_tbl_rev_expedientes->IdentificadorFormaComercializacion;
					}
					else
					{
						$IdentificadorFormaComercializacion[] = sprintf('<input type="radio" class="form_app_m" name="IdentificadorFormaComercializacion" value="%s"> %s <br>', $v_tbl_referencia['codigo'], $v_tbl_referencia['nombre_codigo']);
						$valor_actual_IdentificadorFormaComercializacion = null;
					}
				}

				if ($valor_actual_IdentificadorFormaComercializacion == $valores_tbl_invima_medicamento->IdentificadorFormaComercializacion_homologado)
				{
					$color = 'bg-success';
				}
				else
				{
					$color = 'bg-danger';
				}
			?>
			<td class="<?=$color;?>">
				<?php foreach ($IdentificadorFormaComercializacion as $k_IdentificadorFormaComercializacion => $v_IdentificadorFormaComercializacion): ?>
					<?= $v_IdentificadorFormaComercializacion;?>
				<?php endforeach ?>
				<input type="hidden" class="IdentificadorFormaComercializacion" data-json='{"tabla":"tbl_rev_expedientes", "llave":"<?= $valores_tbl_rev_expedientes->id ?>", "valor_viejo":"<?= $valor_actual_IdentificadorFormaComercializacion; ?>", "campo":"IdentificadorFormaComercializacion"}' value='{"tabla":"tbl_rev_expedientes", "llave":"<?= $valores_tbl_rev_expedientes->id ?>", "valor_viejo":"<?= $valor_actual_IdentificadorFormaComercializacion; ?>", "campo":"IdentificadorFormaComercializacion"}'>
				<hr>
				<div class="comentario">
			 		<?= ( ! empty($comentarios_IdentificadorFormaComercializacion->texto)) ? $comentarios_IdentificadorFormaComercializacion->texto : "";?>
				</div>
			</td>
			<td>
				<?php //echo $valores_tbl_invima_medicamento->generico; ?>
				<form tipoComentario="comentario_en_td_solo" data-comentario='{"clase": "IdentificadorFormaComercializacion"}'>
					<textarea rows="1" cols="40" class="form-control" name="comentario"></textarea><br>
					<input type="radio" name="estado_revision" value="Ok"> Ok 
					<input type="radio" name="estado_revision" value="Rev. Lab"> Rev. Lab 
					<input type="radio" name="estado_revision" value="Rev. Super"> Rev. Super 
					<input type="radio" name="estado_revision" value="Pendiente"> Pendiente 					
				</form>
			</td>
		</tr>
		<!---->
		<tr>
			<th>
				Forma Farmaceutica
			</th>
			<?php 

				foreach ($tbl_referencia_ffm->result_array() as $k_tbl_referencia => $v_tbl_referencia)
				{
					if ($valores_tbl_rev_expedientes->CodigoFormaFarmaceutica == $v_tbl_referencia['codigo']) 
					{
						$option_CodigoFormaFarmaceutica[] = sprintf('<option value="%s" selected> %s </option>', $v_tbl_referencia['codigo'], $v_tbl_referencia['nombre_codigo']);
						$valor_actual_CodigoFormaFarmaceutica = $valores_tbl_rev_expedientes->CodigoFormaFarmaceutica;
					}
					else
					{
						$option_CodigoFormaFarmaceutica[] = sprintf('<option value="%s"> %s </option>', $v_tbl_referencia['codigo'], $v_tbl_referencia['nombre_codigo']);
						$valor_actual_CodigoFormaFarmaceutica = null;
					}
				}
				if (strpos($valores_tbl_invima_medicamento->CodigoFormaFarmaceutica_homologado, $valor_actual_CodigoFormaFarmaceutica) !== false ) 
				{
					$color = 'bg-warning';
					if ($valor_actual_CodigoFormaFarmaceutica == $valores_tbl_invima_medicamento->CodigoFormaFarmaceutica_homologado) 
					{
						$color = 'bg-success';
					}
				}
				else
				{
					$color = 'bg-danger';
				}
			?>
			<td class="<?=$color;?>">
				<select class="form_app_m" name="CodigoFormaFarmaceutica">
					<option value="">Seleccione una opción</option>
					<?php foreach ($option_CodigoFormaFarmaceutica as $k_option_CodigoFormaFarmaceutica => $v_option_CodigoFormaFarmaceutica): ?>
						<?= $v_option_CodigoFormaFarmaceutica;?>
					<?php endforeach ?>
				</select>
				<input type="hidden" class="CodigoFormaFarmaceutica" data-json='{"tabla":"tbl_rev_expedientes", "llave":"<?= $valores_tbl_rev_expedientes->id ?>", "valor_viejo":"<?= $valor_actual_CodigoFormaFarmaceutica?>", "campo":"CodigoFormaFarmaceutica"}' value='{"tabla":"tbl_rev_expedientes", "llave":"<?= $valores_tbl_rev_expedientes->id ?>", "valor_viejo":"<?= $valor_actual_CodigoFormaFarmaceutica?>", "campo":"CodigoFormaFarmaceutica"}'>
				<br>
				<b>Invima: </b><?php echo $valores_tbl_invima_medicamento->forma_farmaceutica; ?>
				<hr>
				<div class="comentario">
			 		<?= ( ! empty($comentarios_CodigoFormaFarmaceutica->texto)) ? $comentarios_CodigoFormaFarmaceutica->texto : "";?>
				</div>
			</td>
			<td>
				<form tipoComentario="comentario_en_td_solo" data-comentario='{"clase": "CodigoFormaFarmaceutica"}'>
					<b>Comentario: </b><br>
					<textarea rows="1" cols="40" class="form-control" name="comentario"></textarea><br>
					<input type="radio" name="estado_revision" value="Ok"> Ok 
					<input type="radio" name="estado_revision" value="Rev. Lab"> Rev. Lab 
					<input type="radio" name="estado_revision" value="Rev. Super"> Rev. Super 
					<input type="radio" name="estado_revision" value="Pendiente"> Pendiente 					
				</form>
			</td>
		</tr>
		<!---->
		<tr>
			<th>
				Via Administracion
			</th>
			<td>
			 	<?php //echo $valores_tbl_rev_expedientes->CodigoViaAdministracion; ?> 
			 	<?php $CodigoViaAdministracion = explode("&", $valores_tbl_rev_expedientes->CodigoViaAdministracion); ?> 
			 	<?php 
			 		$i = 0;
			 		$nuevo_array_codigo_via_administracion = array();
			 		// Robin arregla esto!!!
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
				<select id="select_via_administracion" name="codigo_via_administracion" class="form_app_m codigo_via_administracion" multiple="multiple" data-json='{"tabla":"tbl_rev_expedientes", "llave":"<?= $valores_tbl_rev_expedientes->id; ?>", "valor_viejo":"<?= $valores_tbl_rev_expedientes->CodigoViaAdministracion; ?>", "campo":"CodigoViaAdministracion"}'>
				 	<?php foreach ($nuevo_array_codigo_via_administracion as $k_nuevo_array_codigo_via_administracion => $v_nuevo_array_codigo_via_administracion): ?>
    					<option value="<?= $v_nuevo_array_codigo_via_administracion['codigo']?>" <?php echo ($v_nuevo_array_codigo_via_administracion['selected']) ? "selected" : "" ; ?>><?= $v_nuevo_array_codigo_via_administracion['nombre_codigo']?></option>
				 	<?php endforeach ?>
				</select>
				<br>
				<b>Invima: </b><?php echo $valores_tbl_invima_medicamento->ViasAdministracion; ?>
				<hr>
				<div class="comentario">
			 		<?= ( ! empty($comentarios_CodigoViaAdministracion->texto)) ? $comentarios_CodigoViaAdministracion->texto : "";?>
				</div>
			</td>
			<td>
				<form tipoComentario="comentario_en_td_solo" data-comentario='{"clase": "codigo_via_administracion"}'>
					<b>Comentario: </b><br>
					<textarea rows="1" cols="40" class="form-control" name="comentario"></textarea><br>
					<input type="radio" name="estado_revision" value="Ok"> Ok 
					<input type="radio" name="estado_revision" value="Rev. Lab"> Rev. Lab 
					<input type="radio" name="estado_revision" value="Rev. Super"> Rev. Super 
					<input type="radio" name="estado_revision" value="Pendiente"> Pendiente 					
				</form>
			</td>
		</tr>
	</tbody>
</table>
<div class="<?= $tbl_invima_pa_homologado_texto->row()->coincidencia; ?>">
	<?= $tbl_invima_pa_homologado_texto->row()->texto;?>
</div>
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
				<td>
					<?php 
						$coincide_nombre_codigo = false;
						foreach ($tbl_referencia_dci->result_array() as $k_tbl_referencia_dci => $v_tbl_referencia_dci)
						{
							if ($v_tbl_rev_expediente_pa->NombrePrincipioActivo == $v_tbl_referencia_dci['nombre_codigo']) 
								{
									$select_NombrePrincipioActivo[] = sprintf('<option value="%s" selected> %s </option>', $v_tbl_referencia_dci['nombre_codigo'], $v_tbl_referencia_dci['nombre_codigo']);
									//$valor_actual_CodigoFormaFarmaceutica = $valores_tbl_rev_expedientes->CodigoFormaFarmaceutica;
									$coincide_nombre_codigo = true;
								}
								else
								{
									$select_NombrePrincipioActivo[] = sprintf('<option value="%s"> %s </option>', $v_tbl_referencia_dci['nombre_codigo'], $v_tbl_referencia_dci['nombre_codigo']);
								}
						} 
					?>
					<input type="hidden" nombre-tbl-rev-expediente-pa="<?= $v_tbl_rev_expediente_pa->NombrePrincipioActivo; ?>">
					<?php if ( ! $coincide_nombre_codigo): ?>
						<div class="bg-danger">
							<?= $v_tbl_rev_expediente_pa->NombrePrincipioActivo; ?>
						</div>
					<?php endif ?>
					<!--no estoy seguro, pero si no coincide, el select coge el primer valor-->
					<!-- Este select consume MUCHA! memoria, se incremento de 128M a 512M -->
					<select class="form_app_m NombrePrincipioActivo" name="NombrePrincipioActivo" data-json='{"tabla":"tbl_rev_expediente_pa", "llave":"<?= $v_tbl_rev_expediente_pa->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pa->NombrePrincipioActivo; ?>", "campo":"NombrePrincipioActivo"}' >
						<?php foreach ($select_NombrePrincipioActivo as $k_select_NombrePrincipioActivo => $v_select_NombrePrincipioActivo): ?>
							 	<?= $v_select_NombrePrincipioActivo; ?>
						<?php endforeach ?> 
					</select>
					<p class="comentario">
					<?php foreach ($comentarios_NombrePrincipioActivo as $k_comentarios_NombrePrincipioActivo => $v_comentarios_NombrePrincipioActivo): ?>
						<?php if ($v_tbl_rev_expediente_pa->id == $v_comentarios_NombrePrincipioActivo['llave']): ?>
							<span><?= $v_comentarios_NombrePrincipioActivo['texto']; ?></span>
						<?php endif ?>
					<?php endforeach ?>
					</p>
					<form tipoComentario="comentario_en_td_con_el_input">
						<b>Comentario: </b><br>
						<textarea rows="1" cols="40" class="form-control" name="comentario"></textarea><br>
						<input type="radio" name="estado_revision" value="Ok"> Ok 
						<input type="radio" name="estado_revision" value="Rev. Lab"> Rev. Lab 
						<input type="radio" name="estado_revision" value="Rev. Super"> Rev. Super 
						<input type="radio" name="estado_revision" value="Pendiente"> Pendiente 						
					</form>
				</td>
				<td>
					<select class="form_app_m" name="IdentificadorTipoConcentracionEstandarizada" data-json='{"tabla":"tbl_rev_expediente_pa", "llave":"<?= $v_tbl_rev_expediente_pa->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pa->IdentificadorTipoConcentracionEstandarizada; ?>", "campo":"IdentificadorTipoConcentracionEstandarizada"}'>
						<option value="">Seleccione una opción</option>
						<?php 
							foreach ($tbl_referencia_tepcyum->result_array() as $k_tbl_referencia => $v_tbl_referencia)
							{
								if ($v_tbl_rev_expediente_pa->IdentificadorTipoConcentracionEstandarizada == $v_tbl_referencia['codigo']) 
								{
									echo sprintf('<option value="%s" selected> %s </option>', $v_tbl_referencia['codigo'], $v_tbl_referencia['nombre_codigo']);
									//$valor_actual_CodigoFormaFarmaceutica = $valores_tbl_rev_expedientes->CodigoFormaFarmaceutica;
								}
								else
								{
									echo sprintf('<option value="%s"> %s </option>', $v_tbl_referencia['codigo'], $v_tbl_referencia['nombre_codigo']);
								}
							}
						?>
					</select>
					<p class="comentario">
					<?php foreach ($comentarios_IdentificadorTipoConcentracionEstandarizada as $k_comentarios_IdentificadorTipoConcentracionEstandarizada => $v_comentarios_IdentificadorTipoConcentracionEstandarizada): ?>
						<?php if ($v_tbl_rev_expediente_pa->id == $v_comentarios_IdentificadorTipoConcentracionEstandarizada['llave']): ?>
							<span><?= $v_comentarios_IdentificadorTipoConcentracionEstandarizada['texto']; ?></span>
						<?php endif ?>
					<?php endforeach ?>
					</p>
					<form tipoComentario="comentario_en_td_con_el_input">
						<b>Comentario: </b><br>
						<textarea rows="1" cols="40" class="form-control" name="comentario"></textarea><br>
						<input type="radio" name="estado_revision" value="Ok"> Ok 
						<input type="radio" name="estado_revision" value="Rev. Lab"> Rev. Lab 
						<input type="radio" name="estado_revision" value="Rev. Super"> Rev. Super 
						<input type="radio" name="estado_revision" value="Pendiente"> Pendiente 						
					</form>
				</td>
				<td>
					<input type="text" class="form_app_m CantidadEstandarizadaPrincipioActivo" name="CantidadEstandarizadaPrincipioActivo" data-json='{"tabla":"tbl_rev_expediente_pa", "llave":"<?= $v_tbl_rev_expediente_pa->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pa->CantidadEstandarizadaPrincipioActivo; ?>", "campo":"CantidadEstandarizadaPrincipioActivo"}' value="<?= $v_tbl_rev_expediente_pa->CantidadEstandarizadaPrincipioActivo; ?>">
					<p class="comentario">
					<?php foreach ($comentarios_CantidadEstandarizadaPrincipioActivo as $k_comentarios_CantidadEstandarizadaPrincipioActivo => $v_comentarios_CantidadEstandarizadaPrincipioActivo): ?>
						<?php if ($v_tbl_rev_expediente_pa->id == $v_comentarios_CantidadEstandarizadaPrincipioActivo['llave']): ?>
							<span><?= $v_comentarios_CantidadEstandarizadaPrincipioActivo['texto']; ?></span>
						<?php endif ?>
					<?php endforeach ?>
					</p>
					<form tipoComentario="comentario_en_td_con_el_input">
						<b>Comentario: </b><br>
						<textarea rows="1" cols="40" class="form-control" name="comentario"></textarea><br>
						<input type="radio" name="estado_revision" value="Ok"> Ok 
						<input type="radio" name="estado_revision" value="Rev. Lab"> Rev. Lab 
						<input type="radio" name="estado_revision" value="Rev. Super"> Rev. Super 
						<input type="radio" name="estado_revision" value="Pendiente"> Pendiente 						
					</form>
				</td>
				<td>
					<select class="form_app_m" name="CodigoUnidadMedidaEstandarizadaPrincipioActivo" data-json='{"tabla":"tbl_rev_expediente_pa", "llave":"<?= $v_tbl_rev_expediente_pa->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pa->CodigoUnidadMedidaEstandarizadaPrincipioActivo; ?>", "campo":"CodigoUnidadMedidaEstandarizadaPrincipioActivo"}'>
						<option value="">Seleccione una opción</option>
						<?php 
							foreach ($tbl_referencia_umm->result_array() as $k_tbl_referencia => $v_tbl_referencia)
							{
								if ($v_tbl_rev_expediente_pa->CodigoUnidadMedidaEstandarizadaPrincipioActivo == $v_tbl_referencia['codigo']) 
								{
									echo sprintf('<option value="%s" selected> %s </option>', $v_tbl_referencia['codigo'], $v_tbl_referencia['nombre_codigo']);
									//$valor_actual_CodigoFormaFarmaceutica = $valores_tbl_rev_expedientes->CodigoFormaFarmaceutica;
								}
								else
								{
									echo sprintf('<option value="%s"> %s </option>', $v_tbl_referencia['codigo'], $v_tbl_referencia['nombre_codigo']);
								}
							}
						?>
					</select>
					<p class="comentario">
					<?php foreach ($comentarios_CodigoUnidadMedidaEstandarizadaPrincipioActivo as $k_comentarios_CodigoUnidadMedidaEstandarizadaPrincipioActivo => $v_comentarios_CodigoUnidadMedidaEstandarizadaPrincipioActivo): ?>
						<?php if ($v_tbl_rev_expediente_pa->id == $v_comentarios_CodigoUnidadMedidaEstandarizadaPrincipioActivo['llave']): ?>
							<span><?= $v_comentarios_CodigoUnidadMedidaEstandarizadaPrincipioActivo['texto']; ?></span>
						<?php endif ?>
					<?php endforeach ?>
					</p>
					<form tipoComentario="comentario_en_td_con_el_input">
						<b>Comentario: </b><br>
						<textarea rows="1" cols="40" class="form-control" name="comentario"></textarea><br>
						<input type="radio" name="estado_revision" value="Ok"> Ok 
						<input type="radio" name="estado_revision" value="Rev. Lab"> Rev. Lab 
						<input type="radio" name="estado_revision" value="Rev. Super"> Rev. Super 
						<input type="radio" name="estado_revision" value="Pendiente"> Pendiente 						
					</form>
				</td>
				<td>
					<input type="text" class="form_app_m CantidadEstandarizadaMedicamentoContenidoPrincipioActivo" name="CantidadEstandarizadaMedicamentoContenidoPrincipioActivo" data-json='{"tabla":"tbl_rev_expediente_pa", "llave":"<?= $v_tbl_rev_expediente_pa->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pa->CantidadEstandarizadaMedicamentoContenidoPrincipioActivo; ?>", "campo":"CantidadEstandarizadaMedicamentoContenidoPrincipioActivo"}' value="<?= $v_tbl_rev_expediente_pa->CantidadEstandarizadaMedicamentoContenidoPrincipioActivo; ?>">
					<p class="comentario">
					<?php foreach ($comentarios_CantidadEstandarizadaMedicamentoContenidoPrincipioActivo as $k_comentarios_CantidadEstandarizadaMedicamentoContenidoPrincipioActivo => $v_comentarios_CantidadEstandarizadaMedicamentoContenidoPrincipioActivo): ?>
						<?php if ($v_tbl_rev_expediente_pa->id == $k_comentarios_CantidadEstandarizadaMedicamentoContenidoPrincipioActivo['llave']): ?>
							<span><?= $k_comentarios_CantidadEstandarizadaMedicamentoContenidoPrincipioActivo['texto']; ?></span>
						<?php endif ?>
					<?php endforeach ?>
					</p>
					<form tipoComentario="comentario_en_td_con_el_input">
						<b>Comentario: </b><br>
						<textarea rows="1" cols="40" class="form-control" name="comentario"></textarea><br>
						<input type="radio" name="estado_revision" value="Ok"> Ok 
						<input type="radio" name="estado_revision" value="Rev. Lab"> Rev. Lab 
						<input type="radio" name="estado_revision" value="Rev. Super"> Rev. Super 
						<input type="radio" name="estado_revision" value="Pendiente"> Pendiente 						
					</form>
				</td>
				<td>
					<select class="form_app_m" name="CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo" data-json='{"tabla":"tbl_rev_expediente_pa", "llave":"<?= $v_tbl_rev_expediente_pa->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pa->CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo; ?>", "campo":"CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo"}'>
						<option value="">Seleccione una opción</option>
						<?php 
							foreach ($tbl_referencia_umm->result_array() as $k_tbl_referencia => $v_tbl_referencia)
							{
								if ($v_tbl_rev_expediente_pa->CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo == $v_tbl_referencia['codigo']) 
								{
									echo sprintf('<option value="%s" selected> %s </option>', $v_tbl_referencia['codigo'], $v_tbl_referencia['nombre_codigo']);
									//$valor_actual_CodigoFormaFarmaceutica = $valores_tbl_rev_expedientes->CodigoFormaFarmaceutica;
								}
								else
								{
									echo sprintf('<option value="%s"> %s </option>', $v_tbl_referencia['codigo'], $v_tbl_referencia['nombre_codigo']);
								}
							}
						?>
					</select>
					<p class="comentario">
					<?php foreach ($comentarios_CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo as $k_comentarios_CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo => $v_comentarios_CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo): ?>
						<?php if ($v_tbl_rev_expediente_pa->id == $k_comentarios_CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo['llave']): ?>
							<span><?= $k_comentarios_CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo['texto']; ?></span>
						<?php endif ?>
					<?php endforeach ?>
					</p>
					<form tipoComentario="comentario_en_td_con_el_input">
						<b>Comentario: </b><br>
						<textarea rows="1" cols="40" class="form-control" name="comentario"></textarea><br>
						<input type="radio" name="estado_revision" value="Ok"> Ok 
						<input type="radio" name="estado_revision" value="Rev. Lab"> Rev. Lab 
						<input type="radio" name="estado_revision" value="Rev. Super"> Rev. Super 
						<input type="radio" name="estado_revision" value="Pendiente"> Pendiente 						
					</form>
				</td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>
<!--presentacion comercial -->
<h3>Presentación Comercial</h3>
<table class="table">
	<thead>
		<tr>
			<th>CUM</th>
			<th>U.Contenido</th>
			<th>Capacidad</th>
			<th>U.Capacidad</th>
			<th>Empaque</th>
			<th>Cantidad U.</th>
			<th>Muestra Medica</th>
			<th>Dispositivo</th>
			<th>Marca</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($tbl_rev_expediente_pc->result() as $k_tbl_rev_expediente_pc => $v_tbl_rev_expediente_pc): ?>
		<tr>
			<td colspan="9">

				<?php 

				// LEER ! 

				// aqui comparo los valores de la tabla, para colocar algun color, verde de coincide y rojo si no. Por cada <td>

				# CodigoUnidadContenido
				foreach ($tbl_referencia_upr->result_array() as $k_tbl_referencia => $v_tbl_referencia)
				{
					if ($v_tbl_rev_expediente_pc->CodigoUnidadContenido == $v_tbl_referencia['codigo']) 
					{
						$selec_CodigoUnidadContenido[] = sprintf('<option value="%s" selected> %s </option>', $v_tbl_referencia['codigo'], $v_tbl_referencia['nombre_codigo']);
						$valor_actual_CodigoUnidadContenido = $v_tbl_referencia['nombre_codigo'];
					}
					else
					{
						$selec_CodigoUnidadContenido[] = sprintf('<option value="%s"> %s </option>', $v_tbl_referencia['codigo'], $v_tbl_referencia['nombre_codigo']);
						$valor_actual_CodigoUnidadContenido = null;
					}
				}

				# CodigoUnidadCapacidad
				foreach ($tbl_referencia_umm->result_array() as $k_tbl_referencia => $v_tbl_referencia)
				{
					if ($v_tbl_rev_expediente_pc->CodigoUnidadCapacidad == $v_tbl_referencia['codigo']) 
					{
						$selec_CodigoUnidadCapacidad[] = sprintf('<option value="%s" selected> %s </option>', $v_tbl_referencia['codigo'], $v_tbl_referencia['nombre_codigo']);
						$valor_actual_CodigoUnidadCapacidad = $v_tbl_referencia['nombre_codigo'];
					}
					else
					{
						$selec_CodigoUnidadCapacidad[] = sprintf('<option value="%s"> %s </option>', $v_tbl_referencia['codigo'], $v_tbl_referencia['nombre_codigo']);
						$valor_actual_CodigoUnidadCapacidad = null;
					}
				}

				# CodigoEmpaque
				foreach ($tbl_referencia_upr->result_array() as $k_tbl_referencia => $v_tbl_referencia)
				{
					if ($v_tbl_rev_expediente_pc->CodigoEmpaque == $v_tbl_referencia['codigo']) 
					{
						$select_CodigoEmpaque[] = sprintf('<option value="%s" selected> %s </option>', $v_tbl_referencia['codigo'], $v_tbl_referencia['nombre_codigo']);
						$valor_actual_CodigoEmpaque = $v_tbl_referencia['nombre_codigo'];
					}
					else
					{
						$select_CodigoEmpaque[] = sprintf('<option value="%s"> %s </option>', $v_tbl_referencia['codigo'], $v_tbl_referencia['nombre_codigo']);
						$valor_actual_CodigoEmpaque = null;
					}
				}

				# IdentificadorCondicionEstarRegistradoComoMuestraMedica
				foreach ($tbl_referencia_mm->result_array() as $k_tbl_referencia => $v_tbl_referencia)
				{
					if ($v_tbl_rev_expediente_pc->IdentificadorCondicionEstarRegistradoComoMuestraMedica == $v_tbl_referencia['codigo']) 
					{
						$select_IdentificadorCondicionEstarRegistradoComoMuestraMedica[] = sprintf('<option value="%s" selected> %s </option>', $v_tbl_referencia['codigo'], $v_tbl_referencia['nombre_codigo']);
						$valor_actual_IdentificadorCondicionEstarRegistradoComoMuestraMedica = $v_tbl_referencia['nombre_codigo'];
					}
					else
					{
						$select_IdentificadorCondicionEstarRegistradoComoMuestraMedica[] = sprintf('<option value="%s"> %s </option>', $v_tbl_referencia['codigo'], $v_tbl_referencia['nombre_codigo']);
						$valor_actual_IdentificadorCondicionEstarRegistradoComoMuestraMedica = null;
					}
				}

				# IdentificadorMarca, Nuevo 19 enero, dispositivo
				if ( $tbl_referencia_identificadorMarca->num_rows() > 0 ) 
				{
					foreach ($tbl_referencia_identificadorMarca->result_array() as $k_tbl_referencia => $v_tbl_referencia) 
					{
						if ($v_tbl_rev_expediente_pc->IdentificadorMarca == $v_tbl_referencia['codigo']) 
						{
							$select_IdentificadorMarca[] = sprintf('<option value="%s" selected> %s </option>', $v_tbl_referencia['codigo'], $v_tbl_referencia['nombre_codigo']);
							$valor_actual_IdentificadorMarca = $v_tbl_referencia['nombre_codigo'];
						}
						else
						{
							$select_IdentificadorMarca[] = sprintf('<option value="%s"> %s </option>', $v_tbl_referencia['codigo'], $v_tbl_referencia['nombre_codigo']);
							$valor_actual_IdentificadorMarca = null;
						}
					}
				}
				else
				{
					$select_IdentificadorMarca[] = sprintf('<option value="%s" selected> %s </option>', '0', 'Sin Valores');
					$valor_actual_IdentificadorMarca = null;
				}

				# este foreach revisa los datos de la tabla del invima para encontrar coincidencias
				foreach ($tbl_invima_pc_texto->result() as $k_tbl_invima_pc_texto => $v_tbl_invima_pc_texto) 
				{
					if ($v_tbl_rev_expediente_pc->NumeroConsecutivoCUMPresentacionComercial == $v_tbl_invima_pc_texto->consecutivo) 
					{
						echo $v_tbl_invima_pc_texto->texto;
						
						# CAMBIAR TODOS ESTOS IF POR UNA FUNCION  !! 

						# CodigoUnidadContenido
						if (strpos($v_tbl_invima_pc_texto->texto, $valor_actual_CodigoUnidadContenido) !== false) 
						{
							$color_td_CodigoUnidadContenido = 'bg-success';
						}
						else
						{
							$color_td_CodigoUnidadContenido = 'bg-danger';
						}

						# CapacidadUnidadContenido
						if (strpos($v_tbl_invima_pc_texto->texto, $v_tbl_rev_expediente_pc->CapacidadUnidadContenido) !== false)  
						{
							$color_td_CapacidadUnidadContenido = 'bg-success';
						}
						else
						{
							$color_td_CapacidadUnidadContenido = 'bg-danger';
						}

						# CodigoUnidadCapacidad
						if (strpos($v_tbl_invima_pc_texto->texto, $valor_actual_CodigoUnidadCapacidad) !== false)  
						{
							$color_td_CodigoUnidadCapacidad = 'bg-success';
						}
						else
						{
							$color_td_CodigoUnidadCapacidad = 'bg-danger';
						}

						# CodigoEmpaque
						if (strpos($v_tbl_invima_pc_texto->texto, $valor_actual_CodigoEmpaque) !== false)  
						{
							$color_td_CodigoEmpaque = 'bg-success';
						}
						else
						{
							$color_td_CodigoEmpaque = 'bg-danger';
						}

						# CantidadUnidadesContenidoEmpaque
						if (strpos($v_tbl_invima_pc_texto->texto, $v_tbl_rev_expediente_pc->CantidadUnidadesContenidoEmpaque) !== false)  
						{
							$color_td_CantidadUnidadesContenidoEmpaque = 'bg-success';
						}
						else
						{
							$color_td_CantidadUnidadesContenidoEmpaque = 'bg-danger';
						}

						# IdentificadorCondicionEstarRegistradoComoMuestraMedica
						if (strpos($v_tbl_invima_pc_texto->texto, $valor_actual_IdentificadorCondicionEstarRegistradoComoMuestraMedica) !== false)  
						{
							$color_td_IdentificadorCondicionEstarRegistradoComoMuestraMedica = 'bg-success';
						}
						else
						{
							$color_td_IdentificadorCondicionEstarRegistradoComoMuestraMedica = 'bg-danger';
						}

						# Aqui agregar dispositivo nuevo 19 de enero
						if (strpos($v_tbl_invima_pc_texto->texto, $v_tbl_rev_expediente_pc->DispositivosAsociados) !== false)  
						{
							$color_td_DispositivosAsociados = 'bg-success';
						}
						else
						{
							$color_td_DispositivosAsociados = 'bg-danger';
						}

						# IdentificadorCondicionEstarRegistradoComoMuestraMedica
						if (strpos($v_tbl_invima_pc_texto->texto, $valor_actual_IdentificadorMarca) !== false)  
						{
							$color_td_IdentificadorMarca = 'bg-success';
						}
						else
						{
							$color_td_IdentificadorMarca = 'bg-danger';
						}
					}
				}
				?>
			</td>
		</tr>
		<tr>
			<td class="<?= $color_td_CodigoUnidadContenido; ?>">
				<?= $v_tbl_rev_expediente_pc->NumeroConsecutivoCUMPresentacionComercial; ?>
			</td>
			<td class="<?= $color_td_CodigoUnidadContenido; ?>">
				<select class="form_app_m" name="CodigoUnidadContenido" data-json='{"tabla":"tbl_rev_expediente_pc", "llave":"<?= $v_tbl_rev_expediente_pc->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pc->CodigoUnidadContenido; ?>", "campo":"CodigoUnidadContenido"}'>
					<option value="">Seleccione una opción</option>
					<?php foreach ($selec_CodigoUnidadContenido as $k_selec_CodigoUnidadContenido => $v_selec_CodigoUnidadContenido): ?>
						<?= $v_selec_CodigoUnidadContenido;?>
					<?php endforeach ?>
				</select>
				
				<p class="comentario">
				<?php foreach ($comentarios_CodigoUnidadContenido as $k_comentarios_CodigoUnidadContenido => $v_comentarios_CodigoUnidadContenido): ?>
					<?php if ($v_tbl_rev_expediente_pc->id == $v_comentarios_CodigoUnidadContenido['llave']): ?>
						<span><?= $v_comentarios_CodigoUnidadContenido['texto']; ?></span>
					<?php endif ?>
				<?php endforeach ?>
				</p>
				<form tipoComentario="comentario_en_td_con_el_input">
					<b>Comentario: </b><br>
					<textarea rows="1" cols="40" class="form-control" name="comentario"></textarea><br>
					<input type="radio" name="estado_revision" value="Ok"> Ok 
					<input type="radio" name="estado_revision" value="Rev. Lab"> Rev. Lab 
					<input type="radio" name="estado_revision" value="Rev. Super"> Rev. Super 
					<input type="radio" name="estado_revision" value="Pendiente"> Pendiente 					
				</form>
			</td>
			<td class="<?= $color_td_CapacidadUnidadContenido; ?>">
				<input type="text" class="form_app_m CapacidadUnidadContenido" name="CapacidadUnidadContenido" data-json='{"tabla":"tbl_rev_expediente_pc", "llave":"<?= $v_tbl_rev_expediente_pc->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pc->CapacidadUnidadContenido; ?>", "campo":"CapacidadUnidadContenido"}' value="<?= $v_tbl_rev_expediente_pc->CapacidadUnidadContenido; ?>">
				<p class="comentario">
				<?php foreach ($comentarios_CapacidadUnidadContenido as $k_comentarios_CapacidadUnidadContenido => $v_comentarios_CapacidadUnidadContenido): ?>
					<?php if ($v_tbl_rev_expediente_pc->id == $v_comentarios_CapacidadUnidadContenido['llave']): ?>
						<span><?= $v_comentarios_CapacidadUnidadContenido['texto']; ?></span>
					<?php endif ?>
				<?php endforeach ?>
				</p>
				<form tipoComentario="comentario_en_td_con_el_input">
					<b>Comentario: </b><br>
					<textarea rows="1" cols="40" class="form-control" name="comentario"></textarea><br>
					<input type="radio" name="estado_revision" value="Ok"> Ok 
					<input type="radio" name="estado_revision" value="Rev. Lab"> Rev. Lab 
					<input type="radio" name="estado_revision" value="Rev. Super"> Rev. Super 
					<input type="radio" name="estado_revision" value="Pendiente"> Pendiente 					
				</form>
			</td>
			<td class="<?= $color_td_CodigoUnidadCapacidad;?>">
				<select class="form_app_m" name="CodigoUnidadCapacidad" data-json='{"tabla":"tbl_rev_expediente_pc", "llave":"<?= $v_tbl_rev_expediente_pc->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pc->CodigoUnidadCapacidad; ?>", "campo":"CodigoUnidadCapacidad"}'>
					<option value="">Seleccione una opción</option>
					<?php foreach ($selec_CodigoUnidadCapacidad as $k_selec_CodigoUnidadCapacidad => $v_selec_CodigoUnidadCapacidad): ?>
						<?= $v_selec_CodigoUnidadCapacidad;?>
					<?php endforeach ?>
				</select>
				
				<p class="comentario">
				<?php foreach ($comentarios_CodigoUnidadCapacidad as $k_comentarios_CodigoUnidadCapacidad => $v_comentarios_CodigoUnidadCapacidad): ?>
					<?php if ($v_tbl_rev_expediente_pc->id == $v_comentarios_CodigoUnidadCapacidad['llave']): ?>
						<span><?= $v_comentarios_CodigoUnidadCapacidad['texto']; ?></span>
					<?php endif ?>
				<?php endforeach ?>
				</p>
				<form tipoComentario="comentario_en_td_con_el_input">
					<b>Comentario: </b><br>
					<textarea rows="1" cols="40" class="form-control" name="comentario"></textarea><br>
					<input type="radio" name="estado_revision" value="Ok"> Ok 
					<input type="radio" name="estado_revision" value="Rev. Lab"> Rev. Lab 
					<input type="radio" name="estado_revision" value="Rev. Super"> Rev. Super 
					<input type="radio" name="estado_revision" value="Pendiente"> Pendiente 					
				</form>
			</td>
			<td class="<?= $color_td_CodigoEmpaque;?>">
				<select class="form_app_m" name="CodigoEmpaque" data-json='{"tabla":"tbl_rev_expediente_pc", "llave":"<?= $v_tbl_rev_expediente_pc->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pc->CodigoEmpaque; ?>", "campo":"CodigoEmpaque"}'>
					<option value="">Seleccione una opción</option>
					<?php foreach ($select_CodigoEmpaque as $k_select_CodigoEmpaque => $v_select_CodigoEmpaque): ?>
						<?= $v_select_CodigoEmpaque;?>
					<?php endforeach ?>
				</select>
				
				<p class="comentario">
				<?php foreach ($comentarios_CodigoEmpaque as $k_comentarios_CodigoEmpaque => $v_comentarios_CodigoEmpaque): ?>
					<?php if ($v_tbl_rev_expediente_pc->id == $v_comentarios_CodigoEmpaque['llave']): ?>
						<span><?= $v_comentarios_CodigoEmpaque['texto']; ?></span>
					<?php endif ?>
				<?php endforeach ?>
				</p>
				<form tipoComentario="comentario_en_td_con_el_input">
					<b>Comentario: </b><br>
					<textarea rows="1" cols="40" class="form-control" name="comentario"></textarea><br>
					<input type="radio" name="estado_revision" value="Ok"> Ok 
					<input type="radio" name="estado_revision" value="Rev. Lab"> Rev. Lab 
					<input type="radio" name="estado_revision" value="Rev. Super"> Rev. Super 
					<input type="radio" name="estado_revision" value="Pendiente"> Pendiente 					
				</form>
			</td>
			<td class="<?= $color_td_CantidadUnidadesContenidoEmpaque;?>">
				<input type="text" class="form_app_m CantidadUnidadesContenidoEmpaque" name="CantidadUnidadesContenidoEmpaque" data-json='{"tabla":"tbl_rev_expediente_pc", "llave":"<?= $v_tbl_rev_expediente_pc->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pc->CantidadUnidadesContenidoEmpaque; ?>", "campo":"CantidadUnidadesContenidoEmpaque"}' value="<?= $v_tbl_rev_expediente_pc->CantidadUnidadesContenidoEmpaque; ?>">
				<p class="comentario">
				<?php foreach ($comentarios_CantidadUnidadesContenidoEmpaque as $k_comentarios_CantidadUnidadesContenidoEmpaque => $v_comentarios_CantidadUnidadesContenidoEmpaque): ?>
					<?php if ($v_tbl_rev_expediente_pc->id == $v_comentarios_CantidadUnidadesContenidoEmpaque['llave']): ?>
						<span><?= $v_comentarios_CantidadUnidadesContenidoEmpaque['texto']; ?></span>
					<?php endif ?>
				<?php endforeach ?>
				</p>
				<form tipoComentario="comentario_en_td_con_el_input">
					<b>Comentario: </b><br>
					<textarea rows="1" cols="40" class="form-control" name="comentario"></textarea><br>
					<input type="radio" name="estado_revision" value="Ok"> Ok 
					<input type="radio" name="estado_revision" value="Rev. Lab"> Rev. Lab 
					<input type="radio" name="estado_revision" value="Rev. Super"> Rev. Super 
					<input type="radio" name="estado_revision" value="Pendiente"> Pendiente 					
				</form>
			</td>
			<td class="<?= $color_td_IdentificadorCondicionEstarRegistradoComoMuestraMedica;?>">
				<select class="form_app_m" name="IdentificadorCondicionEstarRegistradoComoMuestraMedica" data-json='{"tabla":"tbl_rev_expediente_pc", "llave":"<?= $v_tbl_rev_expediente_pc->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pc->IdentificadorCondicionEstarRegistradoComoMuestraMedica; ?>", "campo":"IdentificadorCondicionEstarRegistradoComoMuestraMedica"}'>
					<option value="">Seleccione una opción</option>
					<?php foreach ($select_IdentificadorCondicionEstarRegistradoComoMuestraMedica as $k_select_IdentificadorCondicionEstarRegistradoComoMuestraMedica => $v_select_IdentificadorCondicionEstarRegistradoComoMuestraMedica): ?>
						<?= $v_select_IdentificadorCondicionEstarRegistradoComoMuestraMedica;?>
					<?php endforeach ?>
				</select>
				
				<p class="comentario">
				<?php foreach ($comentarios_IdentificadorCondicionEstarRegistradoComoMuestraMedica as $k_comentarios_IdentificadorCondicionEstarRegistradoComoMuestraMedica => $v_comentarios_IdentificadorCondicionEstarRegistradoComoMuestraMedica): ?>
					<?php if ($v_tbl_rev_expediente_pc->id == $v_comentarios_IdentificadorCondicionEstarRegistradoComoMuestraMedica['llave']): ?>
						<span><?= $v_comentarios_IdentificadorCondicionEstarRegistradoComoMuestraMedica['texto']; ?></span>
					<?php endif ?>
				<?php endforeach ?>
				</p>
				<form tipoComentario="comentario_en_td_con_el_input">
					<b>Comentario: </b><br>
					<textarea rows="1" cols="40" class="form-control" name="comentario"></textarea><br>
					<input type="radio" name="estado_revision" value="Ok"> Ok 
					<input type="radio" name="estado_revision" value="Rev. Lab"> Rev. Lab 
					<input type="radio" name="estado_revision" value="Rev. Super"> Rev. Super 
					<input type="radio" name="estado_revision" value="Pendiente"> Pendiente 					
				</form>
			</td>
			<!-- Nuevo 19 enero, dispositivo -->
			<td class="<?= $color_td_DispositivosAsociados; ?>">
				<input type="text" class="form_app_m DispositivosAsociados" name="DispositivosAsociados" data-json='{"tabla":"tbl_rev_expediente_pc", "llave":"<?= $v_tbl_rev_expediente_pc->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pc->DispositivosAsociados; ?>", "campo":"DispositivosAsociados"}' value="<?= $v_tbl_rev_expediente_pc->DispositivosAsociados; ?>">
				<p class="comentario">
				<?php foreach ($comentarios_DispositivosAsociados as $k_comentarios_DispositivosAsociados => $v_comentarios_DispositivosAsociados): ?>
					<?php if ($v_tbl_rev_expediente_pc->id == $v_comentarios_DispositivosAsociados['llave']): ?>
						<span><?= $v_comentarios_DispositivosAsociados['texto']; ?></span>
					<?php endif ?>
				<?php endforeach ?>
				</p>
				<form tipoComentario="comentario_en_td_con_el_input">
					<b>Comentario: </b><br>
					<textarea rows="1" cols="40" class="form-control" name="comentario"></textarea><br>
					<input type="radio" name="estado_revision" value="Ok"> Ok 
					<input type="radio" name="estado_revision" value="Rev. Lab"> Rev. Lab 
					<input type="radio" name="estado_revision" value="Rev. Super"> Rev. Super 
					<input type="radio" name="estado_revision" value="Pendiente"> Pendiente 					
				</form>
			</td>
			<!-- IdentificadorMarca -->
			<td class="<?= $color_td_IdentificadorMarca;?>">
				<select class="form_app_m" name="IdentificadorMarca" data-json='{"tabla":"tbl_rev_expediente_pc", "llave":"<?= $v_tbl_rev_expediente_pc->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pc->IdentificadorMarca; ?>", "campo":"IdentificadorMarca"}'>
					<option value="">Seleccione una opción</option>
					<?php foreach ($select_IdentificadorMarca as $k_select_IdentificadorMarca => $v_select_IdentificadorMarca): ?>
						<?= $v_select_IdentificadorMarca;?>
					<?php endforeach ?>
				</select>
				
				<p class="comentario">
				<?php foreach ($comentarios_IdentificadorMarca as $k_comentarios_IdentificadorMarca => $v_comentarios_IdentificadorMarca): ?>
					<?php if ($v_tbl_rev_expediente_pc->id == $v_comentarios_IdentificadorMarca['llave']): ?>
						<span><?= $v_comentarios_IdentificadorMarca['texto']; ?></span>
					<?php endif ?>
				<?php endforeach ?>
				</p>
				<form tipoComentario="comentario_en_td_con_el_input">
					<b>Comentario: </b><br>
					<textarea rows="1" cols="40" class="form-control" name="comentario"></textarea><br>
					<input type="radio" name="estado_revision" value="Ok"> Ok 
					<input type="radio" name="estado_revision" value="Rev. Lab"> Rev. Lab 
					<input type="radio" name="estado_revision" value="Rev. Super"> Rev. Super 
					<input type="radio" name="estado_revision" value="Pendiente"> Pendiente 					
				</form>
			</td>
			<!-- /19 enero -->

		</tr>
		<?php endforeach ?>
	</tbody>
</table>
<h3>Presentación Comercial P.A.</h3>
<!--presentacion comercial pricipio activo-->
<table class="table">
	<thead>
		<tr>
			<th>CUM - P.A.</th>
			<th>Texto</th>
			<th>Cantidad Calculada</th>
			<th>Cantidad</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($tbl_rev_expediente_pc_pa->result() as $k_tbl_rev_expediente_pc_pa => $v_tbl_rev_expediente_pc_pa): ?>
		<tr>
			<td>
				<p><?= $v_tbl_rev_expediente_pc_pa->NumeroConsecutivoCUMPresentacionComercial; ?> - <?= $v_tbl_rev_expediente_pc_pa->NombrePrincipioActivo; ?></p>
			</td>
			<td>
				<p><?= $v_tbl_rev_expediente_pc_pa->texto;?></p>
			</td>
			<td>
				<p><?= $v_tbl_rev_expediente_pc_pa->cantidad_calculada;?></p>
				<?php 
					if ($v_tbl_rev_expediente_pc_pa->cantidad_calculada == $v_tbl_rev_expediente_pc_pa->CantidadPrincipioActivoPresentacionComercial) 
					{
						$color_td_CantidadPrincipioActivoPresentacionComercial = 'bg-success';	
					}
					else
					{
						$color_td_CantidadPrincipioActivoPresentacionComercial = 'bg-danger';
					}
				 ?>
			</td>
			<td class="<?= $color_td_CantidadPrincipioActivoPresentacionComercial;?>">
				<input type="text" class="form_app_m CantidadPrincipioActivoPresentacionComercial" name="CantidadPrincipioActivoPresentacionComercial" data-json='{"tabla":"tbl_rev_expediente_pc_pa", "llave":"<?= $v_tbl_rev_expediente_pc_pa->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pc_pa->CantidadPrincipioActivoPresentacionComercial; ?>", "campo":"CantidadPrincipioActivoPresentacionComercial"}' value="<?= $v_tbl_rev_expediente_pc_pa->CantidadPrincipioActivoPresentacionComercial; ?>">
				<p class="comentario">
				<?php foreach ($comentarios_CantidadPrincipioActivoPresentacionComercial as $k_comentarios_CantidadPrincipioActivoPresentacionComercial => $v_comentarios_CantidadPrincipioActivoPresentacionComercial): ?>
					<?php if ($v_tbl_rev_expediente_pc_pa->id == $v_comentarios_CantidadPrincipioActivoPresentacionComercial['llave']): ?>
						<span><?= $v_comentarios_CantidadPrincipioActivoPresentacionComercial['texto']; ?></span>
					<?php endif ?>
				<?php endforeach ?>
				</p>
				<form tipoComentario="comentario_en_td_con_el_input">
					<b>Comentario: </b><br>
					<textarea rows="1" cols="40" class="form-control" name="comentario"></textarea><br>
					<input type="radio" name="estado_revision" value="Ok"> Ok 
					<input type="radio" name="estado_revision" value="Rev. Lab"> Rev. Lab 
					<input type="radio" name="estado_revision" value="Rev. Super"> Rev. Super 
					<input type="radio" name="estado_revision" value="Pendiente"> Pendiente 					
				</form>
			</td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>
<br> 	 	 
<button class="btn btn-danger expediente_terminado" type="button">Terminado</button>

<hr>
<!-- Initialize the plugin: -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#select_via_administracion').multiselect();
        
        /*var urlImagenGoogle = "https://ajax.googleapis.com/ajax/services/search/images?v=1.0&q="+$(".nombre_del_producto_invima").val();
       	$.ajax({
		  	type: 'GET',
		  	contentType: "application/json",
		  	dataType: 'jsonp',
			url: urlImagenGoogle,
			success: function(respuesta){
				$(".img-google-1").attr("src", respuesta.responseData.results[0].url);
				$(".img-google-2").attr("src", respuesta.responseData.results[1].url);
				$(".img-google-3").attr("src", respuesta.responseData.results[2].url);
			}
		});*/

        $("input:radio").click(function(){
        	var objeto_actual = $(this); 
    		if(objeto_actual.attr('name') == "estado_revision")
    		{
    			console.log(objeto_actual.parent());
    			console.log(objeto_actual.parent().attr("tipoComentario"));
    			var tipoForm = objeto_actual.parent().attr("tipoComentario");
    			// cada if tiene un ajax, por que segun la ubicacion del comentario se agregara tambien la respuesta asincronica
    			if (tipoForm == 'comentario_en_td_con_el_input') 
    			{
    				var valorJSON_campo_fomulario = objeto_actual.parent().prev().prev().data("json");
					var valoresComentario = objeto_actual.parent().serializeArray();

					// si el input type=radio es null
					/*if (typeof valoresComentario[1] == "undefined") 
					{
						valoresComentario[1] = { value:'Pendiente'};
					}*/

					valoresEnvio = {
						tabla: valorJSON_campo_fomulario.tabla,
						llave: valorJSON_campo_fomulario.llave,
						campo: valorJSON_campo_fomulario.campo,
						valor: valorJSON_campo_fomulario.valor_viejo,
						estado_revision: valoresComentario[1].value, // [1] es el input type=radio
						comentario: valoresComentario[0].value, // [0] comentario
						expediente: $(".NumeroExpediente").val()
					}

					console.log('comentario comentario_en_td_con_el_input', valoresEnvio);
					$(".img-preload").show();
					$.ajax({
						type: "POST",
						url: "/medicamentos/guardar_comentario",
						data: valoresEnvio
					}).done(function(respuesta){
						$(".img-preload").hide();
						console.log('respuesta comentario (1): ', respuesta);
						console.log('mapa del comentario', objeto_actual);
						objeto_actual.parent().parent().find('.comentario').html(respuesta);
						// limpio formulario comentario
						objeto_actual.parent()[0].reset();
					});

    			} 
    			else
    			{
					var valorJSON = objeto_actual.parent().data("comentario");
					var valorJSON_campo_fomulario = $("."+valorJSON.clase).data("json"); // error
					
					var valoresComentario = objeto_actual.parent().serializeArray();

					valoresEnvio = {
						tabla: valorJSON_campo_fomulario.tabla,
						llave: valorJSON_campo_fomulario.llave,
						campo: valorJSON_campo_fomulario.campo,
						valor: valorJSON_campo_fomulario.valor_viejo,
						estado_revision: valoresComentario[1].value, // [1] es el input type=radio
						comentario: valoresComentario[0].value, // [0] comentario
						expediente: $(".NumeroExpediente").val()
					}
					console.log('comentario comentario_en_td', valoresEnvio);
					$(".img-preload").show();
					$.ajax({
						type: "POST",
						url: "/medicamentos/guardar_comentario",
						data: valoresEnvio
					}).done(function(respuesta){
						$(".img-preload").hide();
						console.log('respuesta comentario (2): ');
						objeto_actual.parent().parent().prev().find(".comentario").html(respuesta);
    					// limpio formulario comentario
						objeto_actual.parent()[0].reset();
					});
    			};
    			//console.log();
        	}
        });
		// en este evento, el expediente queda como terminado
		$(".expediente_terminado").click(function(){
			$(".img-preload").show();
			// agregar una validacion aqui
			$.ajax({
				type: "POST",
				url: "/medicamentos/expediente_terminado",
				data: { expediente : $(".NumeroExpediente").val() }
			}).done(function(respuesta){
				var url_host = window.location.origin;
				var url_parametros = '/medicamentos/expediente/'+respuesta;
				$(".img-preload").hide();
				window.location.replace(url_host + url_parametros);
			});
		});

		$(".form_app_m").change(function(e){
			var objeto_actual = $(this);
			var valorJSON;
			var valoresEnvio;
			var es_selector_multimple = false;
			//return false;
			/*
			si son mas tablas seria: { "tabla": "tbl_rev_expedientes, tbl_nueva_tabla", "llave" ... ! solo si conserva el mismo ID o llave ! 

			{
				"tabla": 		"tbl_rev_expedientes",
				"llave": 		"<?= $valores_tbl_rev_expedientes->id ?>", 
				"valor_viejo": 	<?= $valores_tbl_rev_expedientes->MarcaSignoDistintivoComercial; ?>, 
				"campo": 		"MarcaSignoDistintivoComercial"
			}
			*/
			switch (objeto_actual.attr("name").toString()) {
				case 'MarcaSignoDistintivoComercial':
					valorJSON = objeto_actual.data("json");
					valoresEnvio = {
						valor_nuevo: objeto_actual.val(),
						valores_JSON: valorJSON,
						expediente: $(".NumeroExpediente").val()
					}
					break;
				case 'IdentificadorFormaComercializacion':
					valorJSON = $(".IdentificadorFormaComercializacion").data("json");
					valoresEnvio = {
						valor_nuevo: objeto_actual.val(),
						valores_JSON: valorJSON,
						expediente: $(".NumeroExpediente").val()
					}
					break;
				case 'CodigoFormaFarmaceutica':
					valorJSON = $(".CodigoFormaFarmaceutica").data("json");
					valoresEnvio = {
						valor_nuevo: objeto_actual.val(),
						valores_JSON: valorJSON,
						expediente: $(".NumeroExpediente").val()
					}
					break;
				case 'codigo_via_administracion':
					valorJSON = $('.codigo_via_administracion').data("json");
					valoresEnvio = {
						valor_nuevo: objeto_actual.val().join('&'),
						valores_JSON: valorJSON,
						expediente: $(".NumeroExpediente").val()
					}
					es_selector_multimple = true;
					break;
				case 'IdentificadorTipoConcentracionEstandarizada':
					console.log('IdentificadorTipoConcentracionEstandarizada', objeto_actual);
					valorJSON = objeto_actual.data("json");
					valoresEnvio = {
						valor_nuevo: objeto_actual.val(),
						valores_JSON: valorJSON,
						expediente: $(".NumeroExpediente").val()
					}
					
					break;
				case 'CodigoUnidadMedidaEstandarizadaPrincipioActivo':
					console.log('CodigoUnidadMedidaEstandarizadaPrincipioActivo', objeto_actual);
					valorJSON = objeto_actual.data("json");
					valoresEnvio = {
						valor_nuevo: objeto_actual.val(),
						valores_JSON: valorJSON,
						expediente: $(".NumeroExpediente").val()
					}
					
					break;
				case 'CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo':
					console.log('CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo', objeto_actual);
					valorJSON = objeto_actual.data("json");
					valoresEnvio = {
						valor_nuevo: objeto_actual.val(),
						valores_JSON: valorJSON,
						expediente: $(".NumeroExpediente").val()
					}
					
					break;
				case 'NombrePrincipioActivo':
					console.log('NombrePrincipioActivo', objeto_actual);
					valorJSON = objeto_actual.data("json");
					valoresEnvio = {
						valor_nuevo: objeto_actual.val(),
						valores_JSON: valorJSON,
						expediente: $(".NumeroExpediente").val()
					}
					
					break;
				
				default:
					// pasan por ahora los (p.a.)
					/*
						CantidadEstandarizadaPrincipioActivo
						CantidadEstandarizadaMedicamentoContenidoPrincipioActivo
					*/
					valorJSON = objeto_actual.data("json");
					valoresEnvio = {
						valor_nuevo: objeto_actual.val(),
						valores_JSON: valorJSON,
						expediente: $(".NumeroExpediente").val()
					}
					break;
			}
			$(".img-preload").show();
			
			$.ajax({
				type: "POST",
				url: "/medicamentos/guardar_expediente_asignado",
				data: valoresEnvio
			}).done(function(respuesta){
				$(".img-preload").hide();
				console.log('respuesta: ', respuesta);
			});
			
			// actualizo el nuevo valor viejo (despues del ajax)
			if (es_selector_multimple) 
			{
				valorJSON.valor_viejo = objeto_actual.val().join('&');
			}
			else
			{
				valorJSON.valor_viejo = objeto_actual.val();
			}

		});

    });
</script>
<img class="img-preload" src="/assets/images/preload.gif">
<style type="text/css">
	select {
    	width: 100%;
	}
	.img-thum{
		max-height: 150px;
	}
	.img-preload{
		display: none;
		position: fixed;
	    top: 1em;
	    right: 1em;
	    width: 2.5em;
	}
</style>