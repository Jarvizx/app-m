<div class="row">
	<div class="col-md-6">
		<?php $this->load->view('auth/login', $vars_login); ?>
	</div>
	<div class="col-md-6">
		<?php $this->load->view('auth/create_user', $vars_register); ?>
		
 		<?php if( ! empty($expediente)): ?>
 			<hr>
 			<a href="/medicamentos/expediente/<?=$expediente;?>/1"> No quiero registrarme, deseo ver el expediente anónimamente. </a>
 		<?php endif ?>
	</div>
</div>

