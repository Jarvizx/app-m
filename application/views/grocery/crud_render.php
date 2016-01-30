<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
<?php 
foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<script type="text/javascript">
	function inline_edit(value,id,columna)
	{
		// http://www.grocerycrud.com/forums/topic/2179-perform-inline-editing/#entry10008
		var data = 'id='+id+'&'+columna+'='+value;
		var urlUpdate = window.location.pathname+'/update/' + id;
		$.post(urlUpdate, data, function(data){
			$('#crud_page').trigger('change');
		}, 'html');
	}
</script>
<style type='text/css'>
body {
	font-family: Arial;
	font-size: 14px;
}
a {
    color: blue;
    text-decoration: none;
    font-size: 14px;
}
a:hover {
	text-decoration: underline;
}
input.inline_edit {
    font-size: 16px;
    margin: 0.5em;
}
</style>
</head>
<body>
	<div style='height:20px;'></div>  
    <div>
		<?php echo $output; ?>
    </div>	
</body>
</html>
