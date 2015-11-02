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
</head>
<body>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			
			<h1 class="page-header">
				Estándar en Medicamentos.
				<small>version alpha 0.1</small>
			</h1>
		</div>
		
		<div class="col-md-2">	
				
			<?php if (isset($this->user)): ?>
				<p>Hola <?php echo $this->user->first_name; ?>,</p>
			<?php endif ?>
			<ul class="nav nav-pills nav-stacked" style="">

			<?php //print_r($this->uri->uri_string); ?>
			<?php 
				/*
					1 admin
					2 coordinador
					3 digitador
				*/
			?>

				<li>
					<a href="/">Inicio</a>
				</li>
				<?php // donde 1 es admin, 2 es cordinador  y 3 el digitador 

						if(reset($this->ion_auth->get_users_groups($this->user->id)->result())->id == 1
					  	|| reset($this->ion_auth->get_users_groups($this->user->id)->result())->id == 2): ?>
				<li>
					<a href="/medicamentos/asignar">Asignar Datos</a>
				</li>
				<li>
					<a href="/medicamentos/consolidado">Consolidado</a>
				</li>
				<li>
					<a href="/auth">Usuarios</a>
				</li>
				<?php endif ?>
				<li>
					<a href="/medicamentos/asignados">Datos Asignados *</a>
				</li>
				<li>
					<a href="/medicamentos/asignados_guardados">Datos Guardados </a>
				</li>
				<li>
					<a href="/auth/logout">Cerrar</a>
				</li>

			</ul>
		</div>
		<div class="col-md-10">
			<!--content dinamic-->
			
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