<div class="row">
	<br />
</div>
<div class="row">
	<div class="col-md-6">
		<form action="index.php" method="post">
		  <div class="form-group">
		    <input type="text" name="ped" class="form-control" id="pedido" placeholder="Numero de Pedido">
		  </div>
		  <button type="submit" id="pedido" class="btn btn-default">Buscar</button>
		  </form>
	</div>
</div>
<br />
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Pedidos
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-usuarios">
                                    <thead>
                                        <tr>
                                        	<th>ID</th>
                                            <th>Pedido</th>
                                            <th>Estatus</th>
                                            <th>Producto</th>
                                            <th>Descripcion</th>
                                            <th>Cantidad Pedido</th>
                                            <th>Cant Ordenada</th>
                                            <th>Cant Recibida</th>
                                            <th>Fecha Rechazo</th>
                                            <th>Motivo Rechazo</th>
                                            <th>Remision</th>
                                            <th>Fecha</th>
                                            <th>Factura</th>
                                            <th>Fecha</th>
                                            <th>Ruta Entrega</th>
                                            <th>Unidad</th>
                                            <th>Fecha Ruta</th>
                                        </tr>
                                    </thead>                                   
                                  <tbody>
                                    	<?php 
                                    	foreach ($exec as $data): 
                                            $color=$data->ORDENADO;
                                            $color2=$data->RECEPCION;
                                            if ($color == '0'){
                                               $color="style='background-color:yellow;'";             
                                            }elseif($color2=='0'){
                                            $color="style='background-color:#FFBF00;'";
                                            } 
                                            $fecha= '';
                                            if($data->STATUS == 'RE'){
                                                $s='Rechazado';
                                                $fecha=$data->FECHA_RECHAZO;
                                                $color= "style='background-color:#9BA9AE;'";
                                            }elseif ($data->STATUS == 'P'){
                                                $s='Pendiente';
                                            }elseif ($data->STATUS == 'N'){
                                                $s='Liberado';
                                                $fecha= $data->FECHA_AUTO;
                                            }elseif($data->STATUS == 'C'){
                                                $s='Cancelado';    
                                            }elseif($data->STATUS == 'B' or $data->STATUS == 'R'){
                                                $s='Preparando';
                                            }else{
                                                $s='Depurado';
                                            }
                                            ?>
                                        <tr class="odd gradeX" <?php echo $color;?>>
                                            <td><?php echo $data->ID;?></td>
                                            <td><?php echo $data->COTIZA;?></td>
                                            <td><?php echo $s;?></td>
                                            <td><?php echo $data->PROD;?></td>
                                            <td><?php echo $data->NOMPROD;?></td>
                                            <td><?php echo $data->CANT_ORIG;?></td>
                                            <td><?php echo $data->ORDENADO;?></td>
                                            <td><?php echo $data->RECEPCION;?></td>
                                            <td><?php echo $fecha;?></td>
                                            <td><?php echo $data->MOTIVO_RECHAZO;?></td>
                                            <td><?php echo $data->REMISION;?></td>
                                            <td><?php echo $data->FECHA_REM;?></td>
                                            <td><?php echo $data->FACTURAS;?></td>
                                            <td><?php echo $data->FECHA_FAC;?></td>        
                                        </tr>
                                        <?php endforeach; ?>
                                 </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
			          </div>
			</div>
		</div>
</div>
<br /><br />
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Movimientos del Pedido.
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-usuarios1">
                                    <thead>
                                        <tr>
                                        	<th>ID</th>
                                            <th>Clave</th>
                                            <th>Descripcion</th>
                                            <th>CANTIDAD Original</th>
                                            <th>Orden de Compra</th>
                                            <th>Fecha OC</th>
                                            <th>Cantidad</th>
                                            <th>Pago</th>
                                            <th>Unidad</th>
                                            <th>Fecha Ruta</th>
                                            <th>Estatus Log</th>
                                            <th>Recepcion</th>
                                            <th>Fecha recepcion</th>
                                            <th>Cantidad</th>
                                            <th>Confirmada</th>
                                        </tr>
                                    </thead>                                   
                                  <tbody>
                                    	<?php
                                  
                                    	foreach ($options as $data): 
                                            $solicitado=$data->CANT_SOLICITADA;
                                            $recibida=$data->CANT_RECIBIDA;

                                            if ($solicitado == $recibida){
                                               $color="style='background-color:#82FA58;'";             
                                            }else{
                                               $color="style='background-color:#FFBF00;'";
                                            }?>
                                        <tr class="odd gradeX"<?php echo $color;?>>
                                            <td><?php echo $data->ID;?></td>
                                            <td><?php echo $data->PROD;?></td>
                                            <td><?php echo $data->NOMPROD;?></td>
                                            <td><?php echo $data->CANT_ORIG;?></td>
                                            <td><?php echo $data->ORDEN_DE_COMPRA;?></td>
                                            <td><?php echo $data->FECHA_OC;?></td>
                                            <td><?php echo $data->CANT_SOLICITADA;?></td>
                                            <td><?php echo $data->TP_TES;?></td>
                                            <td><?php echo $data->UNIDAD;?></td> 
                                            <td><?php echo $data->FECHA_SECUENCIA;?></td>
                                            <td><?php echo $data->STATUS_LOG;?></td> <!-- Estado REC-->        
                                            <td><?php echo $data->RECEPCION;?></td> 
                                            <td><?php echo $data->FECHA_R;?></td>
                                            <td><?php echo $data->CANT_RECIBIDA;?></td> 
                                            <td><?php echo $data->CANT_REC;?></td>
                                            <!--<td><?php echo $data->CANT_ORIGINAL;?></td>--> 
                                        </tr>
                                        <?php endforeach; ?>
                                 </tbody>
                                </table>
                                <div class="row">
                                <div class="col-lg-10"></div>
                                <div class="col-lg-2">
                                    <button onclick="window.print()" >Imprimir! <img src="http://icons.iconarchive.com/icons/iconshow/hardware/64/Printer-icon.png"></button>
                                </div>
                                </div>
                                
                            </div>
                            <!-- /.table-responsive -->
			          </div>
			</div>
		</div>
</div>
