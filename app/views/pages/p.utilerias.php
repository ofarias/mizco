<br/>

<label>Modulo de Utilerias.</label>
<br/>
<label> Todos los procesos de este modulo estan registrados para su conciliacion con los gerentes y el director, cualquier uso mal intencionado sera sancionado.</label> 
<br/>
<label> Usuario registrado es: <?php echo $usuario;?></label>
<br/>
<label> Fecha y hora del proceso es: <?php echo date("Y-m-d H:i:s");?></label>

<br/>
<form action="index.php" method="post">
<label> Recalcular Precio en Facturas  </label>
<input type="hidden" name="opcion" value="5">
<button name="utilerias" type="submit" value="enviar"> Recalcular Precios en Kits</button>
</form>
<br/>
<form action="index.php" method="post">
<label> Recalcular Costo en Facturas  </label>
<input type="hidden" name="opcion" value="6">
<button name="utilerias" type="submit" value="enviar"> Recalcular Costos en Kits</button>
</form>
<br/>
<form action="index.php" method="post">
<label> Recalcular Inventario y Kardex  </label>
<input type="hidden" name="opcion" value="7">
<button name="utilerias" type="submit" value="enviar"> Recalcular Inventario y Kardex</button>
</form>
<form action="index.php" method="post">
<label> Recalcular Costo Std  </label>
<input type="hidden" name="opcion" value="8">
<button name="utilerias" type="submit" value="enviar"> Recalcular Costo STD</button>
</form>

<form action="index.php" method="post">
	<label> Buscar XML con Addenda  </label>
		<input type="hidden" name="opcion" value="9">
			<button name="utilerias" type="submit" value="enviar"> Acomodar Revisa Addenda </button>
</form>
<br/>
<br/>
<form action="index.php" method="post">
	<label> Relaciona Pagos y Cobros Walmart  </label>
		<input type="hidden" name="opcion" value="10">
			<select name="docf">
				<option>Seleccione un tipo de documento</option>
				<option>Deposito Electronico</option>
			</select>
			<input type="text" name="docp" placeholder="Documento" >
			<button name="utilerias" type="submit" value="enviar"> Revisar Pagos </button>
</form>