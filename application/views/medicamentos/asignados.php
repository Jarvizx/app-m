<table id="tabla_asignados" class="table">
	<thead>
		<tr>
			<th># expediente</th>
			<th>Estado</th>
			<!--<th>Nombre</th>-->
			<!--<th>Usuario asignado</th>-->
		</tr>
	</thead>
	<tbody>
		<?php foreach ($lista_asignados as $k_lista_asignados => $v_lista_asignados): ?>
			<tr>
				<td>
					<a href="<?=base_url().'/medicamentos/expediente/'.$v_lista_asignados->expediente;?>"><?= $v_lista_asignados->expediente; ?></a>
				</td>
				<td>
					<?php echo $v_lista_asignados->estado; ?>
				</td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>

<ul class="pagination">
	<?php echo $this->pagination->create_links() ?>
</ul>