<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/assets/css/jquery.dataTables.css">
    <link rel="stylesheet" href="/assets/css/bootstrap-multiselect.css" type="text/css">
	<script type="text/javascript" src="/assets/js/jquery-1.11.1.js"></script>
	<script type="text/javascript" src="/assets/js/jquery.form.js"></script>
	<script type="text/javascript" src="/assets/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery.number.js"></script>
	<script type="text/javascript" src="/assets/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/assets/js/bootstrap-multiselect.js"></script>
    <style type="text/css">
		.img-cimun{
		    position: absolute;
		    top: 0;
		    right: 0;
		    margin: 1em;
		}
	</style>
</head>
<body>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			
			<h1 class="page-header">
				Estándar en Medicamentos.
				<small>version alpha 0.2</small>
			</h1>
			<a href="/medicamentos/buscador">				
				<img class="img-cimun" src="/assets/images/LogoCIMUN.png" width="230">
			</a>
		</div>
		<?php if ( $this->ion_auth->logged_in()): ?>	
			<div class="col-md-2">	
					
				<?php if (isset($this->user)): ?>
					<p>Hola <?php echo $this->user->first_name; ?>,</p>
				<?php endif ?>
				<ul class="nav nav-pills nav-stacked" style="">
						<li>
							<a href="/medicamentos/buscador">Inicio</a>
						</li>
					<?php $id_grupo_usuario = (array) reset($this->ion_auth->get_users_groups($this->user->id)->result());
					// donde 1 es admin, 2 es ministerio, 3 el coordinador, 4 digitador, 5 externo
					if(in_array($id_grupo_usuario['id'], array(1,2))): ?>
						<li>
							<a href="/medicamentos/asignar">Asignar Datos</a>
						</li>
						<li>
							<a href="/medicamentos/consolidado">Consolidado</a>
						</li>
						<li>
							<a href="/auth">Usuarios</a>
						</li>
						<li>
							<a href="/medicamentos/asignados_guardados">Datos Guardados </a>
						</li>
						<li>
							<a href="/medicamentos/asignados">Datos Asignados *</a>
						</li>	
					<?php elseif(in_array($id_grupo_usuario['id'], array(1,2,3,4))): ?>
						<li>
							<a href="/medicamentos/asignados_guardados">Datos Guardados </a>
						</li>
						<li>
							<a href="/medicamentos/asignados">Datos Asignados *</a>
						</li>
					<?php endif ?>
						<li>
							<a href="/medicamentos/buscador">Buscar Expediente </a>
						</li>
						<li>
							<a href="/auth/logout">Cerrar</a>
						</li>
				</ul>
			</div>
			<?php $width_div = '10'; ?>
		<?php else: ?>
			<?php $width_div = '12'; ?>
		<?php endif ?>
				<!--content dinamic-->
			
			<div class="col-md-<?=$width_div;?>">
				<?php echo $content_for_layout; ?>
			</div>
	</div><!-- close row -->
</div><!-- close <div class="container-fluid"> -->
<script type="text/javascript">
	$(document).on('ready', function(){
		$('input.number').number( true, 2 );
	});
</script>
</body>
</html>