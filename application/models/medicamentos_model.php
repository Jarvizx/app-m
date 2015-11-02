<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Medicamentos_model extends CI_Model
{
	protected $tabla_asignacion;
	protected $tbl_rev_expediente;
	protected $tbl_referencia;
	protected $tbl_invima_medicamento;
	protected $tbl_rev_expediente_pa;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tabla_asignacion = 'tbl_asignacion';
		$this->tbl_rev_expediente = 'tbl_rev_expedientes';
		$this->tbl_referencia = 'tbl_referencia';
		$this->tbl_invima_medicamento = 'tbl_invima_medicamento';
		$this->tbl_rev_expediente_pa = 'tbl_rev_expediente_pa';
	    date_default_timezone_set('America/Bogota');
	}

	# 1
	public function numero_de_filas($tabla = null)
	{
		if ($tabla != null) 
		{
			//$consulta = $this->db->get($tabla);
			//return $consulta->num_rows();

			$sql = "SELECT COUNT(*) as total FROM $tabla";
	        $data = $this->db->query($sql);
        	return $data->row();
		}
	}

	# 2
	public function consultar_asignacion($parametros, $limit, $offset)
	{
		$this->db->select('id, expediente, estado, id_coordinador');
		return $this->db->get_where($this->tabla_asignacion, $parametros, $limit, $offset);
		//echo $this->db->last_query();
	}

	# 3
	public function consultar_usuarios()
    {
        $query = 'SELECT u.id, u.first_name, u.last_name from users as u where active = 1';
        $data = $this->db->query($query);
        return $data->result_array();
    }

    # 4
    public function guardar_asignacion($id = null, $datos_asignacion = array())
    {
    	if ( ! empty($id))
    	{
			$this->db->where('id', $id);
			$this->db->update($this->tabla_asignacion, $datos_asignacion);
			return $this->db->affected_rows();
    	}
    }

    
    # Gran Formulario

    public function consultar_tbl_rev_expediente($parametros = array())
    {
		//$this->db->select('id, expediente, estado, id_coordinador');
		return $this->db->get_where($this->tbl_rev_expediente, $parametros);
    }

    public function consultar_tbl_referencia($parametros = array())
    {
		$this->db->select('codigo, nombre_codigo');
		return $this->db->get_where($this->tbl_referencia, $parametros);
    }

    # + invima

    public function consultar_tbl_invima_medicamento($parametros = array())
    {
		return $this->db->get_where($this->tbl_invima_medicamento, $parametros);
    }

    public function consultar_tbl_rev_expediente_pa($parametros = array())
    {
    	return $this->db->get_where($this->tbl_rev_expediente_pa, $parametros);
    }

}