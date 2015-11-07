<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Medicamentos_pc extends CI_Controller {

	public $user;

	function __construct() 
	{  
	    parent::__construct();
		$this->load->library(array('ion_auth', 'layout', 'session', 'pagination'));
	    $this->load->helper(array('form', 'url'));
		$this->load->model('medicamentos_model');
	
	    $this->user = $this->ion_auth->user()->row();
	}

	private function is_login()
	{
		if ( ! $this->ion_auth->logged_in()) 
	    {
			redirect('auth/login', 'refresh');
	    }
	}

	public function expediente($expediente = null)
	{
		if ( ! empty($expediente)) 
		{
			// muy importante validar el numero de resultados y saber si existe ese expediente
			// < >
			$parametro_expediente = array(
				'NumeroExpediente' => $expediente
			);
			$datos['tbl_rev_expedientes'] = $this->medicamentos_model->consultar_tbl_rev_expedientes($parametro_expediente);

			if($datos['tbl_rev_expedientes']->num_rows() > 0)
			{
				$parametro_referencia = array(
					'propiedad' => 'IdentificadorFormaComercializacion'
				);
				$datos['tbl_referencia_identificador']	= $this->medicamentos_model->consultar_tbl_referencia($parametro_referencia);

				$parametro_referencia = array(
					'propiedad' => 'FFM'
				);
				$datos['tbl_referencia_ffm']	= $this->medicamentos_model->consultar_tbl_referencia($parametro_referencia);
				
				$parametro_referencia = array(
					'propiedad' => 'VAD'
				);
				$datos['tbl_referencia_vad']	= $this->medicamentos_model->consultar_tbl_referencia($parametro_referencia);
				
				$parametro_expediente_para_invima = array(
					'expediente' => $expediente
				);
				$datos['tbl_invima_medicamento']	= $this->medicamentos_model->consultar_tbl_invima_medicamento($parametro_expediente_para_invima);

				// 2do tr
				$parametro_referencia = array(
					'NumeroExpediente' => $expediente
				);
				$datos['tbl_rev_expediente_pa']	= $this->medicamentos_model->consultar_tbl_rev_expediente_pa($parametro_referencia);

				// DCI (select principio activo)
				$parametro_referencia = array(
					'propiedad' => 'DCI'
				);
				$datos['tbl_referencia_dci']	= $this->medicamentos_model->consultar_tbl_referencia($parametro_referencia);

				// UMM
				$parametro_referencia = array(
					'propiedad' => 'UMM' // Unidad & U.
				);
				$datos['tbl_referencia_umm']	= $this->medicamentos_model->consultar_tbl_referencia($parametro_referencia);

				// TipoEstandarParaCantidadYUnidadMedida
				$parametro_referencia = array(
					'propiedad' => 'TipoEstandarParaCantidadYUnidadMedida'
				);
				$datos['tbl_referencia_tepcyum']	= $this->medicamentos_model->consultar_tbl_referencia($parametro_referencia);


				# historial de los comentarios invima
				$datos['comentarios_MarcaSignoDistintivoComercial'] = $this->medicamentos_model->consultar_historial_comentarios(array('expediente' => $expediente, 'campo' => 'MarcaSignoDistintivoComercial'))->row();
				$datos['comentarios_IdentificadorFormaComercializacion'] = $this->medicamentos_model->consultar_historial_comentarios(array('expediente' => $expediente, 'campo' => 'IdentificadorFormaComercializacion'))->row();
				$datos['comentarios_CodigoFormaFarmaceutica'] = $this->medicamentos_model->consultar_historial_comentarios(array('expediente' => $expediente, 'campo' => 'CodigoFormaFarmaceutica'))->row();
				$datos['comentarios_CodigoViaAdministracion'] = $this->medicamentos_model->consultar_historial_comentarios(array('expediente' => $expediente, 'campo' => 'CodigoViaAdministracion'))->row();
				
				# historial de los comentarios (p.a.)
				$datos['comentarios_NombrePrincipioActivo'] = $this->medicamentos_model->consultar_historial_comentarios(array('expediente'=>$expediente, 'campo' => 'NombrePrincipioActivo'))->result_array();
				$datos['comentarios_IdentificadorTipoConcentracionEstandarizada'] = $this->medicamentos_model->consultar_historial_comentarios(array('expediente'=>$expediente, 'campo' => 'IdentificadorTipoConcentracionEstandarizada'))->result_array();
				$datos['comentarios_CantidadEstandarizadaPrincipioActivo'] = $this->medicamentos_model->consultar_historial_comentarios(array('expediente'=>$expediente, 'campo' => 'CantidadEstandarizadaPrincipioActivo'))->result_array();
				$datos['comentarios_CodigoUnidadMedidaEstandarizadaPrincipioActivo'] = $this->medicamentos_model->consultar_historial_comentarios(array('expediente'=>$expediente, 'campo' => 'CodigoUnidadMedidaEstandarizadaPrincipioActivo'))->result_array();
				$datos['comentarios_CantidadEstandarizadaMedicamentoContenidoPrincipioActivo'] = $this->medicamentos_model->consultar_historial_comentarios(array('expediente'=>$expediente, 'campo' => 'CantidadEstandarizadaMedicamentoContenidoPrincipioActivo'))->result_array();
				$datos['comentarios_CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo'] = $this->medicamentos_model->consultar_historial_comentarios(array('expediente'=>$expediente, 'campo' => 'CodigoUnidadMedidaEstandarizadaMedicamentoPrincipioActivo'))->result_array();

				$datos['tbl_invima_pa_homologado_texto'] = $this->medicamentos_model->consultar_tbl_invima_pa_homologado_texto(array('expediente' => $expediente));

				# presentación comercial
				$datos['tbl_rev_expediente_pc'] = $this->medicamentos_model->consultar_tbl_rev_expediente_pc($parametro_expediente);
				
				# comentario presentación comercial
				$datos['comentarios_CodigoUnidadContenido'] = $this->medicamentos_model->consultar_historial_comentarios(array('expediente'=>$expediente, 'campo' => 'CodigoUnidadContenido'))->result_array();
				$datos['comentarios_CapacidadUnidadContenido'] = $this->medicamentos_model->consultar_historial_comentarios(array('expediente'=>$expediente, 'campo' => 'CapacidadUnidadContenido'))->result_array();
				$datos['comentarios_CodigoEmpaque'] = $this->medicamentos_model->consultar_historial_comentarios(array('expediente'=>$expediente, 'campo' => 'CodigoEmpaque'))->result_array();
				$datos['comentarios_CantidadUnidadesContenidoEmpaque'] = $this->medicamentos_model->consultar_historial_comentarios(array('expediente'=>$expediente, 'campo' => 'CantidadUnidadesContenidoEmpaque'))->result_array();
				$datos['comentarios_IdentificadorCondicionEstarRegistradoComoMuestraMedica'] = $this->medicamentos_model->consultar_historial_comentarios(array('expediente'=>$expediente, 'campo' => 'IdentificadorCondicionEstarRegistradoComoMuestraMedica'))->result_array();

				# presentación comercial principio activo
				$datos['tbl_rev_expediente_pc_pa'] = $this->medicamentos_model->consultar_tbl_rev_expediente_pc_pa($parametro_expediente);
				# comentario presentación comercial principio activo
				$datos['comentarios_CantidadPrincipioActivoPresentacionComercial'] = $this->medicamentos_model->consultar_historial_comentarios(array('expediente'=>$expediente, 'campo' => 'CantidadPrincipioActivoPresentacionComercial'))->result_array();


				$this->layout->view('medicamentos_pc/asignados_form',$datos);
			}
			else
			{
				// el expediente no existe
				$this->layout->view('medicamentos_pc/no_existe_expediente',$parametro_expediente);
			}
		}
		else
		{
			$this->layout->view('medicamentos_pc/no_existe_expediente',array('NumeroExpediente'=>0));
		}
	}

	public function guardar_expediente_asignado()
	{
		if ($_POST) 
		{
			if ( $this->ion_auth->logged_in())
			{
				$usuario = $this->user->id;
				$nivel 	 = reset($this->ion_auth->get_users_groups($this->user->id)->result())->id;
				$nombre_nivel = reset($this->ion_auth->get_users_groups($this->user->id)->result())->name;
			}
			else
			{
				$usuario = 5; // id usuario externo 
				$nivel 	 = 5; // id grupo usuario externo
				$nombre_nivel = 'externo'; // id grupo usuario externo
			}

			$decode_valores_json = $this->input->post('valores_JSON');
			$datos_por_guardar = array(
				'tabla' 		=> $decode_valores_json['tabla'],
				'llave' 		=> $decode_valores_json['llave'],
				'campo' 		=> $decode_valores_json['campo'],
				'valor_nuevo' 	=> $this->input->post('valor_nuevo'),
				'valor_viejo' 	=> $decode_valores_json['valor_viejo'],
				'usuario'	 	=> $usuario, // id usuario
				'nivel' 		=> $nivel, // id grupo
				'nombre_nivel' 	=> $nombre_nivel, // nombre grupo
				'expediente'	=> $this->input->post('expediente')
			);
			/*echo "<pre>";
			print_r($datos_por_guardar);
			echo "</pre>";
			exit();*/
			// creo un array de la(s) tabla(s)
			$tablas_por_insertar = explode(',', str_replace(' ', '', $datos_por_guardar['tabla']));
			$tablas_por_insertar[] = 'tbl_control_cambios';

			$contador_permisos = 0;
			foreach ($tablas_por_insertar as $k_tablas_por_insertar => $v_tablas_por_insertar) 
			{
				$datos_tbl_permisos_tablas[$k_tablas_por_insertar] = $this->medicamentos_model->consultar_tbl_permisos_tablas(array('tabla' => $v_tablas_por_insertar))->row();
				if ( ! empty($datos_tbl_permisos_tablas[$k_tablas_por_insertar])) 
				{
					// solo actualizar
					$update_datos_tbl_permisos_tablas[$contador_permisos] = explode(',', str_replace(' ', '', $datos_tbl_permisos_tablas[$k_tablas_por_insertar]->actualizar));
					if (in_array($nombre_nivel, $update_datos_tbl_permisos_tablas[$contador_permisos])) 
					{
						echo " actualizo del grupo " . $nombre_nivel . " la tabla:  " . $v_tablas_por_insertar;

						// caso especial para la tabla control de cambios
						if ($v_tablas_por_insertar == 'tbl_control_cambios')
						{
							$id_ultimo_registro = $this->medicamentos_model->guardar_expediente_asignado_tbl_control_cambios($datos_por_guardar);
						}
						else
						{
							// el array $datos_por_guardar, esta pendiente por guardar, pero en verdad actualiza en este caso. Reutilizo el array para es para el control de cambios
							$id_registro = $datos_por_guardar['llave'];
							$nombre_tabla = $v_tablas_por_insertar;
							$datos_tabla = array(
								$datos_por_guardar['campo'] => $datos_por_guardar['valor_nuevo']
							);
							// ($id = null, $nombre_tabla = null, $datos_tabla = array())
							$this->medicamentos_model->actualizar_expediente_tabla_fuente($id_registro, $nombre_tabla, $datos_tabla);
						}

					}
					$contador_permisos++;
				}
			}
		}
	}

	public function guardar_comentario()
	{
		if ($_POST) 
		{
			/*
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `tabla` varchar(140) DEFAULT NULL,
			  `llave` int(11) DEFAULT NULL,
			  `campo` varchar(140) DEFAULT NULL,
			  `valor` longtext,
			  `estado_revision` varchar(20) DEFAULT NULL,
			  `comentario` longtext,
			  `usuario` int(11) DEFAULT NULL,
			  `nivel` int(11) DEFAULT NULL,
			  `nombre_nivel` varchar(140) DEFAULT NULL,
			  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
			  `expediente` varchar(16) DEFAULT NULL,
			*/

		  	if ( $this->ion_auth->logged_in())
			{
				$usuario = $this->user->id;
				$nivel 	 = reset($this->ion_auth->get_users_groups($this->user->id)->result())->id;
				$nombre_nivel = reset($this->ion_auth->get_users_groups($this->user->id)->result())->name;
			}
			else
			{
				$usuario = 5; // id usuario externo 
				$nivel 	 = 5; // id grupo usuario externo
				$nombre_nivel = 'externo'; // id grupo usuario externo
			}

			$comentario_por_guardar = array(
				'tabla' 		=> $this->input->post('tabla'),
				'llave' 		=> $this->input->post('llave'),
				'campo' 		=> $this->input->post('campo'),
				'valor' 		=> $this->input->post('valor'),
				'estado_revision' => $this->input->post('estado_revision'),
				'comentario' 	=> $this->input->post('comentario'),
				'usuario'	 	=> $usuario, // id usuario
				'nivel' 		=> $nivel, // id grupo
				'nombre_nivel' 	=> $nombre_nivel, // nombre grupo
				'expediente'	=> $this->input->post('expediente')
			);

			$id_ultimo_comentario = $this->medicamentos_model->guardar_tbl_comentarios($comentario_por_guardar);
			echo "comentario registrado id: ". $id_ultimo_comentario;
		}
	}

	public function expediente_terminado()
	{
		if ($_POST) 
		{
			$datos_expediente = array(
				'fecha_realizado' 	=> date('Y-m-d H:i:s'),
				'estado'			=> 'Terminado'
			);
			$this->medicamentos_model->guardar_expediente_terminado($this->input->post('expediente'), $datos_expediente);
			echo $this->medicamentos_model->consultar_siguiente_expediente(array('id_usuario' => $this->user->id))->row()->expediente;
		}
	}
}