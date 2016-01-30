<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tabla extends CI_Controller {

	public $user;

	function __construct() 
	{  
	    parent::__construct();
		$this->load->library(array('ion_auth', 'layout', 'session', 'pagination', 'grocery_CRUD'));
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

	public function _example_output($output = null)
	{
		$this->load->view('grocery/crud_render',$output);
	}

	public function index($nombre_tabla = null)
	{
		$this->is_login();
		
		$id_grupo_usuario = (array) reset($this->ion_auth->get_users_groups($this->user->id)->result());
		// donde 1 es admin, 2 es ministerio, 3 el coordinador, 4 digitador, 5 externo			
		if(in_array($id_grupo_usuario['id'], array(1,2,3,4)))
		{
			if ( ! empty($nombre_tabla))
			{
				// correr el procedimiento almacenado
				$this->load->model('tabla_model');
				$this->tabla_model->correr_procedimiento_almacenado($nombre_tabla);
				try {
					$crud = new grocery_CRUD();
					$crud->set_theme('flexigrid-inline-edit');
					$crud->set_table($nombre_tabla);
					// $crud->unset_edit();
					$crud->unset_delete();
					$output = $crud->render();
					$this->_example_output($output);
				} catch (Exception $e) {
					echo "el nombre de tabla no es valida";
				}
			}
			else
			{
				redirect('/', 'refresh');
			}
		}
		else
		{
			redirect('/', 'refresh');
		}
	}
}