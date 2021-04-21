<br />
<div class="row">
	<h3>Ingreso de Productos a la Bodega</h3>
</div>
<br />
<div class="row">
	<div class="col-lg-6">
		<form action="index.php" method="post">
			<input type="text" class="form-control" name="des" placeholder="Descripcion Del Articulo " required="required" /><br />
			<input type="text" class="form-control" name="cant" placeholder="Cantidad" required="required" /><br />
			<input type="text" class="form-control" name="marca" placeholder="Marca" required="required" /><br/>
			<input type="text" class="form-control" name="proveedor" placeholder="Proveedor" /> <br/>
			<input type="number" step="any" class="form-control" name="costo" placeholder="Costo" value = '0'/> <br/>
			<input type="text" name="unidad" class="form-control" placeholder="Unidad de Medida" />
			<button type="submit" class="alert alert-warning" name="IngresarBodega" value = "enviar"> Agregar</button>
		</form>
	</div>
</div>