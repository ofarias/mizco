

<br /><br />
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                          Inventatio patio.
                        </div>
                        <!-- /.panel-heading -->
                           <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover" id="dataTables-oc1">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>FECHA PEDIDO</th>
                                            <th>PEDIDO</th>
                                            <th>PARTIDA</th>
                                            <th>CLAVE</th>
                                            <th>NOMBRE</th>
                                            <th>UM</th>
                                            <th>CANTIDAD PEDIDO</th>
                                            <th>RECEPCION</th>
                                            <th>EMPACADO</th>
                                            <th>FACTURADO</th>
                                            <th>REMISIONADO</th>
                                            <th>FACTURAS</th>
                                            <th>FECHA</th>
                                            <th>REMISIONES</th>
                                            <th>FECHA</th>
                                            <th>STATUS LOGISTICA</th>
                                            <th>EN PATIO</th>
                                            <th>PRESUPUESTO COMPRA</th>
                                            <th>COSTO REAL</th>
                                            <th>DIFERENCIA</th>
                                            <th>PRESUPUESTO VENTA</th>
                                        </tr>
                                    </thead>
                                  <tbody>
                                        <?php
                                        foreach ($invempaque as $data): 
                                        ?>
                                       <tr>
                                            <td><?php echo $data->ID;?></td>
                                            <td><?php echo $data->FECHASOL;?></td>
                                            <td><?php echo $data->COTIZA;?></a></td>
                                            <td><?php echo $data->PAR;?></td>
                                            <td><?php echo $data->PROD;?></td>
                                            <td><?php echo $data->NOMPROD;?></td>
                                            <td><?php echo $data->UM;?></td>
                                            <td><?php echo $data->CANT_ORIG;?></td>
                                            <td><?php echo $data->RECEPCION;?></td>
                                            <td><?php echo $data->EMPACADO;?></td>
                                            <td><?php echo $data->FACTURADO;?></td>
                                            <td><?php echo $data->REMISIONADO;?></td>
                                            <td><?php echo $data->FACTURAS;?></td>
                                            <td><?php echo $data->FFAC;?></td>
                                            <td><?php echo $data->REMISIONES;?></td>
                                            <td><?php echo $data->FREM;?></td>
                                            <td><?php echo $data->STATUS_LOG;?></td>
                                            <td><?php echo $data->BODEGA;?></td>
                                            <td><?php echo $data->PPTO_COMPRA;?></td>
                                            <td><?php echo $data->COSTO_REAL?></td>
                                            <td><?php echo $data->DIFERENCIA?></td>
                                            <td><?php echo $data->PPTO_VENTA?></td>
                                            </tr>
                                            
                                        <?php endforeach; ?>
                                 </tbody>
                                 </table>
                            <!-- /.table-responsive -->
                      </div>
                </div>
        </div>
    </div>
</div>
