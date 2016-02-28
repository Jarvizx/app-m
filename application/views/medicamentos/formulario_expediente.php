<?php 
	
	// defino globales que necesito en las funciones de esta vista
	$GLOBALS['comentarios'] 	= $comentarios;
	$GLOBALS['idGrupoUsuario'] 	= $grupoUsuario['id'];
	
	// valores que necesito en el formulario
	$valores_tbl_rev_expedientes 		= $tbl_rev_expedientes->row();
	$valores_tbl_invima_medicamento 	= $tbl_invima_medicamento->row();
	/* 
		funcion mostrar_texto_comentarios(), por cada campo del formulario se consulta si tiene un comentario
		$nombre_campo 			string
		$es_array_multiple		bool
		$llave					int
	*/
	function mostrar_texto_comentarios($nombre_campo, $es_array_multiple = false, $llave)
	{
		$comentarios = $GLOBALS['comentarios'];
		if ( ! empty($comentarios[$nombre_campo])) 
		{
			if ($es_array_multiple) 
			{
				foreach ($comentarios[$nombre_campo] as $k_comentario => $v_comentario) 
				{
					if ($llave == $v_comentario->llave) 
					{
						return $v_comentario->texto;
					} 
				}
			}
			else
			{
				return current($comentarios[$nombre_campo])->texto;	
			}
		}
	}

	/*
		funcion formulario_comentarios(), en cada input, existe un mini-formulario
		$tipoComentario 		string
		$clase                 	string (opcional) 
	*/
	function formulario_comentarios($tipoComentario, $clase)
	{
		$formuario = sprintf('<form tipoComentario="%s" data-comentario=\'{"clase": "%s"}\'>', $tipoComentario, $clase);
		$formuario .= '<b>Comentario: </b><br>';
		$formuario .= '<textarea rows="1" cols="40" class="form-control" name="comentario"></textarea><br>';
		$formuario .= '<input type="radio" name="estado_revision" value="Ok"> Ok ';
		
		// donde 1 es admin, 2 es ministerio, 3 el coordinador, 4 digitador, 5 externo
		if (in_array($GLOBALS['idGrupoUsuario'], array(1,2,3,4))) 
		{		
			// los usuarios administrador, coordinador, ministerio y digitador, tienen esta opcion
			$formuario .= '<input type="radio" name="estado_revision" value="Rev. Lab"> Rev. Lab ';
			$formuario .= '<input type="radio" name="estado_revision" value="Rev. Super"> Rev. Super ';
			$formuario .= '<input type="radio" name="estado_revision" value="Pendiente"> Pendiente ';
		}
		else
		{
			// los usuarios externos y de laboratorio solo tiene esta opcion
			$formuario .= '<input type="radio" name="estado_revision" value="Comentario"> Comentario ';	
		}
		
		$formuario .= '</form>';

		return $formuario;
	}
?>
<div class="img-expediente">
	<img class="img-google-1 img-thum">
	<img class="img-google-2 img-thum">
	<img class="img-google-3 img-thum">
</div>
<br>
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
			 		<?= mostrar_texto_comentarios('MarcaSignoDistintivoComercial', false, null); ?>
			 	</div>
			</td>
			<td>
				<?= formulario_comentarios('comentario_en_td_solo', 'MarcaSignoDistintivoComercial');?>
			</td>
		</tr>
		<!---->
		<tr>
			<th>
				Forma Comercializacion
			</th>
			<?php 
				foreach ($parametros_tbl_referencia['IdentificadorFormaComercializacion'] as $k_tbl_referencia => $v_tbl_referencia)
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
			 		<?= mostrar_texto_comentarios('IdentificadorFormaComercializacion', false, null); ?>
				</div>
			</td>
			<td>
				<?= formulario_comentarios('comentario_en_td_solo', 'IdentificadorFormaComercializacion');?>
		</tr>
		<!---->
		<tr>
			<th>
				Forma Farmaceutica
			</th>
			<?php 

				foreach ($parametros_tbl_referencia['FFM'] as $k_tbl_referencia => $v_tbl_referencia)
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
			 		<?= mostrar_texto_comentarios('CodigoFormaFarmaceutica', false, null); ?>
				</div>
			</td>
			<td>
				<?= formulario_comentarios('comentario_en_td_solo', 'CodigoFormaFarmaceutica');?>
			</td>
		</tr>
		<!---->
		<tr>
			<th>
				Via Administracion
			</th>
			<td>
			 	<?php //echo $valores_tbl_rev_expedientes->CodigoViaAdministracion; ?> 
			 	<?php 
			 		$CodigoViaAdministracion = explode("&", $valores_tbl_rev_expedientes->CodigoViaAdministracion); 
					foreach ($CodigoViaAdministracion as $k_CodigoViaAdministracion => $v_CodigoViaAdministracion)
					{
						foreach ($parametros_tbl_referencia['VAD'] as $k_tbl_referencia_vad => $v_tbl_referencia_vad)
						{
							if (($v_CodigoViaAdministracion * 1) == $v_tbl_referencia_vad['codigo']) 
							{
								$parametros_tbl_referencia['VAD'][$k_tbl_referencia_vad]['selected'] = true;
							}
						}
					}
			 	 ?>
				<select id="select_via_administracion" name="codigo_via_administracion" class="form_app_m codigo_via_administracion" multiple="multiple" data-json='{"tabla":"tbl_rev_expedientes", "llave":"<?= $valores_tbl_rev_expedientes->id; ?>", "valor_viejo":"<?= $valores_tbl_rev_expedientes->CodigoViaAdministracion; ?>", "campo":"CodigoViaAdministracion"}'>
				 	<?php foreach ($parametros_tbl_referencia['VAD'] as $k_nuevo_array_codigo_via_administracion => $v_nuevo_array_codigo_via_administracion): ?>
    					<option value="<?= $v_nuevo_array_codigo_via_administracion['codigo']?>" <?php echo ( ! empty($v_nuevo_array_codigo_via_administracion['selected'])) ? "selected" : "" ; ?>><?= $v_nuevo_array_codigo_via_administracion['nombre_codigo']?></option>
				 	<?php endforeach ?>
				</select>
				<br>
				<b>Invima: </b><?php echo $valores_tbl_invima_medicamento->ViasAdministracion; ?>
				<hr>
				<div class="comentario">
			 		<?= mostrar_texto_comentarios('CodigoViaAdministracion', false, null); ?>
				</div>
			</td>
			<td>
				<?= formulario_comentarios('comentario_en_td_solo', 'codigo_via_administracion');?>
			</td>
		</tr>
	</tbody>
</table>
<!-- Invima -->
<div class="<?= $tbl_invima_pa_homologado_texto->row()->coincidencia; ?>">
	<?= $tbl_invima_pa_homologado_texto->row()->texto;?>
</div>
<table class="table">
	<thead>
		<tr>
			<th>Invima</th>
			<th>Principio <br> Activo</th>
			<th>Tipo <br> Concentracion </th>
			<th>Cantidad<br>Principio<br>Activo</th>
			<th>Unidad</th>
			<th>Cantidad<br>Medicamento</th>
			<th>U.</th>
		</tr>
	</thead>
	<tbody class="contenedor-tr-pa">	 		
		<?php $tbl_invima_pa_lista_alerta = $tbl_invima_pa; ?>
		<?php foreach ($tbl_rev_expediente_pa->result() as $k_tbl_rev_expediente_pa => $v_tbl_rev_expediente_pa): ?>
			<tr>
				<td>
					<!--
						si un p.a. de la tabla tbl_invima_pa, teniendo el # de expediente y NO esta utilizado. Debemos mostrar una Alerta
					-->
					<?php 
				 		$principio_activoExplode = explode("&", $v_tbl_rev_expediente_pa->principio_activo);

				 		$tbl_invima_pa_lista = array();
				 		$tbl_invima_pa_lista = $tbl_invima_pa;
						foreach ($principio_activoExplode as $k_principio_activoExplode => $v_principio_activoExplode)
						{
							foreach ($tbl_invima_pa_lista as $k_tbl_invima_pa_lista => $v_tbl_invima_pa_lista)
							{
								if ($v_principio_activoExplode == $v_tbl_invima_pa_lista['principio_activo']) 
								{
									$tbl_invima_pa_lista[$k_tbl_invima_pa_lista]['selected'] = true;
									$tbl_invima_pa_lista_alerta[$k_tbl_invima_pa_lista]['selected'] = true;
								}
							}
						}

				 	?>

					<select class="form_app_m principio_activo select_tbl_invima_pa_lista" name="principio_activo" multiple="multiple" data-json='{"tabla":"tbl_rev_expediente_pa", "llave":"<?= $v_tbl_rev_expediente_pa->id ?>", "valor_viejo":"<?=$v_tbl_rev_expediente_pa->principio_activo;?>", "campo":"principio_activo"}'>
					 	<?php foreach ($tbl_invima_pa_lista as $k_tbl_invima_pa_lista => $v_tbl_invima_pa_lista): ?>
	    					<option value="<?= $v_tbl_invima_pa_lista['principio_activo']?>" <?php echo ( ! empty($v_tbl_invima_pa_lista['selected'])) ? "selected" : "" ; ?>><?= $v_tbl_invima_pa_lista['principio_activo']?></option>
					 	<?php endforeach ?>
					</select>
					<p class="comentario">
						<?= mostrar_texto_comentarios('principio_activo', true, $v_tbl_rev_expediente_pa->id); ?>	
					</p>
					<?= formulario_comentarios('comentario_en_td_con_el_input', null);?>

				</td>
				<td>
					<?php 
						$coincide_nombre_codigo = false;
						foreach ($parametros_tbl_referencia['DCI'] as $k_tbl_referencia_dci => $v_tbl_referencia_dci)
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

							$select_NombrePrincipioActivoLimpio[] = sprintf('<option value="%s"> %s </option>', $v_tbl_referencia_dci['nombre_codigo'], $v_tbl_referencia_dci['nombre_codigo']);

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
						<option value="">Seleccione una opción</option>
						<?php foreach ($select_NombrePrincipioActivo as $k_select_NombrePrincipioActivo => $v_select_NombrePrincipioActivo): ?>
							 	<?= $v_select_NombrePrincipioActivo; ?>
						<?php endforeach ?> 
					</select>
					<p class="comentario">
						<?= mostrar_texto_comentarios('NombrePrincipioActivo', true, $v_tbl_rev_expediente_pa->id); ?>	
					</p>
					<?= formulario_comentarios('comentario_en_td_con_el_input', null);?>					
				</td>
				<td>
					<select class="form_app_m" name="IdentificadorTipoConcentracionEstandarizada" data-json='{"tabla":"tbl_rev_expediente_pa", "llave":"<?= $v_tbl_rev_expediente_pa->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pa->IdentificadorTipoConcentracionEstandarizada; ?>", "campo":"IdentificadorTipoConcentracionEstandarizada"}'>
						<option value="">Seleccione una opción</option>
						<?php 
							foreach ($parametros_tbl_referencia['TipoEstandarParaCantidadYUnidadMedida'] as $k_tbl_referencia => $v_tbl_referencia)
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

								$select_IdentificadorTipoConcentracionEstandarizadaLimpio[] = sprintf('<option value="%s"> %s </option>', $v_tbl_referencia['codigo'], $v_tbl_referencia['nombre_codigo']);;
							}
						?>
					</select>
					<p class="comentario">
						<?= mostrar_texto_comentarios('IdentificadorTipoConcentracionEstandarizada', true, $v_tbl_rev_expediente_pa->id); ?>	
					</p>
					<?= formulario_comentarios('comentario_en_td_con_el_input', null);?>
				</td>
				<td>
					<input type="text" class="form_app_m CantidadEstandarizadaPrincipioActivo" name="CantidadEstandarizadaPrincipioActivo" data-json='{"tabla":"tbl_rev_expediente_pa", "llave":"<?= $v_tbl_rev_expediente_pa->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pa->CantidadEstandarizadaPrincipioActivo; ?>", "campo":"CantidadEstandarizadaPrincipioActivo"}' value="<?= $v_tbl_rev_expediente_pa->CantidadEstandarizadaPrincipioActivo; ?>">
					<p class="comentario">
					<?= mostrar_texto_comentarios('CantidadEstandarizadaPrincipioActivo', true, $v_tbl_rev_expediente_pa->id); ?>	
					</p>
					<?= formulario_comentarios('comentario_en_td_con_el_input', null);?>
				</td>
				<td>
					<select class="form_app_m" name="CodigoUnidadMedidaEstandarizadaPrincipioActivo" data-json='{"tabla":"tbl_rev_expediente_pa", "llave":"<?= $v_tbl_rev_expediente_pa->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pa->CodigoUnidadMedidaEstandarizadaPrincipioActivo; ?>", "campo":"CodigoUnidadMedidaEstandarizadaPrincipioActivo"}'>
						<option value="">Seleccione una opción</option>
						<?php 
							foreach ($parametros_tbl_referencia['UMM'] as $k_tbl_referencia => $v_tbl_referencia)
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

								$select_CodigoUnidadMedidaEstandarizadaPrincipioActivoLimpio[] = sprintf('<option value="%s"> %s </option>', $v_tbl_referencia['codigo'], $v_tbl_referencia['nombre_codigo']);
							}
						?>
					</select>
					<p class="comentario">
						<?= mostrar_texto_comentarios('CodigoUnidadMedidaEstandarizadaPrincipioActivo', true, $v_tbl_rev_expediente_pa->id); ?>	
					</p>
					<?= formulario_comentarios('comentario_en_td_con_el_input', null);?>
				</td>
				<td>
					<input type="text" class="form_app_m CantidadEstandarizadaMedicamentoContenidoPrincipioActivo" name="CantidadEstandarizadaMedicamentoContenidoPrincipioActivo" data-json='{"tabla":"tbl_rev_expediente_pa", "llave":"<?= $v_tbl_rev_expediente_pa->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pa->CantidadEstandarizadaMedicamentoContenidoPrincipioActivo; ?>", "campo":"CantidadEstandarizadaMedicamentoContenidoPrincipioActivo"}' value="<?= $v_tbl_rev_expediente_pa->CantidadEstandarizadaMedicamentoContenidoPrincipioActivo; ?>">
					<p class="comentario">
					<?= mostrar_texto_comentarios('CantidadEstandarizadaMedicamentoContenidoPrincipioActivo', true, $v_tbl_rev_expediente_pa->id); ?>	
					</p>
					<?= formulario_comentarios('comentario_en_td_con_el_input', null);?>
					
				</td>
				<td>
					<select class="form_app_m" name="CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo" data-json='{"tabla":"tbl_rev_expediente_pa", "llave":"<?= $v_tbl_rev_expediente_pa->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pa->CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo; ?>", "campo":"CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo"}'>
						<option value="">Seleccione una opción</option>
						<?php 
							foreach ($parametros_tbl_referencia['UMM'] as $k_tbl_referencia => $v_tbl_referencia)
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
								$select_CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivoLimpio[] = sprintf('<option value="%s"> %s </option>', $v_tbl_referencia['codigo'], $v_tbl_referencia['nombre_codigo']);
							}
						?>
					</select>
					<p class="comentario">
						<?= mostrar_texto_comentarios('CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo', true, $v_tbl_rev_expediente_pa->id); ?>	
					</p>
					<?= formulario_comentarios('comentario_en_td_con_el_input', null);?>
				</td>
			</tr>
		<?php endforeach ?>
	</tbody>
	<tfoot class="hidden">
		<tr>
			<td>
				<?php 
					$pa_sin_seleccionar = NULL;
					foreach ($tbl_invima_pa_lista_alerta as $k_tbl_invima_pa_lista_alerta => $v_tbl_invima_pa_lista_alerta) 
					{
						if (isset($v_tbl_invima_pa_lista_alerta['selected']) == false) 
						{
							$pa_sin_seleccionar .= $v_tbl_invima_pa_lista_alerta['principio_activo'].'&';
						}
					}
				?>
				<input type="text" class="tbl_invima_pa_lista_alerta" value="<?= $pa_sin_seleccionar;?>"></input>
			</td>
		</tr>
	</tfoot>
</table>
<table class="contenedor-formato-tr-dinamico-pa hidden">
	<tbody>	
		<tr class="tr-clonado-pa">
			<td>
				<select class="form_app_m principio_activo select_tbl_invima_pa_lista" name="principio_activo" multiple="multiple" data-json='{"tabla":"tbl_rev_expediente_pa", "llave":"", "valor_viejo":"<?=$v_tbl_rev_expediente_pa->principio_activo;?>", "campo":"principio_activo"}'>
				 	<?php foreach ($tbl_invima_pa_lista as $k_tbl_invima_pa_lista => $v_tbl_invima_pa_lista): ?>
						<option value="<?= $v_tbl_invima_pa_lista['principio_activo']?>"><?= $v_tbl_invima_pa_lista['principio_activo']?></option>
				 	<?php endforeach ?>
				</select>
				<p class="comentario"></p>
				<?= formulario_comentarios('comentario_en_td_con_el_input', null);?>
			</td>
			<td>
				<select class="form_app_m NombrePrincipioActivo" name="NombrePrincipioActivo" data-json='{"tabla":"tbl_rev_expediente_pa", "llave":"", "valor_viejo":"", "campo":"NombrePrincipioActivo"}' >
					<option value="">Seleccione una opción</option>	
					<?php foreach ($select_NombrePrincipioActivoLimpio as $k_select_NombrePrincipioActivoLimpio => $v_select_NombrePrincipioActivoLimpio): ?>
						<?= $v_select_NombrePrincipioActivoLimpio; ?>
					<?php endforeach ?> 
				</select>
				<p class="comentario"></p>
				<?= formulario_comentarios('comentario_en_td_con_el_input', null);?>
			</td>
			<td>
				<select class="form_app_m" name="IdentificadorTipoConcentracionEstandarizada" data-json='{"tabla":"tbl_rev_expediente_pa", "llave":"", "valor_viejo":"", "campo":"IdentificadorTipoConcentracionEstandarizada"}'>
					<option value="">Seleccione una opción</option>
					<?php foreach ($select_IdentificadorTipoConcentracionEstandarizadaLimpio as $k_select_IdentificadorTipoConcentracionEstandarizadaLimpio => $v_select_IdentificadorTipoConcentracionEstandarizadaLimpio): ?>
						<?= $v_select_IdentificadorTipoConcentracionEstandarizadaLimpio; ?>
					<?php endforeach ?> 
				</select>
				<p class="comentario"></p>
				<?= formulario_comentarios('comentario_en_td_con_el_input', null);?>
			</td>
			<td>
				<input type="text" class="form_app_m CantidadEstandarizadaPrincipioActivo" name="CantidadEstandarizadaPrincipioActivo" data-json='{"tabla":"tbl_rev_expediente_pa", "llave":"", "valor_viejo":"", "campo":"CantidadEstandarizadaPrincipioActivo"}' value="">
				<p class="comentario"></p>
				<?= formulario_comentarios('comentario_en_td_con_el_input', null);?>
			</td>
			<td>
				<select class="form_app_m" name="CodigoUnidadMedidaEstandarizadaPrincipioActivo" data-json='{"tabla":"tbl_rev_expediente_pa", "llave":"", "valor_viejo":"", "campo":"CodigoUnidadMedidaEstandarizadaPrincipioActivo"}'>
					<option value="">Seleccione una opción</option>
					<?php foreach ($select_CodigoUnidadMedidaEstandarizadaPrincipioActivoLimpio as $k_select_CodigoUnidadMedidaEstandarizadaPrincipioActivoLimpio => $v_select_CodigoUnidadMedidaEstandarizadaPrincipioActivoLimpio): ?>
						<?= $v_select_CodigoUnidadMedidaEstandarizadaPrincipioActivoLimpio; ?>
					<?php endforeach ?> 
				</select>
				<p class="comentario"></p>
				<?= formulario_comentarios('comentario_en_td_con_el_input', null);?>
			</td>
			<td>
				<input type="text" class="form_app_m CantidadEstandarizadaMedicamentoContenidoPrincipioActivo" name="CantidadEstandarizadaMedicamentoContenidoPrincipioActivo" data-json='{"tabla":"tbl_rev_expediente_pa", "llave":"", "valor_viejo":"", "campo":"CantidadEstandarizadaMedicamentoContenidoPrincipioActivo"}' value="">
				<p class="comentario"></p>
				<?= formulario_comentarios('comentario_en_td_con_el_input', null);?>
			</td>
			<td>
				<select class="form_app_m" name="CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo" data-json='{"tabla":"tbl_rev_expediente_pa", "llave":"", "valor_viejo":"", "campo":"CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo"}'>
					<option value="">Seleccione una opción</option>
					<?php foreach ($select_CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivoLimpio as $k_select_CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivoLimpio => $v_select_CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivoLimpio): ?>
						<?= $v_select_CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivoLimpio; ?>
					<?php endforeach ?> 
				</select>
				<p class="comentario"></p>
				<?= formulario_comentarios('comentario_en_td_con_el_input', null);?>
			</td>
			<td>
				<button class="btn btn-danger quitar-tr-pa">Quitar Fila</button>
			</td>
		</tr>
	</tbody>
</table>
<!--
		! el id es cuando selecciona el principio activo para guardar (llave)

		primero copio el td que esta oculto con los valores del select por defecto, y solo le agrego una clase para diferenciarlo
		despues de que seleccionen un principio activo (como primer paso) se envia a la tabla para que guarde, (aun no se si por campo o toda la tr)


		! recordar llenar los campos del JSON en estos Campos
-->
<!-- Esta TR esta oculta y se clona para otro registro de invima -->

<!-- /Esta TR esta oculta y se clona para otro registro de invima -->
<?php if ( $this->ion_auth->logged_in()): ?>
	<?php if (in_array($GLOBALS['idGrupoUsuario'], array(1,2,3,4))): ?>
		<div class="opciones_formulario_pa">
			<div class="formulario_pa_alerta"></div>
			<button class="agregar-tr-pa btn btn-success">Agregar Otra Fila</button> 
			<span>Al agregar una nueva fila, recuerde primero seleccionar un principio activo para que los demás campos se guarden. </span>
		</div>
	<?php endif ?>
<?php endif ?>

<!-- /Invima -->
<!--presentacion comercial -->
<h3>Presentación Comercial</h3>
<table class="table">
	<thead>
		<tr>
			<th>Invima (new)</th>
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
				foreach ($parametros_tbl_referencia['UPR'] as $k_tbl_referencia => $v_tbl_referencia)
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
				foreach ($parametros_tbl_referencia['UMM'] as $k_tbl_referencia => $v_tbl_referencia)
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
				foreach ($parametros_tbl_referencia['UPR'] as $k_tbl_referencia => $v_tbl_referencia)
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
				foreach ($parametros_tbl_referencia['IndicadorMuestraMedica_old'] as $k_tbl_referencia => $v_tbl_referencia)
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
				if ( count($parametros_tbl_referencia['IdentificadorMarca']) > 0 ) 
				{
					foreach ($parametros_tbl_referencia['IdentificadorMarca'] as $k_tbl_referencia => $v_tbl_referencia) 
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
			<td>
				Invima (new )
			</td>
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
					<?= mostrar_texto_comentarios('CodigoUnidadContenido', true, $v_tbl_rev_expediente_pc->id); ?>
				</p>
					<?= formulario_comentarios('comentario_en_td_con_el_input', null);?>
				
			</td>
			<td class="<?= $color_td_CapacidadUnidadContenido; ?>">
				<input type="text" class="form_app_m CapacidadUnidadContenido" name="CapacidadUnidadContenido" data-json='{"tabla":"tbl_rev_expediente_pc", "llave":"<?= $v_tbl_rev_expediente_pc->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pc->CapacidadUnidadContenido; ?>", "campo":"CapacidadUnidadContenido"}' value="<?= $v_tbl_rev_expediente_pc->CapacidadUnidadContenido; ?>">
				<p class="comentario">
					<?= mostrar_texto_comentarios('CapacidadUnidadContenido', true, $v_tbl_rev_expediente_pc->id); ?>
				</p>
					<?= formulario_comentarios('comentario_en_td_con_el_input', null);?>
				
			</td>
			<td class="<?= $color_td_CodigoUnidadCapacidad;?>">
				<select class="form_app_m" name="CodigoUnidadCapacidad" data-json='{"tabla":"tbl_rev_expediente_pc", "llave":"<?= $v_tbl_rev_expediente_pc->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pc->CodigoUnidadCapacidad; ?>", "campo":"CodigoUnidadCapacidad"}'>
					<option value="">Seleccione una opción</option>
					<?php foreach ($selec_CodigoUnidadCapacidad as $k_selec_CodigoUnidadCapacidad => $v_selec_CodigoUnidadCapacidad): ?>
						<?= $v_selec_CodigoUnidadCapacidad;?>
					<?php endforeach ?>
				</select>
				
				<p class="comentario">
					<?= mostrar_texto_comentarios('CodigoUnidadCapacidad', true, $v_tbl_rev_expediente_pc->id); ?>
				</p>
					<?= formulario_comentarios('comentario_en_td_con_el_input', null);?>
				
			</td>
			<td class="<?= $color_td_CodigoEmpaque;?>">
				<select class="form_app_m" name="CodigoEmpaque" data-json='{"tabla":"tbl_rev_expediente_pc", "llave":"<?= $v_tbl_rev_expediente_pc->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pc->CodigoEmpaque; ?>", "campo":"CodigoEmpaque"}'>
					<option value="">Seleccione una opción</option>
					<?php foreach ($select_CodigoEmpaque as $k_select_CodigoEmpaque => $v_select_CodigoEmpaque): ?>
						<?= $v_select_CodigoEmpaque;?>
					<?php endforeach ?>
				</select>
				
				<p class="comentario">
					<?= mostrar_texto_comentarios('CodigoEmpaque', true, $v_tbl_rev_expediente_pc->id); ?>
				</p>
					<?= formulario_comentarios('comentario_en_td_con_el_input', null);?>
				
			</td>
			<td class="<?= $color_td_CantidadUnidadesContenidoEmpaque;?>">
				<input type="text" class="form_app_m CantidadUnidadesContenidoEmpaque" name="CantidadUnidadesContenidoEmpaque" data-json='{"tabla":"tbl_rev_expediente_pc", "llave":"<?= $v_tbl_rev_expediente_pc->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pc->CantidadUnidadesContenidoEmpaque; ?>", "campo":"CantidadUnidadesContenidoEmpaque"}' value="<?= $v_tbl_rev_expediente_pc->CantidadUnidadesContenidoEmpaque; ?>">
				<p class="comentario">
					<?= mostrar_texto_comentarios('CantidadUnidadesContenidoEmpaque', true, $v_tbl_rev_expediente_pc->id); ?>
				</p>
					<?= formulario_comentarios('comentario_en_td_con_el_input', null);?>
				
			</td>
			<td class="<?= $color_td_IdentificadorCondicionEstarRegistradoComoMuestraMedica;?>">
				<select class="form_app_m" name="IdentificadorCondicionEstarRegistradoComoMuestraMedica" data-json='{"tabla":"tbl_rev_expediente_pc", "llave":"<?= $v_tbl_rev_expediente_pc->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pc->IdentificadorCondicionEstarRegistradoComoMuestraMedica; ?>", "campo":"IdentificadorCondicionEstarRegistradoComoMuestraMedica"}'>
					<option value="">Seleccione una opción</option>
					<?php foreach ($select_IdentificadorCondicionEstarRegistradoComoMuestraMedica as $k_select_IdentificadorCondicionEstarRegistradoComoMuestraMedica => $v_select_IdentificadorCondicionEstarRegistradoComoMuestraMedica): ?>
						<?= $v_select_IdentificadorCondicionEstarRegistradoComoMuestraMedica;?>
					<?php endforeach ?>
				</select>
				
				<p class="comentario">
					<?= mostrar_texto_comentarios('IdentificadorCondicionEstarRegistradoComoMuestraMedica', true, $v_tbl_rev_expediente_pc->id); ?>
				</p>
					<?= formulario_comentarios('comentario_en_td_con_el_input', null);?>
				
			</td>
			<!-- Nuevo 19 enero, dispositivo -->
			<td class="<?= $color_td_DispositivosAsociados; ?>">
				<input type="text" class="form_app_m DispositivosAsociados" name="DispositivosAsociados" data-json='{"tabla":"tbl_rev_expediente_pc", "llave":"<?= $v_tbl_rev_expediente_pc->id ?>", "valor_viejo":"<?= $v_tbl_rev_expediente_pc->DispositivosAsociados; ?>", "campo":"DispositivosAsociados"}' value="<?= $v_tbl_rev_expediente_pc->DispositivosAsociados; ?>">
				<p class="comentario">
					<?= mostrar_texto_comentarios('DispositivosAsociados', true, $v_tbl_rev_expediente_pc->id); ?>
				</p>
					<?= formulario_comentarios('comentario_en_td_con_el_input', null);?>
				
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
					<?= mostrar_texto_comentarios('IdentificadorMarca', true, $v_tbl_rev_expediente_pc->id); ?>
				</p>
					<?= formulario_comentarios('comentario_en_td_con_el_input', null);?>
				
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
					<?= mostrar_texto_comentarios('CantidadPrincipioActivoPresentacionComercial', true, $v_tbl_rev_expediente_pc_pa->id); ?>
				</p>
					<?= formulario_comentarios('comentario_en_td_con_el_input', null);?>
				
			</td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>
<br> 	 	 

<?php if ( $this->ion_auth->logged_in()): ?>
	<?php if (in_array($GLOBALS['idGrupoUsuario'], array(1,2,3,4))): ?>
		<!-- // donde 1 es admin, 2 es ministerio, 3 el coordinador, 4 digitador, 5 externo  -->
		<button class="btn btn-danger expediente_terminado" type="button">Siguiente Asignado</button>
	<?php endif ?>
<?php endif ?>

<hr>
<!-- Initialize the plugin: -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#select_via_administracion').multiselect();
        $('.select_tbl_invima_pa_lista').multiselect();
        if ($('.tbl_invima_pa_lista_alerta').val().length > 0) 
        {
        	$(".formulario_pa_alerta").html('<p class="bg-danger lead">Existe un principio activo que no esta seleccionado del Invima</p>');
        }
        /*
		realizar la busqueda, lleva buen tiempo, son 8760 valores que tiene ese select 
        $('.NombrePrincipioActivo').multiselect({
            enableFiltering: true,
            enableFullValueFiltering: true
            // enableCaseInsensitiveFiltering: true
        });*/

        
		var urlImagenGoogle = "https://www.googleapis.com/customsearch/v1";
      	var params 		= {};
        params.q 		= $(".nombre_del_producto_invima").val(); // search text
        params.num 		= 3; // integer value range between 1 to 10 including
        params.start 	= 1; // integer value range between 1 to 101, it is like the offset
        params.imgSize 	= "medium"; // for image size
        params.searchType = "image"; // compulsory 
		// params.fileType = "jpg"; // you can leave these if extension does not matters you
        params.key 		= "AIzaSyAxuhR-N_z_nqLKuiOUDOJRcF2EfThokaw"; // API_KEY got from https://console.developers.google.com/
        params.cx 		= "001076089730765291564:1feh9bak7-8"; // cx value is the custom search engine value got from https://cse.google.com/cse(if not created then create it).
          
		// var urlImagenGoogle = "https://ajax.googleapis.com/ajax/services/search/images?v=1.0&q="+$(".nombre_del_producto_invima").val();
       	/*$.ajax({
			type: 'GET',
			contentType: "application/json",
			dataType: 'jsonp',
			url: urlImagenGoogle,
			data: params,
			success: function(respuesta){
				console.log('peticion');
				$(".img-google-1").attr("src", respuesta.items[0].link);
				$(".img-google-2").attr("src", respuesta.items[1].link);
				$(".img-google-3").attr("src", respuesta.items[2].link);
			}
        });*/

        // ajax comentarios

        $("body").on("click", "input:radio", function(){
        	var objeto_actual = $(this); 
    		if(objeto_actual.attr('name') == "estado_revision")
    		{
    			console.log(objeto_actual.parent());
    			console.log(objeto_actual.parent().attr("tipoComentario"));
    			var tipoForm = objeto_actual.parent().attr("tipoComentario");
    			// cada if tiene un ajax, por que segun la ubicacion del comentario se agregara tambien la respuesta asincronica
    			if (tipoForm == 'comentario_en_td_con_el_input') 
    			{
					var valorJSON_campo_fomulario;
					// existe un select multiple, que crea una posicion entre el select y los comentarios, por eso esta validacion
    				if (typeof objeto_actual.parent().prev().prev().data("json") == "undefined") 
    				{
    					valorJSON_campo_fomulario = objeto_actual.parent().prev().prev().prev().data("json");
    				} 
    				else 
    				{
    					valorJSON_campo_fomulario = objeto_actual.parent().prev().prev().data("json");
    				}

					var valoresComentario = objeto_actual.parent().serializeArray();

					console.log(objeto_actual, ' - ', valorJSON_campo_fomulario , ' - ', objeto_actual.parent().prev().prev());
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
				$(".img-preload").hide();
				console.log('respuesta: ', respuesta);
				if (respuesta > 0)
				{
					var url_parametros = '/medicamentos/expediente/'+respuesta;
				} 
				else
				{
					var url_parametros = '/medicamentos/asignados';
				}
				window.location.replace(url_host + url_parametros);
			});
		});

		function actualizar_llave_pa(principio_activo)
		{
			$(".tr-clonado-pa").each(function(index){
				var expediente = $(".NumeroExpediente").val();
				$(this).find('[data-json]').each(function(k){
					$(this).data("json").llave = expediente+'&'+principio_activo;
				});
				// = expediente+'(select_dinamico)_'+index;
				// console.log(' -> ', $(this).find('[data-json]'));
			});
		}

		// Agregar tr dinamico
		$(".agregar-tr-pa").on('click', function(){
			$(".contenedor-formato-tr-dinamico-pa").children().children().clone().appendTo(".contenedor-tr-pa");
			// actualizar_llave_pa();
		});
		// Quitar tr dinamico
		$(".contenedor-tr-pa").on('click', '.quitar-tr-pa', function(){
			$(this).parent().parent().remove();
		});

		// ajax valores de cada input
		$("body").on("change", ".form_app_m", function(e){
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

			if (objeto_actual.parent().parent().hasClass("tr-clonado-pa")) 
			{
				if (objeto_actual.hasClass("NombrePrincipioActivo")) 
				{
					actualizar_llave_pa(objeto_actual.val());
					console.log("cumplio!");
				}
				if(objeto_actual.data("json").llave.length == 0)
				{
					alert('Recuerde primero seleccionar un principio activo para que los demás campos se guarden.');
					return false;
				}

			} 
			console.log(objeto_actual.data("json"));

			//return false;

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
				// ESTO MISMO
				case 'codigo_via_administracion':
					valorJSON = $('.codigo_via_administracion').data("json");
					valoresEnvio = {
						valor_nuevo: objeto_actual.val().join('&'),
						valores_JSON: valorJSON,
						expediente: $(".NumeroExpediente").val()
					}
					es_selector_multimple = true;
					break;
				case 'principio_activo':
					valorJSON = objeto_actual.data("json");
					valoresEnvio = {
						valor_nuevo: objeto_actual.val().join('&'),
						valores_JSON: valorJSON,
						expediente: $(".NumeroExpediente").val()
					}
					console.log('llego en el case, como principio_activo ', valoresEnvio);
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
	td > b{
	    margin-right: 0.2em;
	}
</style>