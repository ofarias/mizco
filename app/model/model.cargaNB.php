<?php 

	function insertaVentas($file){
		### Leemos el archivo de excel
		$xls = $this->lee_xls($file);
		if($xls['status']=='ok'){
			$part = 0;$ocBase=''; $docs=0; $errors=0;
			foreach ($xls['info'] as $col){
				$id= 2048;
				$cliente = $col['CLIENTE'];
				$art = $col['ART'];
				$obs = $col['OBS'];
				$oc = $col['OC'];
				$precio = $col['PRECIO'];
				$cant = $col['CANT'];
				$fecha = $col['FECHA'];
				$suc = $col['SUCURSAL'];
				$alm = 'AL PT';///ALM ML 
				$rfc = $col['RFC'];
				$nombre = $col['NOMBRE'];
				$movID = $col['movID'];
				$mp = $col['mp'];
				$tv = $col['tv'];

				$this->query="SELECT TOP 1 * FROM dbo.CTE WHERE cliente = '$cliente' or rfc = '$rfc'";
				$res = $this->EjecutaQuerySimple();
				$row = sqlsrv_fetch_array($res);

				if($row){
					if(empty($cliente)){
						$cliente = $row['Cliente'];
					}
					$this->query="SELECT * FROM ART WHERE ARTICULO = '$art'";
					$r=$this->EjecutaQuerySimple();
					if($rowArt = sqlsrv_fetch_array($r)){ ### Existe el articulo, entonces insertamos la remision.
						if($oc == $ocBase){
							$part++;
						}else{
							$part =1;
						}

						/// Pedido Contado , ECOMMERCE , 'ML'+ '$movID' , 02, MERC LIBRE
						/// Boni. Electronica , EGONZALEZ , '$movID', '01', OFFICE RS
						if($part==1){
							$this->query="INSERT INTO Venta (EMPRESA, MOV, FECHAEMISION, Moneda, TipoCambio, Usuario, Estatus, Cliente, Almacen, enviarA, FormaPagoTipo, comentarios, ORDENCOMPRA, Agente, Atencion, MovID, Observaciones, Referencia, ListaPreciosEsp, CAUSA, CLASE) VALUES ('MIZCO', 'Boni. Electronica', '$fecha', 'Pesos', '1', 'EGONZALEZ', 'SINAFECTAR', '$cliente', '$alm', $suc, '$mp', '$obs', '$movID', '01', iif( (SELECT top 1 departamento FROM DESACondicionesxDepto WHERE CLIENTE = '$cliente') is null, 'General', (SELECT top 1 DEPARTAMENTO FROM DESACondicionesxDepto WHERE CLIENTE = '$cliente')), '$oc', '$obs', '$tv', 'OFFICE RS', 'Devoluciones, Descuentos y Bonificaciones','Tipo Relacion 03' )";
							$this->grabaBD();
							$docs++;
						}
						$id = $id * $part;
						$ocpar = substr($movID, 0, 19);
						$this->query="INSERT INTO VENTAD (ID, Renglon, Almacen, Cantidad, Articulo, Precio, Impuesto1, Unidad, DescripcionExtra, renglonID, CantidadInventario, OrdenCompra )
									VALUES ((SELECT MAX(ID) FROM VENTA), $id, 'AL PT', $cant, '$art', $precio, 16, 'PIEZA', '$movID', 0, $cant, '$ocpar')";
						$this->grabaBD();
						$ocBase= $oc;
					}else{
						echo 'No Existe el Articulo: '.$art.' Linea Excel: '.$col['linea'].' Factura: '.$oc.'<br/>';
						$errors++;
					}
				}else{
					echo 'No Existe el cliente: '.$cliente.'<br/>';
					$errors++;
				}
			}
		}
		return array("docs"=>$docs,"errors"=>$errors);
	}