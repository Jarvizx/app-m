<style type="text/css">
    .div-buscador{
        width: 50em;
        margin: 0 auto;
    }
</style>

<?php if ( ! empty($this->session->flashdata('message'))): ?>
    <div class="alert alert-success" role="alert">
        <?php echo $this->session->flashdata('message')?>
    </div>
<?php endif ?>


<form method="GET">
    <div class="input-group div-buscador">
        <input type="text" class="form-control" name="parametro" placeholder="Buscar">
        <span class="input-group-btn">
            <input type="hidden" name="consultar" value="true">
            <button class="btn btn-primary" type="submit">Buscar</button>
        </span>
    </div><!-- /input-group -->
</form>

<table id="tabla_asignar" class="table">
    <thead>
        <tr>
            <th># Expediente</th>
            <th>Descripción</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($expedientes as $k_expedientes => $v_expedientes): ?>
            <tr>
                <td>
                    <a href="/medicamentos/autentificarse/?expediente=<?= $v_expedientes->NumeroExpediente;?>">
                        <?php echo $v_expedientes->NumeroExpediente; ?>
                    </a>
                </td>
                <td>
                    <?php echo $v_expedientes->texto; ?>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
<ul class="pagination">
    <?php echo $this->pagination->create_links(); ?>
</ul>
