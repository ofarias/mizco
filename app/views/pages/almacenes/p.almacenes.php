<div class="row">
	<br />
</div>
<div class="row">
	<div class="col-md-6">
		  <div class="form-group">
		    <input type="button" name="p" value="Productos" class="btn-sm btn-info exec">
		    &nbsp;&nbsp;&nbsp;
		    <input type="button" name="c" value="Componentes" class="btn-sm btn-success exec">
		    &nbsp;&nbsp;&nbsp;
		    <input type="button" name="a" value="Almacenes" class="btn-sm btn-warning exec">
		    &nbsp;&nbsp;&nbsp;
		    <input type="button" name="m" value="Movimientos" class="btn-sm btn-primary exec">
		  </div>  
	</div>
</div>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script>

	$(".exec").click(function(){
		var opc = $(this).attr('name')
		alert('Se dio click en el atributo name con el valor:' + opc)
		window.open('index.wms.php?action=wms_menu&opc='+opc, '_self')
	})
  
</script>