<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Medicamentos_model extends CI_Model
{
	protected $tbl_asignacion;
	protected $tbl_rev_expedientes;
	protected $tbl_referencia;
	protected $tbl_invima_medicamento;
	protected $tbl_rev_expediente_pa;
	protected $tbl_control_cambios;
	protected $tbl_permisos_tablas;
	protected $tbl_comentarios;
	protected $vws_consolidado_edicion_agrupado;//vista
	protected $tbl_invima_pa_homologado_texto;//vista
	protected $vws_siguiente_expediente;//vista
    protected $tbl_rev_expediente_pc;
    protected $tbl_rev_expediente_pc_pa;
    protected $tbl_invima_pc_texto;
    protected $vws_listado;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tbl_asignacion = 'tbl_asignacion';
		$this->tbl_rev_expedientes = 'tbl_rev_expedientes';
		$this->tbl_referencia = 'tbl_referencia';
		$this->tbl_invima_medicamento = 'tbl_invima_medicamento_homologado'; // ante era tbl_invima_medicamento
		$this->tbl_rev_expediente_pa = 'tbl_rev_expediente_pa';
		$this->tbl_control_cambios = 'tbl_control_cambios';
		$this->tbl_permisos_tablas = 'tbl_permisos_tablas';
		$this->tbl_comentarios = 'tbl_comentarios';
		$this->vws_consolidado_edicion_agrupado = 'vws_consolidado_edicion_agrupado';//vista
		$this->tbl_invima_pa_homologado_texto = 'tbl_invima_pa_homologado_texto';//vista
		$this->vws_siguiente_expediente = 'vws_siguiente_expediente';//vista
        $this->tbl_rev_expediente_pc = 'tbl_rev_expediente_pc';
        $this->tbl_rev_expediente_pc_pa = 'tbl_rev_expediente_pc_pa';
        $this->tbl_invima_pc_texto = 'tbl_invima_pc_texto';
        $this->vws_listado = 'tbl_listado';
	    date_default_timezone_set('America/Bogota');
	}

	# 1
	public function numero_de_filas($tabla = null)
	{
		if ($tabla != null) 
		{
			$sql = "SELECT COUNT(*) as total FROM $tabla";
	        $data = $this->db->query($sql);
        	return $data->row();
		}
	}

	# 1.1
	public function numero_de_filas_asignadas($parametros = null)
	{
		if ($parametros != null) 
		{
			$sql = "SELECT COUNT(*) as total FROM $this->tbl_asignacion where id_usuario = '" .$parametros['id_usuario'] ."' and estado = '" . $parametros['estado'] . "'";
	        $data = $this->db->query($sql);
        	return $data->row();
		}
	}

	# 2
	public function consultar_asignacion($parametros, $limit, $offset)
	{
		$this->db->select('id, expediente, estado, id_coordinador');
		return $this->db->get_where($this->tbl_asignacion, $parametros, $limit, $offset);
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
			$this->db->update($this->tbl_asignacion, $datos_asignacion);
			return $this->db->affected_rows();
    	}
    }

    # 5
    public function consultar_tbl_permisos_tablas($tabla = null)
    {
		$this->db->select('tabla, actualizar, insertar');
		return $this->db->get_where($this->tbl_permisos_tablas, $tabla);
    }

    # 6, consulto los expedientes asignados (esta funcion puede ser remplazada por )
    public function consultar_expedientes_asignados($id_usuario = null)
    {
    	if ( ! empty($id_usuario)) 
    	{
			$this->db->select('id, expediente, estado, id_coordinador');
			return $this->db->get_where($this->tbl_asignacion, $parametros, $limit, $offset);
    	}
    }
    
    # Gran Formulario

    public function consultar_tbl_rev_expedientes($parametros = array())
    {
		//$this->db->select('id, expediente, estado, id_coordinador');
		return $this->db->get_where($this->tbl_rev_expedientes, $parametros);
    }

    public function consultar_tbl_referencia_wherein($parametros = array())
    {
        $this->db->select('codigo, nombre_codigo, propiedad');
        $this->db->where_in(key($parametros),$parametros['propiedad']);
        $this->db->order_by('nombre_codigo', 'ASC');
        $resultados_tbl_referencia = $this->db->get($this->tbl_referencia);
        // echo "SQL: ".$this->db->last_query();
        return $resultados_tbl_referencia->result_array();

    }
    public function consultar_tbl_referencia($parametros = array())
    {
		$this->db->select('codigo, nombre_codigo', 'propiedad');
		return $this->db->order_by('nombre_codigo', 'ASC')->get_where($this->tbl_referencia, $parametros);
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

    # consultar historial de comentarios del expediente de una vista
    public function consultar_historial_comentarios($expediente)
    {
        if ( ! empty($expediente)) 
        {
            return $this->db->get_where($this->vws_consolidado_edicion_agrupado, $expediente);
            // echo "SQL: " . $this->db->last_query();
        }
    }

    # consultar historial de comentarios del expediente de una vista...
    /*public function consultar_historial_comentario_x_campo($expediente)
    {
    	if ( ! empty($expediente)) 
    	{
            $this->db->select('texto');
    		return $this->db->get_where($this->vws_consolidado_edicion_agrupado, $expediente);
    	}
    }*/

    # consultar historial de comentarios del expediente de una tabla/ legacy!
    public function consultar_historial_comentarios_tabla($expediente)
    {
    	if ( ! empty($expediente)) 
    	{
    		return $this->db->get_where($this->tbl_comentarios, $expediente);
    	}
    }

    public function consultar_tbl_invima_pa_homologado_texto($expediente)
    {
    	if ( ! empty($expediente)) 
    	{
    		return $this->db->get_where($this->tbl_invima_pa_homologado_texto, $expediente);
    	}
    }


    # Guardar la informacion guardada del Gran Formulario
    # tbl_control_cambios
    public function guardar_expediente_asignado_tbl_control_cambios($datos)
    {
    	//echo "<pre>";
    	//print_r($datos);
    	//echo "</pre>";
    	$this->db->insert($this->tbl_control_cambios, $datos);
		//echo "SQL-> " . $this->db->last_query();
		return $this->db->insert_id();
    }

    # actualizacion general (multiple funcion - uso)
    public function actualizar_expediente_tabla_fuente($id = null, $nombre_tabla = null, $datos_tabla = array())
    {
    	if ( ! empty($id))
    	{
			$this->db->where('id', $id);
			$this->db->update($nombre_tabla, $datos_tabla);
			//echo "SQL : " . $this->db->last_query();
			return $this->db->affected_rows();
    	}
    }

    # consultar proximo expediente
    public function consultar_siguiente_expediente($id_usuario = null)
    {
    	if ( ! empty($id_usuario))
    	{

    		//return $this->db->get_where($this->tbl_asignacion, $id_usuario);
    		return $this->db->get_where($this->vws_siguiente_expediente, $id_usuario);
    	}
    }

    public function consultar_siguiente_expediente_en_cola()
    {
        $sql = "SELECT * 
                FROM ".$this->tbl_asignacion."
                WHERE estado = 'En Cola'
                AND (esta_en_revision = 0
                OR esta_en_revision IS NULL)
                ORDER BY id ASC
                LIMIT 0,1";
        
        return  $this->db->query($sql);
    }
    public function contar_expedientes_en_cola()
    {
        $sql = "SELECT count(*) as total 
                FROM ".$this->tbl_asignacion."
                WHERE estado = 'En Cola'
                AND (esta_en_revision = 0
                OR esta_en_revision IS NULL)";
        
        $data = $this->db->query($sql);
        return $data->row();
    }

    public function consultar_lista_expediente_en_cola($texto_limite)
    {
        $sql = "SELECT * 
                FROM ".$this->tbl_asignacion."
                WHERE estado = 'En Cola'
                AND (esta_en_revision = 0
                OR esta_en_revision IS NULL)
                LIMIT $texto_limite";
        
        return $this->db->query($sql);
    }

    # guardar comentario
    public function guardar_tbl_comentarios($datos)
    {
    	$this->db->insert($this->tbl_comentarios, $datos);
		//echo "SQL-> " . $this->db->last_query();
		return $this->db->insert_id();
    }

    # guardar expediente terminado, 
    public function actualizar_tbl_asignacion($expediente, $datos_tabla)
    {
    	if ($expediente) 
    	{
	    	$this->db->where('expediente', $expediente);
			$this->db->update($this->tbl_asignacion, $datos_tabla); 
			//echo "SQL : " . $this->db->last_query();
			return $this->db->affected_rows();
    	}
    }

    # consulta pc
    public function consultar_tbl_rev_expediente_pc($expediente)
    {
        if ( ! empty($expediente)) 
        {
            return $this->db->get_where($this->tbl_rev_expediente_pc, $expediente);
        }
    }
    public function consultar_tbl_rev_expediente_pc_pa($expediente)
    {
        if ( ! empty($expediente)) 
        {
            return $this->db->get_where($this->tbl_rev_expediente_pc_pa, $expediente);
        }
    }
    # consultar_tbl_invima_pc_texto
    public function consultar_tbl_invima_pc_texto($expediente)
    {
        if ( ! empty($expediente)) 
        {
            return $this->db->get_where($this->tbl_invima_pc_texto, $expediente);
        }
    }

    # consultar_expedientes para el buscador
    public function consultar_vws_listado($parametros = null, $texto_limite = null)
    {
        $sql = "SELECT *
                FROM ".$this->vws_listado."
                WHERE (concat_ws('-',NumeroExpediente,texto) 
                LIKE '%".$parametros."%')
                ORDER BY NumeroExpediente
                LIMIT $texto_limite";
        $data = $this->db->query($sql);
        return $data->result();
        // echo "SQL".$this->db->last_query();
        // return $this->db->get_where($this->vws_listado, $parametros, $limit, $offset);
    }

    public function numero_de_filas_vws_listado($parametros = null)
    {
        
        $sql = "SELECT count(*) as total
                FROM ".$this->vws_listado."
                WHERE (concat_ws('-',NumeroExpediente,texto) 
                LIKE '%".$parametros."%')
                ORDER BY NumeroExpediente";
        $data = $this->db->query($sql);
        return $data->row();
    }
}