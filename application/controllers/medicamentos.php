<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Medicamentos extends CI_Controller {

	public $user;

	function __construct() 
	{  
	    parent::__construct();
		$this->load->library(array('ion_auth', 'layout', 'session', 'pagination'));
	    $this->load->helper(array('form', 'url'));
		$this->load->model('medicamentos_model');
	
	    if ( ! $this->ion_auth->logged_in()) 
	    {
			redirect('auth/login', 'refresh');
	    }
	    $this->user = $this->ion_auth->user()->row();
	}

	public function asignar()
	{
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

	public function asignados($expediente = null)
	{
		$datos['texto'] = 'asignados';

		if ( ! empty($expediente)) 
		{
			$parametro_expediente = array(
				'NumeroExpediente' => $expediente
			);
			$datos['tbl_rev_expediente']= $this->medicamentos_model->consultar_tbl_rev_expediente($parametro_expediente);
			
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


			$this->layout->view('medicamentos/asignados_form',$datos);
		}
		else
		{
			$this->layout->view('medicamentos/asignados',$datos);
		}
	}

	public function asignados_guardados()
	{
		$datos['texto'] = 'asignados guardados';
		$this->layout->view('medicamentos/asignados_guardados',$datos);
	}

	public function consolidado()
	{
		$datos['texto'] = 'consolidado';
		$this->layout->view('medicamentos/consolidado',$datos);
	}
}