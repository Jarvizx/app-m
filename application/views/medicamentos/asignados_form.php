<?php $valores_tbl_rev_expedientes = $tbl_rev_expedientes->row(); ?>
<?php $valores_tbl_invima_medicamento = $tbl_invima_medicamento->row(); ?>
<div class="img-expediente">
	<img class="img-google-1 img-thum">
	<img class="img-google-2 img-thum">
	<img class="img-google-3 img-thum">
</div>
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
			 	<?= ( ! empty($comentarios_MarcaSignoDistintivoComercial->texto)) ? $comentarios_MarcaSignoDistintivoComercial->texto : "";?>
			</td>
			<td>
				<form>
					<b>Comentario: </b><br>
					<textarea rows="1" cols="40" class="form-control" name="comentario"></textarea><br>
					<input type="radio" name="estado_revision" value="Ok"> Ok 
					<input type="radio" name="estado_revision" value="Rev. Lab"> Rev. Lab 
					<input type="radio" name="estado_revision" value="Rev. Super"> Rev. Super 
					<input type="radio" name="estado_revision" value="Pendiente"> Pendiente 
					<button class="btn btn-primary comentario" type="button" data-comentario='{"clase": "MarcaSignoDistintivoComercial"}'>Enviar</button>
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
			 	<?= ( ! empty($comentarios_IdentificadorFormaComercializacion->texto)) ? $comentarios_IdentificadorFormaComercializacion->texto : "";?>
			</td>
			<td>
				<?php //echo $valores_tbl_invima_medicamento->generico; ?>
				<form>
					<textarea rows="1" cols="40" class="form-control" name="comentario"></textarea><br>
					<input type="radio" name="estado_revision" value="Ok"> Ok 
					<input type="radio" name="estado_revision" value="Rev. Lab"> Rev. Lab 
					<input type="radio" name="estado_revision" value="Rev. Super"> Rev. Super 
					<input type="radio" name="estado_revision" value="Pendiente"> Pendiente 
					<button class="btn btn-primary comentario" type="button" data-comentario='{"clase": "IdentificadorFormaComercializacion"}'>Enviar</button>
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
					<?php foreach ($option_CodigoFormaFarmaceutica as $k_option_CodigoFormaFarmaceutica => $v_option_CodigoFormaFarmaceutica): ?>
						<?= $v_option_CodigoFormaFarmaceutica;?>
					<?php endforeach ?>
				</select>
				<input type="hidden" class="CodigoFormaFarmaceutica" data-json='{"tabla":"tbl_rev_expedientes", "llave":"<?= $valores_tbl_rev_expedientes->id ?>", "valor_viejo":"<?= $valor_actual_CodigoFormaFarmaceutica?>", "campo":"CodigoFormaFarmaceutica"}' value='{"tabla":"tbl_rev_expedientes", "llave":"<?= $valores_tbl_rev_expedientes->id ?>", "valor_viejo":"<?= $valor_actual_CodigoFormaFarmaceutica?>", "campo":"CodigoFormaFarmaceutica"}'>
				<br>
				<b>Invima: </b><?php echo $valores_tbl_invima_medicamento->forma_farmaceutica; ?>
				<hr>
			 	<?= ( ! empty($comentarios_CodigoFormaFarmaceutica->texto)) ? $comentarios_CodigoFormaFarmaceutica->texto : "";?>
			</td>
			<td>
				<form>
					<b>Comentario: </b><br>
					<textarea rows="1" cols="40" class="form-control" name="comentario"></textarea><br>
					<input type="radio" name="estado_revision" value="Ok"> Ok 
					<input type="radio" name="estado_revision" value="Rev. Lab"> Rev. Lab 
					<input type="radio" name="estado_revision" value="Rev. Super"> Rev. Super 
					<input type="radio" name="estado_revision" value="Pendiente"> Pendiente 
					<button class="btn btn-primary comentario" type="button" data-comentario='{"clase": "CodigoFormaFarmaceutica"}'>Enviar</button>
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
			 	<?= ( ! empty($comentarios_CodigoViaAdministracion->texto)) ? $comentarios_CodigoViaAdministracion->texto : "";?>
			</td>
			<td>
				<form>
					<b>Comentario: </b><br>
					<textarea rows="1" cols="40" class="form-control" name="comentario"></textarea><br>
					<input type="radio" name="estado_revision" value="Ok"> Ok 
					<input type="radio" name="estado_revision" value="Rev. Lab"> Rev. Lab 
					<input type="radio" name="estado_revision" value="Rev. Super"> Rev. Super 
					<input type="radio" name="estado_revision" value="Pendiente"> Pendiente 
					<button class="btn btn-primary comentario" type="button" data-comentario='{"clase": "codigo_via_administracion"}'>Enviar</button>
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
					<input type="text" class="form_app_m" class="NombrePrincipioActivo" name="NombrePrincipioActivo" data-json='{"tabla":"tbl_rev_expediente_pa", "llave":"<?= $v_tbl_rev_expediente_pa->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pa->NombrePrincipioActivo; ?>", "campo":"NombrePrincipioActivo"}' value="<?= $v_tbl_rev_expediente_pa->NombrePrincipioActivo; ?>">
					<p>
					<?php foreach ($comentarios_NombrePrincipioActivo as $k_comentarios_NombrePrincipioActivo => $v_comentarios_NombrePrincipioActivo): ?>
						<?php if ($v_tbl_rev_expediente_pa->id == $v_comentarios_NombrePrincipioActivo['llave']): ?>
							<span><?= $v_comentarios_NombrePrincipioActivo['texto']; ?></span>
						<?php endif ?>
					<?php endforeach ?>
					</p>
					<form>
						<b>Comentario: </b><br>
						<textarea rows="1" cols="40" class="form-control" name="comentario"></textarea><br>
						<input type="radio" name="estado_revision" value="Ok"> Ok 
						<input type="radio" name="estado_revision" value="Rev. Lab"> Rev. Lab 
						<input type="radio" name="estado_revision" value="Rev. Super"> Rev. Super 
						<input type="radio" name="estado_revision" value="Pendiente"> Pendiente 
						<button class="btn btn-primary comentario-pa" type="button" data-comentario-pa='{"clase": "NombrePrincipioActivo"}'>Enviar</button>
					</form>
				</td>
				<td>
					<select class="form_app_m" name="IdentificadorTipoConcentracionEstandarizada" data-json='{"tabla":"tbl_rev_expediente_pa", "llave":"<?= $v_tbl_rev_expediente_pa->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pa->IdentificadorTipoConcentracionEstandarizada; ?>", "campo":"IdentificadorTipoConcentracionEstandarizada"}'>
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
					<p>
					<?php foreach ($comentarios_IdentificadorTipoConcentracionEstandarizada as $k_comentarios_IdentificadorTipoConcentracionEstandarizada => $v_comentarios_IdentificadorTipoConcentracionEstandarizada): ?>
						<?php if ($v_tbl_rev_expediente_pa->id == $v_comentarios_IdentificadorTipoConcentracionEstandarizada['llave']): ?>
							<span><?= $v_comentarios_IdentificadorTipoConcentracionEstandarizada['texto']; ?></span>
						<?php endif ?>
					<?php endforeach ?>
					</p>
					<form>
						<b>Comentario: </b><br>
						<textarea rows="1" cols="40" class="form-control" name="comentario"></textarea><br>
						<input type="radio" name="estado_revision" value="Ok"> Ok 
						<input type="radio" name="estado_revision" value="Rev. Lab"> Rev. Lab 
						<input type="radio" name="estado_revision" value="Rev. Super"> Rev. Super 
						<input type="radio" name="estado_revision" value="Pendiente"> Pendiente 
						<button class="btn btn-primary comentario-pa" type="button" data-comentario-pa='{"clase": "IdentificadorTipoConcentracionEstandarizada"}'>Enviar</button>
					</form>
				</td>
				<td>
					<input type="text" class="form_app_m" class="CantidadEstandarizadaPrincipioActivo" name="CantidadEstandarizadaPrincipioActivo" data-json='{"tabla":"tbl_rev_expediente_pa", "llave":"<?= $v_tbl_rev_expediente_pa->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pa->CantidadEstandarizadaPrincipioActivo; ?>", "campo":"CantidadEstandarizadaPrincipioActivo"}' value="<?= $v_tbl_rev_expediente_pa->CantidadEstandarizadaPrincipioActivo; ?>">
					<p>
					<?php foreach ($comentarios_CantidadEstandarizadaPrincipioActivo as $k_comentarios_CantidadEstandarizadaPrincipioActivo => $v_comentarios_CantidadEstandarizadaPrincipioActivo): ?>
						<?php if ($v_tbl_rev_expediente_pa->id == $v_comentarios_CantidadEstandarizadaPrincipioActivo['llave']): ?>
							<span><?= $v_comentarios_CantidadEstandarizadaPrincipioActivo['texto']; ?></span>
						<?php endif ?>
					<?php endforeach ?>
					</p>
					<form>
						<b>Comentario: </b><br>
						<textarea rows="1" cols="40" class="form-control" name="comentario"></textarea><br>
						<input type="radio" name="estado_revision" value="Ok"> Ok 
						<input type="radio" name="estado_revision" value="Rev. Lab"> Rev. Lab 
						<input type="radio" name="estado_revision" value="Rev. Super"> Rev. Super 
						<input type="radio" name="estado_revision" value="Pendiente"> Pendiente 
						<button class="btn btn-primary comentario-pa" type="button" data-comentario-pa='{"clase": "CantidadEstandarizadaPrincipioActivo"}'>Enviar</button>
					</form>
				</td>
				<td>
					<select class="form_app_m" name="CodigoUnidadMedidaEstandarizadaPrincipioActivo" data-json='{"tabla":"tbl_rev_expediente_pa", "llave":"<?= $v_tbl_rev_expediente_pa->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pa->CodigoUnidadMedidaEstandarizadaPrincipioActivo; ?>", "campo":"CodigoUnidadMedidaEstandarizadaPrincipioActivo"}'>
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
					<p>
					<?php foreach ($comentarios_CodigoUnidadMedidaEstandarizadaPrincipioActivo as $k_comentarios_CodigoUnidadMedidaEstandarizadaPrincipioActivo => $v_comentarios_CodigoUnidadMedidaEstandarizadaPrincipioActivo): ?>
						<?php if ($v_tbl_rev_expediente_pa->id == $v_comentarios_CodigoUnidadMedidaEstandarizadaPrincipioActivo['llave']): ?>
							<span><?= $v_comentarios_CodigoUnidadMedidaEstandarizadaPrincipioActivo['texto']; ?></span>
						<?php endif ?>
					<?php endforeach ?>
					</p>
					<form>
						<b>Comentario: </b><br>
						<textarea rows="1" cols="40" class="form-control" name="comentario"></textarea><br>
						<input type="radio" name="estado_revision" value="Ok"> Ok 
						<input type="radio" name="estado_revision" value="Rev. Lab"> Rev. Lab 
						<input type="radio" name="estado_revision" value="Rev. Super"> Rev. Super 
						<input type="radio" name="estado_revision" value="Pendiente"> Pendiente 
						<button class="btn btn-primary comentario-pa" type="button" data-comentario-pa='{"clase": "CodigoUnidadMedidaEstandarizadaPrincipioActivo"}'>Enviar</button>
					</form>
				</td>
				<td>
					<input type="text" class="form_app_m" class="CantidadEstandarizadaMedicamentoContenidoPrincipioActivo" name="CantidadEstandarizadaMedicamentoContenidoPrincipioActivo" data-json='{"tabla":"tbl_rev_expediente_pa", "llave":"<?= $v_tbl_rev_expediente_pa->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pa->CantidadEstandarizadaMedicamentoContenidoPrincipioActivo; ?>", "campo":"CantidadEstandarizadaMedicamentoContenidoPrincipioActivo"}' value="<?= $v_tbl_rev_expediente_pa->CantidadEstandarizadaMedicamentoContenidoPrincipioActivo; ?>">
					<p>
					<?php foreach ($comentarios_CantidadEstandarizadaMedicamentoContenidoPrincipioActivo as $k_comentarios_CantidadEstandarizadaMedicamentoContenidoPrincipioActivo => $v_comentarios_CantidadEstandarizadaMedicamentoContenidoPrincipioActivo): ?>
						<?php if ($v_tbl_rev_expediente_pa->id == $k_comentarios_CantidadEstandarizadaMedicamentoContenidoPrincipioActivo['llave']): ?>
							<span><?= $k_comentarios_CantidadEstandarizadaMedicamentoContenidoPrincipioActivo['texto']; ?></span>
						<?php endif ?>
					<?php endforeach ?>
					</p>
					<form>
						<b>Comentario: </b><br>
						<textarea rows="1" cols="40" class="form-control" name="comentario"></textarea><br>
						<input type="radio" name="estado_revision" value="Ok"> Ok 
						<input type="radio" name="estado_revision" value="Rev. Lab"> Rev. Lab 
						<input type="radio" name="estado_revision" value="Rev. Super"> Rev. Super 
						<input type="radio" name="estado_revision" value="Pendiente"> Pendiente 
						<button class="btn btn-primary comentario-pa" type="button" data-comentario-pa='{"clase": "CantidadEstandarizadaMedicamentoContenidoPrincipioActivo"}'>Enviar</button>
					</form>
				</td>
				<td>
					<select class="form_app_m" name="CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo" data-json='{"tabla":"tbl_rev_expediente_pa", "llave":"<?= $v_tbl_rev_expediente_pa->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pa->CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo; ?>", "campo":"CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo"}'>
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
					<p>
					<?php foreach ($comentarios_CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo as $k_comentarios_CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo => $v_comentarios_CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo): ?>
						<?php if ($v_tbl_rev_expediente_pa->id == $k_comentarios_CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo['llave']): ?>
							<span><?= $k_comentarios_CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo['texto']; ?></span>
						<?php endif ?>
					<?php endforeach ?>
					</p>
					<form>
						<b>Comentario: </b><br>
						<textarea rows="1" cols="40" class="form-control" name="comentario"></textarea><br>
						<input type="radio" name="estado_revision" value="Ok"> Ok 
						<input type="radio" name="estado_revision" value="Rev. Lab"> Rev. Lab 
						<input type="radio" name="estado_revision" value="Rev. Super"> Rev. Super 
						<input type="radio" name="estado_revision" value="Pendiente"> Pendiente 
						<button class="btn btn-primary comentario-pa" type="button" data-comentario-pa='{"clase": "CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo"}'>Enviar</button>
					</form>
				</td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>
			 	 
<button class="btn btn-danger expediente_terminado" type="button">Terminado</button>

<hr>
<!-- Initialize the plugin: -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#select_via_administracion').multiselect();
        
        var urlImagenGoogle = "https://ajax.googleapis.com/ajax/services/search/images?v=1.0&q="+$(".nombre_del_producto_invima").val();
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

		// en este evento, el expediente queda como terminado
		$(".expediente_terminado").click(function(){
			$.ajax({
				type: "POST",
				url: "/medicamentos/expediente_terminado",
				data: { expediente : $(".NumeroExpediente").val() }
			}).done(function(respuesta){
				console.log('respuesta terminado: ', respuesta);
				var url_host = window.location.origin;
				var url_parametros = '/medicamentos/expediente/'+respuesta;
				window.location.replace(url_host + url_parametros);
			});
		});

		// js para los comentarios de invima
		$(".comentario").click(function(){
			var objeto_actual = $(this);
			var valorJSON = objeto_actual.data("comentario");
			var valorJSON_campo_fomulario = $("."+valorJSON.clase).data("json"); // error
			
			var valoresComentario = objeto_actual.parent().serializeArray();

			// si el input type=radio es null
			if (typeof valoresComentario[1] == "undefined") 
			{
				valoresComentario[1] = { value:'Pendiente'};
			}

			valoresEnvio = {
				tabla: valorJSON_campo_fomulario.tabla,
				llave: valorJSON_campo_fomulario.llave,
				campo: valorJSON_campo_fomulario.campo,
				valor: valorJSON_campo_fomulario.valor_viejo,
				estado_revision: valoresComentario[1].value, // [1] es el input type=radio
				comentario: valoresComentario[0].value, // [0] comentario
				expediente: $(".NumeroExpediente").val()
			}

			$.ajax({
				type: "POST",
				url: "/medicamentos/guardar_comentario",
				data: valoresEnvio
			}).done(function(respuesta){
				console.log('respuesta comentario: ', respuesta);
			});
		});

		// js para los comentarios de principio activo (lista dinamica)
		$(".comentario-pa").click(function(){
			var objeto_actual = $(this);
			//var valorJSON = objeto_actual.data("comentario-pa");
			
			var valorJSON_campo_fomulario = objeto_actual.parent().prev().prev().data("json");

			var valoresComentario = objeto_actual.parent().serializeArray();

			// si el input type=radio es null
			if (typeof valoresComentario[1] == "undefined") 
			{
				valoresComentario[1] = { value:'Pendiente'};
			}

			valoresEnvio = {
				tabla: valorJSON_campo_fomulario.tabla,
				llave: valorJSON_campo_fomulario.llave,
				campo: valorJSON_campo_fomulario.campo,
				valor: valorJSON_campo_fomulario.valor_viejo,
				estado_revision: valoresComentario[1].value, // [1] es el input type=radio
				comentario: valoresComentario[0].value, // [0] comentario
				expediente: $(".NumeroExpediente").val()
			}

			$.ajax({
				type: "POST",
				url: "/medicamentos/guardar_comentario",
				data: valoresEnvio
			}).done(function(respuesta){
				console.log('respuesta comentario: ', respuesta);
			});
		});

		$(".form_app_m").change(function(e){
			var objeto_actual = $(this);
			var valorJSON;
			var valoresEnvio;
			var es_selector_multimple = false;
			//console.log(objeto_actual.attr("name").toString(), ' : ', objeto_actual.val());
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
					//console.log('MarcaSignoDistintivoComercial');
					valorJSON = objeto_actual.data("json");
					valoresEnvio = {
						valor_nuevo: objeto_actual.val(),
						valores_JSON: valorJSON,
						expediente: $(".NumeroExpediente").val()
					}
					break;
				case 'IdentificadorFormaComercializacion':
					//console.log('IdentificadorFormaComercializacion');
					valorJSON = $(".IdentificadorFormaComercializacion").data("json");
					valoresEnvio = {
						valor_nuevo: objeto_actual.val(),
						valores_JSON: valorJSON,
						expediente: $(".NumeroExpediente").val()
					}
					break;
				case 'CodigoFormaFarmaceutica':
					//console.log('CodigoFormaFarmaceutica');
					valorJSON = $(".CodigoFormaFarmaceutica").data("json");
					valoresEnvio = {
						valor_nuevo: objeto_actual.val(),
						valores_JSON: valorJSON,
						expediente: $(".NumeroExpediente").val()
					}
					break;
				case 'codigo_via_administracion':
					//console.log('codigo_via_administracion');
					valorJSON = $('.codigo_via_administracion').data("json");
					//console.log(objeto_actual.val().join('&'));
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
				
				default:
					// pasan por ahora los (p.a.)
					/*
						NombrePrincipioActivo
						IdentificadorTipoConcentracionEstandarizada
						CantidadEstandarizadaPrincipioActivo
						CodigoUnidadMedidaEstandarizadaPrincipioActivo
						CantidadEstandarizadaMedicamentoContenidoPrincipioActivo
						CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo
					*/
					valorJSON = objeto_actual.data("json");
					valoresEnvio = {
						valor_nuevo: objeto_actual.val(),
						valores_JSON: valorJSON,
						expediente: $(".NumeroExpediente").val()
					}
					break;
			}
			$.ajax({
				type: "POST",
				url: "/medicamentos/guardar_expediente_asignado",
				data: valoresEnvio
			}).done(function(respuesta){
				console.log('respuesta: ', respuesta);
			});
			
			//console.log('nuevo valor', objeto_actual.val());
			// actualizo el nuevo valor viejo (despues del ajax)
			if (es_selector_multimple) 
			{
				valorJSON.valor_viejo = objeto_actual.val().join('&');
			}
			else
			{
				valorJSON.valor_viejo = objeto_actual.val();
			}

			//console.log('nuevo valorJSON:', valorJSON);
		});

    });
</script>

<style type="text/css">
	select {
    	width: 100%;
	}
	.img-thum{
		max-height: 150px;
	}
</style>