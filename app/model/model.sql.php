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
				$alm = 'AL ECM';
				$this->query="SELECT * FROM CTE WHERE cliente = '$cliente'";
				$res = $this->EjecutaQuerySimple();
				$row = sqlsrv_fetch_array($res);
				if($row){
					$this->query="SELECT * FROM ART WHERE ARTICULO = '$art'";
					$r=$this->EjecutaQuerySimple();
					if($rowArt = sqlsrv_fetch_array($r)){ ### Existe el articulo, entonces insertamos la remision.
						if($oc == $ocBase){
							$part++;
						}else{
							$part =1;
						}
						if($part==1){
							$this->query="INSERT INTO Venta (EMPRESA, MOV, FECHAEMISION, Moneda, TipoCambio, Usuario, Estatus, Cliente, Almacen, enviarA, FormaPagoTipo, comentarios, ORDENCOMPRA, Agente, Atencion) VALUES ('MIZCO', 'Remision', '$fecha', 'Pesos', '1', 'ECOMMERCE', 'SINAFECTAR', '$cliente', '$alm', $suc, '99 Por Definir', '$obs', '$oc', '02', iif( (SELECT top 1 departamento FROM DESACondicionesxDepto WHERE CLIENTE = '$cliente') is null, 'General', (SELECT top 1 DEPARTAMENTO FROM DESACondicionesxDepto WHERE CLIENTE = '$cliente')))";
							$this->grabaBD();
							$docs++;
						}
						$id = $id * $part;
						$this->query="INSERT INTO VENTAD (ID, Renglon, Almacen, Cantidad, Articulo, Precio, Impuesto1, Unidad, DescripcionExtra, renglonID, CantidadInventario )
									VALUES ((SELECT MAX(ID) FROM VENTA), $id, 'AL PT', $cant, '$art', $precio, 16, 'PIEZA', '$obs', 0, $cant)";
						$this->grabaBD();
						$ocBase= $oc;
					}else{
						echo 'No Existe el Articulo: '.$art.'<br/>';
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

	function lee_xls($file){
		$data= array();
		$usuario = $_SESSION['user']->NOMBRE;

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

	            if(strpos(($A.$B.$C.$D.$E.$F.$G.$H),"|")){
	            	$errors .= $row.',';
	            	$te++;
	            }else{
            		$info[] = $A.'|'.$B.'|'.$C.'|'.$D.'|'.$E.'|'.$F.'|'.$G;
            		$info1[] = array("FECHA"=>$A,"CLIENTE"=>$B, "SUCURSAL"=>$C, "OC"=>$D, "ART"=>$E, "CANT"=>$F,"PRECIO"=>$G, "OBS"=>$H);
	            }
        	//echo 'Fecha:'.$A.' Cliente: '.$B.' Sucursal: '.$C.' Pedido: '.$D.' Obs: '.$H.'<br/>';
        }
        return array("status"=>'ok', "info"=>$info1, "errors"=>$errors, "te"=>$te);
    }

    function prodInt(){
    	$data = array();
        $this->query="SELECT * FROM Art where tipo = 'Lote' and (Estatus = 'Alta' or Estatus = 'Alta')";
        $res=$this->EjecutaQuerySimple();
        while($tsarray = sqlsrv_fetch_array($res)){
        	$data[]=$tsarray;
        }
        //$datos=$this->datosArticulo();
        return $data;
    }

    function datosArticulo(){
    	$data=array();
    	echo 'Entra al SP';
    	$this->query="EXEC MIZCOInformacionIntelisis";
    	$res=$this->EjecutaQuerySimple();
    	while($tsarray=ibase_fetch_object($res)){
    		$data[]=$tsarray;
    	}
    	print_r($data);
    	die();
    }
}