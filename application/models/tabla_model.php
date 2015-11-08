<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tabla_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function correr_procedimiento_almacenado($nombre_tabla = null)
	{
		if ( ! empty($nombre_tabla)) 
		{
			$sql = "call Recrear_reportes_endcase('$nombre_tabla')";
	        //echo "SQL ".$sql;
	        $data = $this->db->query($sql);
		}
	}
}