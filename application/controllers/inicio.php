<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inicio extends CI_Controller {

	
	public $user;

	function __construct() {  
		

	    parent::__construct();
		$this->load->library('ion_auth');
	    $this->load->library('layout');
	    $this->load->library('session');
	    $this->load->helper(array('form', 'url'));
	
	    if (!$this->ion_auth->logged_in()) {
	       	// redirect to login view
			redirect('auth/login', 'refresh');

	    }
	    $this->user = $this->ion_auth->user()->row();
	}

	function index()
	{
		//$user_groups = $this->ion_auth->get_users_groups($this->user->id)->result();
		$data = array();
		$this->layout->view('inicio/home', $data);
	}
	
	function contactos()
	{
		echo "string";
	}
}