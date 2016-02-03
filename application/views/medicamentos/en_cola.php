<table id="tabla_asignados" class="table">
	<thead>
		<tr>
			<th># Expediente</th>
			<th>Estado</th>
			<!--<th>Nombre</th>-->
			<!--<th>Usuario asignado</th>-->
		</tr>
	</thead>
	<tbody>
		<?php foreach ($lista_en_cola as $k_lista_en_cola => $v_lista_en_cola): ?>
			<tr>
				<td>
					<a href="<?=base_url().'medicamentos/expediente/'.$v_lista_en_cola->expediente;?>"><?= $v_lista_en_cola->expediente; ?></a>
				</td>
				<td>
					<?php echo $v_lista_en_cola->estado; ?>
				</td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>

<ul class="pagination">
	<?php echo $this->pagination->create_links() ?>
</ul>