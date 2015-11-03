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
		if ( ! empty($nombre_tabla))
		{
			// cualquier usuario logeado puede acceder
			$this->is_login();

			try {
				$crud = new grocery_CRUD();
				$crud->set_theme('datatables');
				$crud->set_table($nombre_tabla);
				$output = $crud->render();
				$this->_example_output($output);
			} catch (Exception $e) {
				echo "el nombre de tabla no es valida";
			}
		}
		{
			//redirect('/', 'refresh');
		}

	}
}