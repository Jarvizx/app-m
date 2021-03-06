<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Medicamentos extends CI_Controller {

	public $user;
	private $correo_usuario_externo;

	function __construct() 
	{  
	    parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth', 'layout', 'form_validation', 'session', 'pagination'));
	    $this->load->helper(array('form', 'url', 'language'));
		$this->load->model('medicamentos_model');
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->lang->load('auth');

		// debug
	
		/*$sections = array(
		    'queries' => TRUE
	    );
		$this->output->set_profiler_sections($sections);
		$this->output->enable_profiler(TRUE);*/
		// /debug

	    $this->user = $this->ion_auth->user()->row();
	    if ( ! empty($this->input->get('correo_usuario_externo')))
	    {
	    	$this->correo_usuario_externo = $this->input->get('correo_usuario_externo');
	    }
	}

	public function index()
	{
		redirect('medicamentos/buscador', 'refresh');
		//$this->buscador();
	}

	private function is_login()
	{
		if ( ! $this->ion_auth->logged_in()) 
	    {
			redirect('auth/login', 'refresh');
	    }
	}

	public function asignar()
	{
		$this->is_login();
		$datos['texto'] = 'asignar';

		$registro_por_pagina = $this->uri->segment(4);
		$registros = (!empty($registro_por_pagina)) ? $registro_por_pagina : 20; 
		$config['base_url'] = base_url().'medicamentos/asignar/'; 
		$config['total_rows'] = $this->medicamentos_model->numero_de_filas('tbl_asignacion')->total;
		$config['per_page'] = $registros; //Número de registros mostrados por páginas
        $config['num_links'] = 10; //Número de links mostrados en la paginación
 		$config['first_link'] = 'Primera';
		$config['last_link'] = 'Última';
        $config["uri_segment"] = 3;//el segmento de la paginación
		$config['next_link'] = 'Siguiente';
		$config['prev_link'] = 'Anterior';
		$config['suffix'] = '/'.$registros."?parametro_de_busqueda=".$this->input->get('parametro_de_busqueda');

		$this->pagination->initialize($config); //inicializamos la paginación	

		$datos["lista_asignacion"] 	= $this->medicamentos_model->consultar_asignacion(null, $config['per_page'], $this->uri->segment(3))->result();
		$datos["usuarios"]			= $this->medicamentos_model->consultar_usuarios();


		$this->layout->view('medicamentos/asignar',$datos);
	}

	public function guardar_asignacion()
	{
		$this->is_login();
		if ($_POST) 
		{
			$id_usuario = $this->input->post('id_usuario');
			$expedientes = $this->input->post('expedientes');
			
			foreach ($expedientes as $k_expedientes => $v_id_expedientes) 
			{	
				$datos_asignacion = array(
					'fecha_asignado'=> date('Y-m-d H:i:s'),
					'estado'		=> 'Asignado',
					'id_coordinador'=> $this->user->id,
					'id_usuario'	=> $id_usuario
				);
				$this->medicamentos_model->guardar_asignacion($v_id_expedientes, $datos_asignacion);
			}
			echo "<pre>";
			print_r($datos_asignacion);
		}
	}

	public function debug()
	{

		if ($_POST) 
		{
			$id_usuario = $this->input->post('id_usuario');
			$expedientes = $this->input->post('expedientes');
			
			foreach ($expedientes as $k_expedientes => $v_id_expedientes) 
			{	
				$datos_asignacion[] = array(
					'fecha_asignado'=> date('Y-m-d H:i:s'),
					'estado'		=> 'Asignado',
					'id_coordinador'=> $this->user->id,
					'id_usuario'	=> $id_usuario
				);
				//$this->medicamentos_model->guardar_asignacion($v_id_expedientes, $datos_asignacion);
			}
			echo "<pre>";
			print_r($datos_asignacion);
		}
	}

	public function expediente($expediente = null, $es_anonimo = 0)
	{
		if ( ! empty($expediente))
		{
			if ( ! $this->ion_auth->logged_in() && empty($this->correo_usuario_externo))
			{
				$datos['mostrar_input_correo'] = true;
				$datos['grupoUsuario']['id'] = 0;
			}
			else
			{
				$datos['mostrar_input_correo'] = false;
				$datos['grupoUsuario'] = (array) reset($this->ion_auth->get_users_groups($this->user->id)->result());
			}
			$datos['es_anonimo'] = $es_anonimo;

			if ($datos['mostrar_input_correo'] == true && $es_anonimo == 0) 
			{
				// Si no es usuario anomino y tampoco tiene algun correo por get, cargo esta vista
				redirect('medicamentos/buscador');
				// $this->layout->view('medicamentos/inscribir_correo',null);
				// exit();
			}

			// validar el numero de resultados y saber si existe ese expediente
			$parametro_expediente = array(
				'NumeroExpediente' => $expediente
			);
			$datos['tbl_rev_expedientes'] = $this->medicamentos_model->consultar_tbl_rev_expedientes($parametro_expediente);

			if($datos['tbl_rev_expedientes']->num_rows() > 0)
			{
				// nuevo forma de consultar con los parametros
				$parametros_tbl_referencia = array(
					'propiedad' => array(
						'IdentificadorFormaComercializacion',
						'FFM',
						'VAD',
						'DCI',
						'UMM',
						'IndicadorMuestraMedica_old',
						'TipoEstandarParaCantidadYUnidadMedida',
						'UPR',
						'UPR',
						'IndicadorMuestraMedica',
						'IdentificadorMarca'
					)
				);

				$resultados_tbl_referencia	= $this->medicamentos_model->consultar_tbl_referencia_wherein($parametros_tbl_referencia);

				$datos['parametros_tbl_referencia'] = null;
				foreach ($resultados_tbl_referencia as $k_resultados_tbl_referencia => $v_resultados_tbl_referencia) 
				{
					$datos['parametros_tbl_referencia'][$v_resultados_tbl_referencia['propiedad']][] = $v_resultados_tbl_referencia;
				}

				$parametro_expediente_para_invima = array(
					'expediente' => $expediente
				);
				$datos['tbl_invima_medicamento']	= $this->medicamentos_model->consultar_tbl_invima_medicamento($parametro_expediente_para_invima);

				// 2do tr
				$parametro_referencia = array(
					'NumeroExpediente' => $expediente
				);
				$datos['tbl_rev_expediente_pa']	= $this->medicamentos_model->consultar_tbl_rev_expediente_pa($parametro_referencia);

				$datos['tbl_invima_pa_homologado_texto'] = $this->medicamentos_model->consultar_tbl_invima_pa_homologado_texto(array('expediente' => $expediente));

				# presentación comercial
				$datos['tbl_rev_expediente_pc'] = $this->medicamentos_model->consultar_tbl_rev_expediente_pc($parametro_expediente);

				# consulta tbl_invima_pc_texto
				$datos['tbl_invima_pc_texto'] = $this->medicamentos_model->consultar_tbl_invima_pc_texto(array('expediente'=>$expediente));

				# presentación comercial principio activo
				$datos['tbl_rev_expediente_pc_pa'] = $this->medicamentos_model->consultar_tbl_rev_expediente_pc_pa($parametro_expediente);

				# nueva consulta del invima
				$datos['tbl_invima_pa'] = $this->medicamentos_model->consultar_tbl_invima_pa(array('expediente'=>$expediente))->result_array();

				// 1 sola consulta para los comentarios
				
				$comentarios = $this->medicamentos_model->consultar_historial_comentarios($expediente)->result();
				
				$datos['comentarios'] = null;
				foreach ($comentarios as $k_comentarios => $v_comentarios) 
				{
					$datos['comentarios'][$v_comentarios->campo][] = $v_comentarios;
				}

				// cambio el estado (esta_en_revision) 0 ó NULL por 1 en tbl_asignacion 
				if ($this->ion_auth->logged_in())
				{
					// donde 1 es admin, 2 es ministerio, 3 el coordinador, 4 digitador, 5 externo
					if (in_array($datos['grupoUsuario']['id'], array(1,2,3,4))) 
					{
						$actualizar_esta_en_revision = array(
							'esta_en_revision'	=> 1
						);
						$this->medicamentos_model->actualizar_tbl_asignacion($expediente, $actualizar_esta_en_revision);
					}
				}

				$this->layout->view('medicamentos/formulario_expediente',$datos);
			}
			else
			{
				// el expediente no existe
				$this->layout->view('medicamentos/no_existe_expediente',$parametro_expediente);
			}
		}
		else
		{
			$this->layout->view('medicamentos/no_existe_expediente',array('NumeroExpediente'=>0));
		}
	}

	public function asignados($num = null)
	{
		$this->is_login();

		$asignados = array(
			'id_usuario'=> $this->user->id,
			'estado'	=> 'Asignado'
		);

		$registro_por_pagina 	= $this->uri->segment(4);
		$registros 				= (!empty($registro_por_pagina)) ? $registro_por_pagina : 100; 
		$config['base_url'] 	= base_url().'medicamentos/asignados//'; 
		$config['total_rows'] 	= $this->medicamentos_model->numero_de_filas_asignadas($asignados)->total;
		$config['per_page'] 	= $registros; //Número de registros mostrados por páginas
        $config['num_links'] 	= 10; //Número de links mostrados en la paginación
 		$config['first_link'] 	= 'Primera';
		$config['last_link'] 	= 'Última';
        $config["uri_segment"] 	= 3;//el segmento de la paginación
		$config['next_link'] 	= 'Siguiente';
		$config['prev_link'] 	= 'Anterior';
		$this->pagination->initialize($config); //inicializamos la paginación	
		$datos["lista_asignados"] = $this->medicamentos_model->consultar_asignacion($asignados, $config['per_page'], $this->uri->segment(3))->result();
		// la vista
		$this->layout->view('medicamentos/asignados',$datos);
	}

	public function guardar_expediente_asignado()
	{
		//echo "en guardar_expediente_asignado" . $this->correo_usuario_externo;
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
				$usuario = 29; // id usuario anonimo
				$nivel 	 = 6; // id grupo usuario anonimo
				$nombre_nivel = 'anonimo'; // id grupo usuario anonimo
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

			// creo un array de la(s) tabla(s)
			$tablas_por_insertar = explode(',', str_replace(' ', '', $datos_por_guardar['tabla']));
			// por defecto sienpre se agrega esta columna 
			$tablas_por_insertar[] = 'tbl_control_cambios';

			$contador_permisos = 0;
			foreach ($tablas_por_insertar as $k_tablas_por_insertar => $v_tablas_por_insertar) 
			{
				$datos_tbl_permisos_tablas[$k_tablas_por_insertar] = $this->medicamentos_model->consultar_tbl_permisos_tablas(array('tabla' => $v_tablas_por_insertar))->row();
				if ( ! empty($datos_tbl_permisos_tablas[$k_tablas_por_insertar])) 
				{
					// ! pendiente, agregar en la tabla permisos, el usuario anonimo para la tabla control de cambios.
					// solo actualizar
					$update_datos_tbl_permisos_tablas[$contador_permisos] = explode(',', str_replace(' ', '', $datos_tbl_permisos_tablas[$k_tablas_por_insertar]->actualizar));
					if (in_array($nombre_nivel, $update_datos_tbl_permisos_tablas[$contador_permisos])) 
					{
						echo "actualizo del grupo " . $nombre_nivel . " la tabla " . $v_tablas_por_insertar . ". ";

						// caso especial para la tabla control de cambios
						if ($v_tablas_por_insertar == 'tbl_control_cambios')
						{
							$this->cambiar_estado_expediente_por_laboratorio($this->input->post('expediente'));
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
							
							// Esto se cumple es mas para las filas nuevas que se agregen
							if ($v_tablas_por_insertar == 'tbl_rev_expediente_pa') 
							{
								$expediente = explode("&",$id_registro);

								$parametro_tbl_rev_expediente_pa = array(
									'id' => $id_registro,
									'NumeroExpediente' => $expediente[0],
									'NombrePrincipioActivo' => $expediente[1]
								);

								$resultado_tbl_rev_expediente_pa = $this->medicamentos_model->consultar_tbl_rev_expediente_pa($parametro_tbl_rev_expediente_pa);
								
								if ($resultado_tbl_rev_expediente_pa->num_rows() == 0) 
								{
									$this->medicamentos_model->guardar_tbl_rev_expediente_pa($parametro_tbl_rev_expediente_pa);
								}
							}

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
		  	if ( $this->ion_auth->logged_in())
			{
				$usuario = $this->user->id;
				$nivel 	 = reset($this->ion_auth->get_users_groups($this->user->id)->result())->id;
				$nombre_nivel = reset($this->ion_auth->get_users_groups($this->user->id)->result())->name;
			}
			else
			{
				$usuario = 29; // id usuario anonimo
				$nivel 	 = 6; // id grupo usuario anonimo
				$nombre_nivel = 'anonimo'; // id grupo usuario externo
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

			$this->cambiar_estado_expediente_por_laboratorio($this->input->post('expediente'));
			// SI ES LABORATORIO
				// CAMBIO SU ESTADO EXPEDIENTE ($EXPEDIENTE)

			$comentario = $this->medicamentos_model->consultar_historial_comentarios_x_campo(array(
				'llave' => $this->input->post('llave'), 
				'campo' 	 => $this->input->post('campo')
			))->row();
			// select * from vws_consolidado_edicion_agrupado where campo = 'MarcaSignoDistintivoComercial' and expediente = 2202
			//echo "comentario registrado id: ". $id_ultimo_comentario, $comentario->texto;
			echo $comentario->texto;
		}
	}

	// si un laboratorio, agrega un conentario o modifica un valor, se cambia el estado del expediente por En Cola
	public function cambiar_estado_expediente_por_laboratorio($expediente)
	{
	  	if ( ! empty($expediente) && $this->ion_auth->logged_in())
	  	{
	  		$id_rol_usuario = (array) reset($this->ion_auth->get_users_groups($this->user->id)->result());
	  		// id rol laboratorio
	  		if ($id_rol_usuario['id'] == 5) 
	  		{
	  			$datos_expediente = array(
					'fecha_realizado' 	=> date('Y-m-d H:i:s'),
					'estado'			=> 'En Cola'
				);
				$this->medicamentos_model->actualizar_tbl_asignacion($expediente, $datos_expediente);
	  		}
	  	}

	}

	public function expediente_terminado()
	{
		if ( $this->ion_auth->logged_in())
		{
			if ($_POST) 
			{

				// aqui consulto si es un expediente En Cola,
				// si es en cola
				// consulto el siguiente en cola
				// el redirect es para el siguiente en cola
				// si no, al siguiente asignado
				$expediente = $this->input->post('expediente');
				// $expediente = 2203;
				
				$parametros_busqueda_en_cola = array(
					'expediente'=> $expediente,
					'estado'	=> 'En Cola'
				);
				$esta_en_cola = $this->medicamentos_model->consultar_asignacion($parametros_busqueda_en_cola,1,0);
				
				$datos_expediente = array(
					'fecha_realizado' 	=> date('Y-m-d H:i:s'),
					'estado'			=> 'Terminado',
					'esta_en_revision'	=> 0
				);
				$this->medicamentos_model->actualizar_tbl_asignacion($expediente, $datos_expediente);
				

				if ($esta_en_cola->num_rows() > 0) 
				{
					# consulto siguiente expediente en cola
					$consultar_proximo_expediente_en_cola = $this->medicamentos_model->consultar_siguiente_expediente_en_cola();
					if ($consultar_proximo_expediente_en_cola->num_rows() > 0) 
					{
						$resultados_proximo_expediente_en_cola = $consultar_proximo_expediente_en_cola->row();
						$proximo_expediente = $resultados_proximo_expediente_en_cola->expediente;
					}
					else
					{
						// aqui el js, valida. y si llega a este else, lo redirecciona a la pagina de asignacion
						$proximo_expediente = 0;
						// $proximo_expediente = $this->medicamentos_model->consultar_siguiente_expediente(array('id_usuario' => $this->user->id))->row()->expediente;
					}
				}
				else
				{
					# 
					$proximo_expediente = $this->medicamentos_model->consultar_siguiente_expediente(array('id_usuario' => $this->user->id))->row()->expediente;
				}


				// queda que al momento de entrar al experiente cambie el esta de estar en revision
				// y terminar esta funcion
				echo $proximo_expediente;
			}
		}
	}

	public function en_cola()
	{
		$this->is_login();

		$registro_por_pagina 	= $this->uri->segment(4);
		$registros 				= (!empty($registro_por_pagina)) ? $registro_por_pagina : 100; 
		$config['base_url'] 	= base_url().'medicamentos/en_cola//'; 
		$config['total_rows'] 	= $this->medicamentos_model->contar_expedientes_en_cola()->total;
		$config['per_page'] 	= $registros; //Número de registros mostrados por páginas
        $config['num_links'] 	= 10; //Número de links mostrados en la paginación
 		$config['first_link'] 	= 'Primera';
		$config['last_link'] 	= 'Última';
        $config["uri_segment"] 	= 3;//el segmento de la paginación
		$config['next_link'] 	= 'Siguiente';
		$config['prev_link'] 	= 'Anterior';
		$this->pagination->initialize($config); //inicializamos la paginación
		
		$texto_limite 			= ( ! empty($registro_por_pagina)) ? $registro_por_pagina.','.$config['per_page'] : $config['per_page'];
		$datos["lista_en_cola"] = $this->medicamentos_model->consultar_lista_expediente_en_cola($texto_limite)->result();
		// la vista
		// $this->layout->view('medicamentos/asignados',$datos);
		$this->layout->view('medicamentos/en_cola',$datos);
		
	}

	public function asignados_guardados()
	{
		$this->is_login();
		$datos['texto'] = 'asignados guardados';
		$this->layout->view('medicamentos/asignados_guardados',$datos);
	}

	public function consolidado()
	{
		$this->is_login();
		$datos['texto'] = 'consolidado';
		$this->layout->view('medicamentos/consolidado',$datos);
	}

	public function buscador()
	{
		$parametro 						= ( ! empty($this->input->get('parametro', true))) ? $this->input->get('parametro', true) : null;
		$numero_de_filas_vws_listado 	= $this->medicamentos_model->numero_de_filas_vws_listado($parametro);
		$registro_por_pagina 			= $this->uri->segment(3);

		$config['base_url'] 	= base_url().'medicamentos/buscador//';
		$config['total_rows'] 	= $numero_de_filas_vws_listado->total;
		$config['per_page'] 	= 20; 	// Número de registros mostrados por páginas
        $config['num_links'] 	= 10; 			// Número de links mostrados en la paginación
 		$config['first_link'] 	= 'Primera';
		$config['last_link'] 	= 'Última';
        $config["uri_segment"] 	= 3;			// el segmento de la paginación
		$config['next_link'] 	= 'Siguiente';
		$config['prev_link'] 	= 'Anterior';
		$config['suffix'] 		= '/?parametro='.$parametro;
		$config['first_url'] 	= $config['base_url'] . $config['suffix']; // fix para conservar el parametro de busqueda

		if ($this->input->get('consultar', true) == 'true' && $registro_por_pagina > 0)
		{
			// limpio el int si esta paginando y busca algo
			redirect('medicamentos/buscador/?parametro='.$parametro);
		}
		$this->pagination->initialize($config); // inicializamos la paginación	
		
		$texto_limite = ( ! empty($registro_por_pagina)) ? $registro_por_pagina.','.$config['per_page'] : $config['per_page'];

		$datos["expedientes"] 	= $this->medicamentos_model->consultar_vws_listado($parametro, $texto_limite);

		// la vista

		$this->layout->view('medicamentos/buscador', $datos);
		
	}

	public function autentificarse()
	{
		$data['expediente'] 	= $this->input->get('expediente', true);

		if ($this->ion_auth->logged_in()) 
	    {
			if ( ! empty($data['expediente'])) 
			{				
				redirect('medicamentos/expediente/'.$data['expediente'] , 'refresh');
			}
			else
			{
				redirect('medicamentos/buscador/' , 'refresh');
			}
	    }

		// cargo las vistas
		$data['vars_login'] 	= $this->login();
		$data['vars_register'] 	= $this->register();

		$this->layout->view('medicamentos/autentificarse', $data);

	}

	// log the user in
	// esta funcion es la copia del controlador de auth
	function login()
	{
		$this->data['title'] = "Login";
		$expediente = $this->input->get('expediente', true);
		
		if ($this->input->post('tipo', true) == 'login')
		{
			//validate form input
			$this->form_validation->set_rules('identity', 'Identity', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');

			if ($this->form_validation->run() == true)
			{
				// check to see if the user is logging in
				// check for "remember me"
				$remember = (bool) $this->input->post('remember');

				if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
				{
					//if the login is successful
					if ( ! empty($expediente)) 
					{				
						redirect('medicamentos/expediente/'.$expediente , 'refresh');
					}
					else
					{
						redirect('medicamentos/buscador/' , 'refresh');
					}
				}
				else
				{
					// if the login was un-successful
					// redirect them back to the login page
					$this->session->set_flashdata('message', $this->ion_auth->errors());
					redirect('medicamentos/autentificarse', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
				}
			}
		}
		else
		{
			// the user is not logging in so display the login page
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['identity'] = array('name' => 'identity',
				'id'    => 'identity',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('identity'),
			);
			$this->data['password'] = array('name' => 'password',
				'id'   => 'password',
				'type' => 'password',
			);

            $this->data['target'] = "medicamentos/login?expediente=".$expediente;

			return $this->data;
		}
	}
	
	// esta funcion es la copia del controlador de auth
	function register()
    {
        $this->data['title'] = "Create User";
		$expediente = $this->input->get('expediente', true);

        /*if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            redirect('auth', 'refresh');
        }*/

        $tables = $this->config->item('tables','ion_auth');
        $identity_column = $this->config->item('identity','ion_auth');
        $this->data['identity_column'] = $identity_column;

        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required');
        if($identity_column!=='email')
        {
            $this->form_validation->set_rules('identity',$this->lang->line('create_user_validation_identity_label'),'required|is_unique['.$tables['users'].'.'.$identity_column.']');
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
        }
        else
        {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
        }
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'trim');
        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

        if ($this->form_validation->run() == true)
        {
            $email    = strtolower($this->input->post('email'));
            $identity = ($identity_column==='email') ? $email : $this->input->post('identity');
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'company'    => $this->input->post('company'),
                'phone'      => $this->input->post('phone'),
            );
        }
        if ($this->form_validation->run() == true && $this->ion_auth->register($identity, $password, $email, $additional_data))
        {
            // check to see if we are creating the user
            // redirect them back to the admin page
            $this->session->set_flashdata('message', $this->ion_auth->messages());;
            // aqui no podemos crear las variables de session, por que se debe activar la cuenta por correo
			// $this->ion_auth->login($this->input->post('email'), $this->input->post('password'), TRUE);
			redirect('medicamentos/buscador', 'refresh');
        }
        else
        {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['first_name'] = array(
                'name'  => 'first_name',
                'id'    => 'first_name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('first_name'),
            );
            $this->data['last_name'] = array(
                'name'  => 'last_name',
                'id'    => 'last_name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('last_name'),
            );
            $this->data['identity'] = array(
                'name'  => 'identity',
                'id'    => 'identity',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('identity
                '),
            );
            $this->data['email'] = array(
                'name'  => 'email',
                'id'    => 'email',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('email'),
            );
            $this->data['company'] = array(
                'name'  => 'company',
                'id'    => 'company',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('company'),
            );
            $this->data['phone'] = array(
                'name'  => 'phone',
                'id'    => 'phone',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('phone'),
            );
            $this->data['password'] = array(
                'name'  => 'password',
                'id'    => 'password',
                'type'  => 'password',
                'value' => $this->form_validation->set_value('password'),
            );
            $this->data['password_confirm'] = array(
                'name'  => 'password_confirm',
                'id'    => 'password_confirm',
                'type'  => 'password',
                'value' => $this->form_validation->set_value('password_confirm'),
            );
            $this->data['target'] = "medicamentos/autentificarse?expediente=".$expediente;

            return $this->data;
        }
    }

}