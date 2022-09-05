<?php
	require_once 'app/model/sql.php';
	require_once 'app/classes/PHPExcel.php';	

class intelisis extends sqlbase {

	function ventas($tipo){
		$data = array();
		$this->query="SELECT * FROM VENTA WHERE MOV = 'Pedido' and DATEPART(YEAR,UltimoCambio) = 2019 ";
		$rs=$this->EjecutaQuerySimple();
		while ($tsArray = sqlsrv_fetch_array($rs)){
			$data[]=$tsArray;
		}
	}

	function insertaVentas($file){
		### Leemos el archivo de excel	
		$xls = $this->lee_xls($file);$rengId=0;
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
				$cadena = trim($col['cadena']);
				$alm = ($cadena=='WM')? 'ALM WM':'ALM ML';
				$mov = ($cadena=='WM')? 'Pedido Web':'Pedido Contado';
				//$alm = 'ALM ML';
				$rfc = $col['RFC'];
				$nombre = $col['NOMBRE'];
				$movID = $col['movID'];
				$mp = $col['mp'];
				$tv = $col['tv'];
				$lista = $col['lista'];


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
						if($part==1){
							$this->query="INSERT INTO Venta (EMPRESA, MOV, FECHAEMISION, Moneda, TipoCambio, Usuario, Estatus, Cliente, Almacen, enviarA, FormaPagoTipo, comentarios, ORDENCOMPRA, Agente, Atencion, MovID, Observaciones, Referencia, ListaPreciosEsp) VALUES ('MIZCO', '$mov', '$fecha', 'Pesos', '1', 'ECOMMERCE', 'SINAFECTAR', '$cliente', '$alm', $suc, '$mp', '$obs', '$cadena'+ '$movID', '02', iif( (SELECT top 1 departamento FROM DESACondicionesxDepto WHERE CLIENTE = '$cliente') is null, 'General', (SELECT top 1 DEPARTAMENTO FROM DESACondicionesxDepto WHERE CLIENTE = '$cliente')), '$oc', '$obs', '$tv', '$lista')";
							$this->grabaBD();
							$docs++;
							### Falta actualizar en VentaC que maneja los folios.
							$this->query="UPDATE VENTAC set Consecutivo = Consecutivo + 1 where MOV = '$mov'";
							$this->grabaBD();
						}

						$id = $id * $part;
						$rengId++;
						$this->query="INSERT INTO VENTAD (ID, Renglon, RenglonID, Almacen, Cantidad, Articulo, Precio, Impuesto1, Unidad, DescripcionExtra, CantidadInventario, OrdenCompra )
									VALUES ((SELECT MAX(ID) FROM VENTA), $id, $rengId, '$alm', $cant, '$art', $precio, 16, 'PIEZA', '$cadena'+'$movID', $cant, '$movID')";
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


	function insertaCliente($nombre, $rfc, $uso, $fp, $lista){
		$this->query= "SELECT max(cliente) + 1 as cliente FROM dbo.cte ";
		$res=$this->Ejecutaquerysimple();
		$row=sqlsrv_fetch_array($res);
		$cons=$row['cliente'];
		$this->query= "INSERT INTO dbo.cte (CLIENTE, NOMBRE, RFC, CATEGORIA, TIPO, Condicion, ListaPreciosEsp, DefMoneda, ESTATUS, ULTIMOCAMBIO, ALTA, CREDITOMONEDA, DESAFormaPago, PAIS, AGENTE, CUENTA) values ('$cons', '$nombre', '$rfc', 'Público en General', 'Cliente', 'Contado', '$lista', 'Pesos', 'ALTA', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Pesos', (SELECT formaPago from FormaPago where ClaveSat = '$fp'), 'México', '02', '1150-001-317')";
		$this->grabaBD();
		$this->query="INSERT INTO CTECFD (Cliente, ValidarTipo, AlmacenarTipo, EnviarTipo, Validar, ClaveUsoCFDI) VALUES ('$cons', 'Especifico', 'Especifico', 'Cliente', 0, '$uso')";
		//echo $this->query;
		$this->grabaBD();
		return;
	}

	function metodos($m){
		if($m == '99'){
			$nm = '99 Por definir';
		}elseif($m == '03' or $m == '3'){
			$nm = '03 Transferencia electronica de fondos';
		}elseif($m == '04' or $m == '4'){
			$nm = '04 Tarjeta de Credito';
		}elseif($m == '28'){
			$nm = '28 Tarjeta de Débito';
		}elseif($m == '01' or $m == '1'){
			$nm = '01 Efectivo';
		}else{
			$nm = '01 Efectivo';
		}
		return $nm;
	}

	function lee_xls($file){
		$data= array();
		$usuario = $_SESSION['user']->NOMBRE;
		$lista = '';
		$inputFileType=PHPExcel_IOFactory::identify($file);
        $objReader=PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel=$objReader->load($file);
        $sheet=$objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); 
        $highestColumn = $sheet->getHighestColumn();
        $ruta="C:\\xampp\\htdocs\\remisiones\\";
        if(!file_exists($ruta)){
        	mkdir($ruta, null, true);
        }
        $d=date('s');
        $errors = '';
        $te=0;
        for ($row=2; $row <= $highestRow; $row++){ //10
        	    $col = 'A';
	            $A = date('d/m/Y',PHPExcel_Shared_Date::ExcelToPHP($sheet->getCell($col.$row)->getValue()+1));//FECHA
	            $B = $sheet->getCell(++$col.$row)->getValue();//CLIENTE
	            $C = $sheet->getCell(++$col.$row)->getValue();//SUCURSAL CLIENTE
	            $D = $sheet->getCell(++$col.$row)->getValue();//ORDEN DE COMPRA
	            $E = $sheet->getCell(++$col.$row)->getValue();//ARTICULO
	            $F = $sheet->getCell(++$col.$row)->getValue();//CANTIDAD
	            $G = $sheet->getCell(++$col.$row)->getValue();//PRECIO UNITARIO
	            $H = $sheet->getCell(++$col.$row)->getValue();//OBSERVACIONES
	            $I = $sheet->getCell(++$col.$row)->getValue();//RFC
	            $J = $sheet->getCell(++$col.$row)->getValue();//NOMBRE DEL CLIENTE
	            $K = $sheet->getCell(++$col.$row)->getValue();//MovID
	            $L = $sheet->getCell(++$col.$row)->getValue();//Metodo de Pago
	            $M = $sheet->getCell(++$col.$row)->getValue();//Tipo de Venta Full o Mizco
	            $N = $sheet->getCell(++$col.$row)->getValue();//Cadena
	            $L = $this->metodos($L);
	            $this->query="SELECT TOP 1 * FROM dbo.CTE WHERE cliente = '$B' or rfc = '$I'";
				$res = $this->EjecutaQuerySimple();
				$rowC = sqlsrv_fetch_array($res);
				$lista = $N=='ML'? 'MERC LIBRE':'WALMART.COM';
				if(empty($rowC)){
					$this->insertaCliente($J, $I, $U='',$L, $lista);
				}
	
	            if(strpos(($A.$B.$C.$D.$E.$F.$G.$H.$I),"|")){
	            	$errors .= $row.',';
	            	$te++;
	            }else{
	            	$info[] = $A.'|'.$B.'|'.$C.'|'.$D.'|'.$E.'|'.$F.'|'.$G.'|'.$H.'|'.$I.'|'.$J.'|'.$K.'|'.$N;
            		$info1[] = array("FECHA"=>$A,"CLIENTE"=>$B, "SUCURSAL"=>$C, "OC"=>$D, "ART"=>$E, "CANT"=>$F,"PRECIO"=>$G, "OBS"=>$H, "RFC"=>$I, "NOMBRE"=>$J, "movID"=>$K, "mp"=>$L, "tv"=>$M, "linea"=>$row, "cadena"=>$N, "lista"=>$lista);
	            }
        }
        return array("status"=>'ok', "info"=>$info1, "errors"=>$errors, "te"=>$te);
    }

    function prodInt($t){
    	$data = array();$datos=array();
        $this->query="SELECT * FROM Art where (tipo = 'Lote' or tipo = 'Juego') and Estatus = 'Alta'";
        $res=$this->EjecutaQuerySimple();
        while($tsarray = sqlsrv_fetch_array($res)){
        	$data[]=$tsarray;
        }
        if($t=='x'){
        	$datos=$this->existInt();
        }
        return array("data"=>$data, "datos"=>$datos);
    }

    function existInt(){
    	$data=array();
    	$this->query="SELECT Articulo, ISNULL(RTRIM(LTRIM(Almacen)) + ':1, Existencias: ' + CONVERT(VARCHAR,CONVERT(money,ISNULL(Disponible,0))) + ', Reservado: ' + CONVERT(VARCHAR,CONVERT(money,ISNULL(Reservado,0)))  + ', Remisionado:' + CONVERT(VARCHAR,CONVERT(money,ISNULL((SELECT ISNULL(Remisionado, 0) FROM ArtRemisionado r WHERE r.Empresa = d.Empresa AND r.Almacen = d.Almacen AND r.Articulo = d.Articulo), 0))) + '', '') as disponible
		 FROM ArtDisponibleReservado d
		WHERE ISNULL(Empresa, 'MIZCO') = 'MIZCO'
		ORDER BY Articulo";
    	$res=$this->EjecutaQuerySimple();
    	while($tsarray=sqlsrv_fetch_array($res)){
    		$data[]=$tsarray;
    	}
    	return $data;
    }

    function prodApolo($info){
    	$art= array();
    	foreach($info as $inf){
	    	## Consulta el producto
    		if($inf->PRODUCTO == ''){
	    		$iden = strlen($inf->NO_IDEN)==14? substr($inf->NO_IDEN, 2):$inf->ID_IDEN;
	    		$this->query="SELECT * from listaPreciosD where DESACodigoBarras like '%$iden%' and lista = 'WALMART.COM'";
	    		//$this->query="SELECT top 1 * from listaPreciosD where DESACodigoBarras ='$iden' and lista = 'WALMART.COM'";
	    		$res=$this->EjecutaQuerySimple();
	    		$row = sqlsrv_fetch_array($res);
	    		if($row){
	    			array_push($art , $inf->ID_D, $row['Articulo']);
	    		}
    		}else{
    			array_push($art, $inf->ID_D, $inf->PRODUCTO);
    		}
    		## Consulta el cliente y si no existe lo crea
    		$rfc=$inf->RCF_CLIENTE;
    		$this->query="SELECT * FROM CTE WHERE RFC = '$rfc'";
    		$rs=$this->EjecutaQuerySimple();
    		$rowc=sqlsrv_fetch_array($rs);
    		if(!isset($rowc) ){
        		$this->insertaCliente($inf->NOMBRE, $rfc, $inf->USO_CFDI, $inf->FORMA_PAGO, $lista = 'WALMART.COM');
    		}
    	}
    	return $art;
    }


	function insertaPedidoWeb($info){
		foreach($info as $inf){
			if($inf->PEDIDO != ''){
				return;
			}
			$this->query="SELECT TOP 1 * FROM dbo.CTE WHERE rfc = '$inf->RCF_CLIENTE'";
			$res = $this->EjecutaQuerySimple();
			$row = sqlsrv_fetch_array($res);
			$cliente = $row['Cliente'];
			$this->query="SELECT formaPago as fp FROM FormaPago where claveSat =$inf->FORMA_PAGO";
			$res =$this->EjecutaQuerySimple();
			$rowFP = sqlsrv_fetch_array($res);
			$fp = $rowFP['fp'];
			$uso = 'Uso CFDI: '.$inf->USO_CFDI;
			$obs = $inf->DOCUMENTO;
		}
		$archivo = explode("_",$inf->ARCHIVO);
		$oc = $archivo[3]; 

		### Inserta Cabecera 
			$this->query="INSERT INTO Venta (EMPRESA, MOV, FECHAEMISION, Moneda, TipoCambio, Usuario, Estatus, Cliente, Almacen, enviarA, FormaPagoTipo, comentarios, ORDENCOMPRA, Agente, Atencion, MovID, Observaciones, Referencia, ListaPreciosEsp, condicion) VALUES ('MIZCO', 'Pedido Web', CURRENT_TIMESTAMP, 'Pesos', '1', 'ECOMMERCE', 'SINAFECTAR', '$cliente', 'ALM WM', null, '$fp', '$oc', '$oc', '02', iif( (SELECT top 1 departamento FROM DESACondicionesxDepto WHERE CLIENTE = '$cliente') is null, 'General', (SELECT top 1 DEPARTAMENTO FROM DESACondicionesxDepto WHERE CLIENTE = '$cliente')), (SELECT MAX( CAST(MovID AS bigint))+1 from venta where MOV = 'Pedido Web'), '$obs', '$uso', 'WALMART.COM', 'Contado')";
			$this->grabaBD();

		### Inserta Partidas
		$id= 2048;
		$i=0;
		foreach($info as $infp){
			$i++; $id=$id*$i;$cant = $infp->CANTIDAD; $art=$infp->PRODUCTO; $precio = $infp->UNITARIO; 
			$this->query="INSERT INTO VENTAD (ID, Renglon, Almacen, Cantidad, Articulo, Precio, Impuesto1, Unidad, DescripcionExtra, renglonID, CantidadInventario, OrdenCompra )
									VALUES ((SELECT MAX(ID) FROM VENTA), $id, 'ALM WM', $cant, '$art', $precio, 16, 'PIEZA', '', $i, $cant, '$oc')";
			//echo $this->query;
			$this->grabaBD();
		
		}
		$this->query="SELECT MAX(CAST(MovID AS bigint)) as pedido from venta where MOV = 'Pedido Web'";
		$res=$this->EjecutaQuerySimple();
		$row=sqlsrv_fetch_array($res);
		return array("status"=>'ok', "pedido"=>$row['pedido']);
	}

	function obtieneUUID($info){
		foreach($info as $inf){
			$pedido = $inf->PEDIDO;
			$oc = $inf->OC;
		}
		$this->query="SELECT DESACFDIUUID, MOVID, Cliente, Ejercicio, Periodo from venta where OrdenCompra = '$oc' and OrigenTipo ='VTAS' and Origen = 'Pedido Web' and OrigenID = $pedido and estatus = 'CONCLUIDO' and CFDFlexEstatus = 'CONCLUIDO' and CFDTimbrado = 1 and DESACFDIUUID != ''";
		//echo $this->query;
		$res=$this->Ejecutaquerysimple();
		$row = sqlsrv_fetch_array($res);
		if(isset($row)){
			return array("uuid"=>$row['DESACFDIUUID'], "factura"=>$row['MOVID'], 'eje'=>$row['Ejercicio'], 'per'=>$row['Periodo'], 'cliente'=>$row['Cliente']);
		}
		return array();
	}

	function detalleDoc($docp, $docf){
		// docp es el MovId
		// docf es el Movimiento
		$data = array(); $dataf=array(); 
		$this->query="select * from dinero where mov = '$docf' and MovId = $docp";
		$res=$this->EjecutaQuerySimple();
		$row=sqlsrv_fetch_array($res);
		//print_r($row);
		///echo $row['ID'];
		$this->query="	SELECT  c.id, 
								c.Aplica, 
								c.AplicaID, 
								(SELECT PrecioTotal + IMPUESTOS FROM Venta where movID = c.aplicaID) as TotalFact,
								c.Importe,
								(SELECT SUM(ca.IMPORTE) FROM CXCD ca  
									LEFT JOIN CXC cd on cd.ID = ca.id
									WHERE ca.AplicaID = c.AplicaID and ca.id != c.id and cd.Estatus = 'CONCLUIDO') as aplicado
								from CxcD c
								where c.id = (select ID from Cxc where Dinero = '$docf' and DineroId = $docp)
							";
		$res=$this->EjecutaQuerySimple();
		while($tsarray=sqlsrv_fetch_array($res)){
			$data[]=$tsarray;
		}
		echo 'Se contaron '.count($data).' aplicaciones';
		///print_r($data);
		//echo $this->query;
		$facturas = '';
		foreach($data as $k){
			echo '<br/><br/> Por un monto del doc '.$k['TotalFact'];
			$pago=0; $total = intval($k['TotalFact']);
			$fact = $k['AplicaID'];
			echo '<br/><b>'.$k['AplicaID']. ' $ '.$k['TotalFact'].'</b>';
			$facturas .= ','.substr($k['AplicaID'],2);
			$this->query="SELECT cd.*, c.estatus, c.mov, c.movID FROM CXCD cd left join CXC c on c.id = cd.id WHERE cd.AplicaID = '$fact'"; //and c.estatus = 'CONCLUIDO'";
			$res=$this->Ejecutaquerysimple();
			while($tsarray=sqlsrv_fetch_array($res)){
				$dataf[]=$tsarray;
			}
			if(count($dataf) >0){
				foreach($dataf as $appf){
					$mov = $appf['mov'];
					$movID = $appf['movID'];
					$status = $appf['estatus'];
					
					$idcxc = $appf['ID'];
					$aplicaID = $appf['AplicaID'];
					$this->query="SELECT c.mov+c.movid as DocuCxC, 
										(SELECT SUM(IMPORTE) FROM CXCD cd WHERE cd.id = c.id and cd.AplicaID = '$aplicaID') as MontoA,
										(select  UUID 
												from CFDICobroParcialTimbrado
												where movimiento= '$mov'
													  and movID = '$movID'
												group by MovID, rfc, UUID, Movimiento	
												) as UUID
										FROM CXC c  
										WHERE c.ID = $idcxc 
											and estatus = '$status'";
					$res=$this->Ejecutaquerysimple();
					$row=sqlsrv_fetch_array($res);
					$uuid = isset($row['uuid'])? ' uuid: '.$row['uuid']:'';
					if(trim($status)== 'CONCLUIDO'){
						$pago = $pago + $appf['Importe'];
						echo '<br/><font color = "blue">'.$status.' Los cobros concluidos son: '.$row['DocuCxC'].' $ '.$row['MontoA'].$uuid.'</font>';
					}else{
						echo '<br/>'.$status.'Los cobros Cancelados son: '.$row['DocuCxC'].' $ '.$row['MontoA'].$uuid ;
					}
				}

				echo '<br/> <b>Total Pagado '.number_format($pago,2).'</b>';
				$resultado = intval($total) - intval($pago);
				if($resultado <-1 ){
					$color='Red';
				}elseif($resultado == 0){
					$color='green';
				}else{
					$color='blue';
				}
				echo "<br/> <font color ='$color'> Resultado:  $ ". number_format($resultado,2). "</font><br/>";
				echo $facturas;
				unset($dataf);	
			}
			
		}
	}

	function revProd($partidas){
		$data= array();
		foreach($partidas as $part){
			$iden = strlen($part->NO_IDENTIFICACION)==14? substr($part->NO_IDENTIFICACION, 2):$part->ID_IDENTIFICACION;
			//echo '<br/> Busca el Iden '. $iden. ' linea '.$part->ID_AD;
			//$this->query="SELECT TOP 1 * FROM listaPreciosD where DESACodigoBarras like '%$iden%' and lista = 'WALMART.COM'";
	    	$this->query="SELECT top 1 * from listaPreciosD where DESACodigoBarras = '$iden' and lista = 'WALMART.COM'";
			$res=$this->EjecutaQuerySimple();
			while($tsArray=sqlsrv_fetch_array($res)){
				$data[]=$tsArray;
			}
		}
		if(count($data)>0 ){
			foreach($data as $d ){
				echo '<br/>Se encontro el '.$d['DESACodigoBarras']. ' con el valor '.$d['Articulo'].' precio intelisis '.$d['Precio'];
			}
		}
		//die();
	}

	function documentos($op0, $op1, $ini, $fin){
		$data = array(); $hoy=date("d.m.Y");
		$ini = empty($ini)?  date("d.m.Y", strtotime($hoy."- 1 days")):date("d.m.Y", strtotime($ini));
		$fin = empty($fin)?  date("d.m.Y"):date("d.m.Y", strtotime($fin));
		$op1 = isset($op1)?  'Pedido':$op1; 
		if($op0 == '0'){
			$this->query="SELECT mov, count(*) as cant FROM VENTA  where fechaemision between '$ini' and '$fin' and (mov = 'Pedido' or Mov = 'Factura Electronica') and estatus = 'PENDIENTE' GROUP BY MOV";
		}else{
			$this->query="SELECT (SELECT c.NOMBRE FROM cte c where c.cliente = v.cliente) as nombre,  
								 (SELECT s.nombre from cteEnviarA s where s.cliente = v.cliente and s.id = v.enviarA ) as Sucursal,
								 v.* 
						  FROM VENTA v WHERE 
							v.FECHAEMISION between '$ini' and '$fin' 
							and v.estatus ='PENDIENTE'
							and v.mov = '$op1'
							and v.Almacen = 'AL PT'";	
		}
		//echo '<br/>'.$this->query;
		$res=$this->Ejecutaquerysimple();	
			while($tsarray=sqlsrv_fetch_array($res)){
				$data[]=$tsarray;
			}
		echo "<br/><b>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Se muestra información del ".$ini.' al '.$fin.'</b>';
		return $data;
	}

	function detDoc($doc){
		$data=array();
		$this->query="SELECT (SELECT a.DESCRIPCION1 FROM art a where a.Articulo = d.Articulo) as descr, 
							(SELECT l.DESACodigoBarras from ListaPreciosD l where lista = v.ListaPreciosEsp and d.Articulo = l.articulo and l.Moneda = 'Pesos') as upc , 
							d.* 
							FROM VENTAD d
								LEFT JOIN VENTA v on v.id = d.id 
							WHERE d.ID = $doc";
		$res=$this->Ejecutaquerysimple();
		while($tsArray=sqlsrv_fetch_array($res)){
			$data[]=$tsArray;
		}
		return $data;
	}

	function sincPres($prod){
		$data = array();
		$this->query="SELECT p.articulo as presentacion , p.presentacion as articulo, (SELECT a.Descripcion1 FROM ART a WHERE p.Presentacion = a.articulo) as descripcion1 from artPresenta p where presentacion = '$prod'";
		$res=$this->EjecutaQuerySimple();
		while ($tsArray=sqlsrv_fetch_array($res)) {
			$data[]=$tsArray;
		}
		return array("status"=>'ok', "datos"=>$data);
	}

	function infoInt($doc){
		$cabecera=array(); $detalle=array();
		if($doc < 187033){
			$tabla =' INV '; $tablap = ' INVD ';
		}else{
			$tabla =' VENTA '; $tablap = ' VENTAD ';
		}
		
		$this->query="SELECT * FROM $tabla WHERE ID = $doc";
		$res=$this->Ejecutaquerysimple();
		while($tsArray=sqlsrv_fetch_array($res)){
			$cabecera[]=$tsArray;
		}
		$this->query="SELECT * FROM $tablap WHERE ID = $doc";
		$res=$this->Ejecutaquerysimple();
		while($tsArray=sqlsrv_fetch_array($res)){
			$partidas[]=$tsArray;
		}
		//echo 'Cabecera: '.count($cabecera).' partidas '.count($partidas); 
		return array("cabecera"=>$cabecera, "partidas"=>$partidas);
	}



	function asgLn($info){
		$data=array();$asig=0;$base=''; $i=0;
		foreach ($info as $inf){
			$id = $inf->ID_ORD; 
			if($i==0){
				$base= $inf->RENGLON;
			}
			$renglon=$inf->RENGLON;  ///1 
			if($renglon == $base){ /// SIEMPRE ES IGUAL LA PRIMERA VES aqui base no esta definida
				$base = $inf->RENGLON;   /// ESTABLECEMOS LA BASE 1
				$asig+=$inf->CANT; 	/// SUMAMOS 0  + CANTIDAD INICIAL.
			}else{
				$asig=$inf->CANT;
			}
				$this->query="SELECT * FROM VENTAD WHERE ID = $id and renglon =$renglon";
				//$res=$this->Ejecutaquerysimple();
				while($tsArray=sqlsrv_fetch_array($res)){
					$data[]=$tsArray;
				}
				if(count($data)>0){
					foreach($data as $d){
						$this->query="UPDATE VENTAD set UltimoReservadoCantidad=($asig/factor), CantidadReservada= ($asig/factor) where ID = $id and renglon = $renglon";
						echo '<br/>Consulta: '.$this->query;
						//$this->Ejecutaquerysimple();
					}
					unset($data);
				}
			$i++;
		}
	}

	function sincInt($info){
		$data=array();
		foreach ($info as $inf) {
			$idOrd = $inf->ID_ORD; $renglon=$inf->PARTIDA;
			$this->query="SELECT * FROM VENTAD WHERE ID = $idOrd and renglon = $renglon";
			$res=$this->Ejecutaquerysimple();
			while($tsarray=sqlsrv_fetch_array($res)){
				$data[]=$tsarray;
			}
			break;
		}
		return $data;
	}

	function sincIntWms($info){
		$data=array();$cant= 0;
		foreach ($info as $i) {
			$cant += $i->CANT;
			//echo '<br/> Base: '.$i->BASE.' Nuevo: '.$i->NUEVO;
			if($i->BASE != $i->NUEVO){
				$this->iCambioPres($i->BASE , $i->NUEVO, $i->CANT, $i->PARTIDA, $i->ID_ORD, $i->MOVID);
			}
			$this->query="UPDATE VENTAD SET CantidadA = $cant/FACTOR where id = $i->ID_ORD AND Renglon = $i->PARTIDA";
			$this->EjecutaQuerySimple();
		}
		return array("status"=>'ok', "msg"=>'Debera terminar el proceso de "Afectar" en Intelisis para concluir');
	}

	function iCambioPres($base, $nuevo, $cant, $part, $idord, $movID){
		//// Buscamos si hay un documento abierto, si existe introducimos las partidas en el, si no existe, creamos uno nuevo.
			$id = $this->findCambio('Cambio Presentacion');
			$this->query="INSERT INTO INVD (ID, RENGLON, RENGLONSUB, RenglonID, RenglonTipo, CANTIDAD, ALMACEN, ARTICULO, ArticuloDestino, FechaRequerida, Unidad, Factor, CantidadInventario, Sucursal, SucursalOrigen, DescripcionExtra) 
				VALUES ( $id,
					(SELECT COALESCE (MAX(Renglon), 0) + 2048 FROM INVD WHERE ID = $id),
					0,
					'L',
					(SELECT COALESCE (MAX(RenglonID),0) + 1 FROM INVD WHERE ID = $id),
					$cant,
					'AL PT',
					'$nuevo',
					'$base',  CURRENT_TIMESTAMP,
					'PIEZA', 1,
					$cant,
					0,
					0,
					'$movID --> $idord --> $part')";
			echo '<br/>Detalle: '.$this->query.'<br/>';
			$this->Ejecutaquerysimple();
	}

	function findCambio($tipo){
		$data=array();
		$this->query ="SELECT coalesce(max(id),0) as id FROM INV WHERE MOV = '$tipo' and Estatus = 'SINAFECTAR'";
		$res=$this->Ejecutaquerysimple();
		while($tsArray = sqlsrv_fetch_array($res)){
			$data[]=$tsArray;
		}
		$id = $data[0]['id'] > 0?  $data[0]['id']:$this->creaFolio($tipo);
		return $id;
	}

	function creaFolio($tipo){
			//echo '<br/> No existe el documento entonces creamos el registro';
			$this->query="SET IDENTITY_INSERT INV ON ";
			$this->query .="INSERT INTO inv (ID, Empresa, Mov, MovID, FechaEmision, UltimoCambio, Moneda, TipoCambio, Usuario, Estatus, Directo, RenglonID, Almacen,
				AlmacenTransito, Largo, FechaRequerida, Vencimiento, GenerarPoliza, Ejercicio, Periodo, FechaRegistro, FechaConclusion, Peso, 
				Sucursal, SucursalOrigen, SubModulo) 
				output inserted.ID 
				values (
				(select max(id)+1 from inv),
				 'MIZCO', 
				 '$tipo', 
				 (SELECT Consecutivo + 1 FROM InvC where mov = '$tipo'),
				CURRENT_TIMESTAMP, 
				CURRENT_TIMESTAMP, 
				'Pesos', 
				1, 
				'PHP', 
				'SINAFECTAR', 1, 0, 'AL PT',
				'(TRANSITO)', 0, CURRENT_TIMESTAMP , CURRENT_TIMESTAMP, 0,  YEAR(GETDATE()),  MONTH(GETDATE()), CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 0
				, 0, 0, 'INV' ) ";
			$this->query .="SET IDENTITY_INSERT INV OFF";
			//echo '<br/>Cabecera: '.$this->query.'<br/>';
			$this->Ejecutaquerysimple();
			
			$this->query="UPDATE InvC SET Consecutivo = Consecutivo + 1 where mov = '$tipo'";
			//echo '<br/>Folio: '.$this->query.'<br/>';
			$this->Ejecutaquerysimple();
			return $this->findCambio($tipo);
	}

	function insertaMovInv($info){
		$id = $this->findCambio($tipo = 'Salida Diversa');
		for ($i=0; $i < count($info) ; $i++){ 
            $cant=$info[$i]['PIEZAS']; $alm='AL PT';$art=$info[$i]['SKU']; $uni = 'PIEZA'; $factor = 1; $suc = 0; $obs=$info[$i]['OBS']; $guia=$info[$i]['GUIA'];$edo_fis=$info[$i]['ESTADO'];$motivo=$info[$i]['MOTIVO']; $solicitante=$info[$i]['SOLICITUD'];
            $this->query="INSERT INTO INVD (ID, RENGLON, RENGLONSUB, RenglonID, RenglonTipo, CANTIDAD, ALMACEN, ARTICULO, ArticuloDestino, FechaRequerida, Unidad, Factor, CantidadInventario, Sucursal, SucursalOrigen, DescripcionExtra) 
				VALUES ( $id,
					(SELECT COALESCE (MAX(Renglon), 0) + 2048 FROM INVD WHERE ID = $id),
					0,
					(SELECT COALESCE (MAX(RenglonID),0) + 1 FROM INVD WHERE ID = $id),
					'L',
					$cant,
					'$alm',
					'$art',
					'',  CURRENT_TIMESTAMP,
					'$uni', 
					$factor,
					$cant,
					$suc,
					0,
					'$obs --> $guia --> $motivo --> $solicitante')";
			//echo '<br/>Detalle: '.$this->query.'<br/>';
			$this->Ejecutaquerysimple();
        }	
        $this->query ="SELECT MOVID FROM INV WHERE ID = $id ";
        $res=$this->Ejecutaquerysimple();
        $movid = sqlsrv_fetch_array($res);
        $movid = $movid[0];
		return array("movid"=>$movid, "idint"=>$id, "docs"=> 1, "errors"=>0);
	}


	function valInt($regWms){
			### stdClass Object ( [ID_INT_FP] => 10281 [ID] => 401 [RENGLON] => 2048 [RENGLONSUB] => 0 [RENGLONID] => 1 [RENGLONTIPO] => L [ALMACEN] => AL PT [CANTIDAD] => 12 [ARTICULO] => BT1400 [PRECIO] => 310 [IMPUESTO1] => 1.16 [UNIDAD] => 4 [DESCRIPCIONEXTRA] => [CANTIDADINVENTARIO] => 48 [ORDENCOMPRA] => 9950821199 ) 
	### La validacion se hace por lista de precios, almacen y presentacion por el momento solo se hace por articulo. 
		$valPart=array();$valCab=array();
		foreach($regWms['partidas'] as $par){
			$art= $par->COMPRADOR; $lista = $par->LISTA; $id=$par->ID_INT_FP;
			//$this->query = "SELECT count(*) as valArt FROM ListaPreciosD WHERE CodigoCliente = '$art' and Lista = '$lista'";
			$this->query = "SELECT count(*) as valArt FROM ListaPreciosD WHERE CodigoCliente = '$art'";
			$res = $this->EjecutaQuerySimple();
			$row=sqlsrv_fetch_array($res);
			//echo '<br/> Revisa el Articulo: '.$art. ' con el resultado '.$row['valArt'];
			//$this->query="SELECT * FROM ART WHERE "
			$valPart[] = array("id"=>$id, "art"=>$art, "val"=>$row['valArt']);;
		}
		
		foreach($regWms['cabecera'] as $cbc){
			$enviarA = $cbc->ENVIARA;
			$valCab[]=array("id"=>$cbc->ID_INT_F, "suc"=>$enviarA, "val"=>1);
		}
		return array("valPart"=>$valPart, "valCab"=>$valCab);
	}

	function insertaVtaInt($info){
		$mov='Pedido';$docs=0;
		foreach ($info['cabeceras'] as $c){
			//echo '<br/> se intenta insertar el ID_INT_F: '.$c->ID_INT_F;
			$empresa =$c->EMPRESA; $mov=$c->MOV; $moneda=$c->MONEDA; $tc = $c->TIPOCAMBIO; $usuario=$c->USUARIO; $estatus=$c->ESTATUS;$cliente=$c->CLIENTE;$almacen=$c->ALMACEN;$enviarA=$c->ENVIARA;$formaPago=$c->FORMAPAGOTIPO; $comentarios=$c->COMENTARIOS;$oc=$c->ORDENCOMPRA;$agente=$c->AGENTE;$atencion=$c->ATENCION;$obs=$c->OBSERVACIONES;$depto=$c->DEPTO;$lista='WAL-MART SC'; $cadena=$c->COMPRADOR;
				//$lista=$c->LISTAPRECIOSESP;
			$this->query="INSERT INTO Venta (EMPRESA, MOV, FECHAEMISION, Moneda, TipoCambio, Usuario, Estatus, Cliente, Almacen, enviarA, FormaPagoTipo, comentarios, ORDENCOMPRA, Agente, Atencion, MovID, Observaciones, Referencia, ListaPreciosEsp) VALUES ('$empresa', '$mov',  GETDATE(), '$moneda', '$tc', '$usuario', '$estatus', '$cliente', '$almacen',
				(SELECT ID FROM CteEnviarA where cliente='$cliente' and cadena = '$cadena'),
				(SELECT Condicion FROM CteEnviarA where cliente='$cliente' and cadena = '$cadena'),
				'$obs',
				'$oc',
				'01', 
				iif( (SELECT top 1 departamento FROM DESACondicionesxDepto WHERE CLIENTE = '$cliente' and Departamento = '$depto') is null, 'General', (SELECT top 1 DEPARTAMENTO FROM DESACondicionesxDepto WHERE CLIENTE = '$cliente' and Departamento = '$depto')), 
				(SELECT Consecutivo + 1  FROM VENTAC WHERE MOV = '$mov'), 
				'$obs', 
				'$oc', 
				'$lista'
				)";
			echo '<br/>Cabecera: '.$this->query.'<br/>';
			$this->grabaBD();
			$docs++;
			$this->query="UPDATE VENTAC set Consecutivo = Consecutivo + 1 where MOV = '$mov'";
			$this->grabaBD();
			$i=0;
			foreach ($info['partidas'] as $p){
				if($p->ID = $c->ID_INT_F){
					$i++;
					$this->query="INSERT INTO VENTAD (ID, Renglon, RenglonID, RenglonTipo, Almacen, Cantidad, Articulo, Precio, Impuesto1, Unidad, DescripcionExtra, CantidadInventario, OrdenCompra )
									VALUES ((SELECT MAX(ID) FROM VENTA), $p->RENGLON, $p->RENGLONID, '$p->RENGLONTIPO', '$almacen', $p->CANTIDAD, '$p->ARTICULO', $p->PRECIO, 16, '$p->UNIDAD', '$p->ORDENCOMPRA', $p->CANTIDADINVENTARIO, '$p->ORDENCOMPRA')";
					echo '<br/> Partidas: '.$this->query.'<br/>';
					$this->grabaBD();
				}
			}
		}
		return array("docs"=>count($info['cabeceras']));
	}
}