<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inicio extends CI_Controller {
	
	public $user;

	function __construct() 
	{  
	    parent::__construct();
		$this->load->library(array('ion_auth', 'layout', 'session'));
	    $this->load->helper(array('form', 'url'));
	
	    if ( ! $this->ion_auth->logged_in()) 
	    {
			redirect('auth/login', 'refresh');
	    }
	    $this->user = $this->ion_auth->user()->row();
	}

	function index()
	{
		$data['titulo'] = 'Inicio App M...';
		$this->layout->view('inicio/home', $data);
	}
}