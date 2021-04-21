<br/>
<br/>
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Detalla de Recepcion .
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-aplicapago">
                                    <thead>
                                        <tr>
                                            <th>No Documento</th>
                                            <th>Proveedor</th>
                                            <th>Fecha</th>
                                            <th>Importe</th>
                                            <th>Moneda</th>
                                            <th>Tipo de Cambio</th>
                                            
                                            <th>Documento Anterior</th>
                                           
                                            <th>Costear</th>
                                        </tr>
                                    </thead>                                   
                                  <tbody>
                                        <?php 
                                        foreach ($compras as $data): 
                                        ?>
                                        <tr>
                                            <td><?php echo $data->CVE_DOC;?></td>
                                            <td><?php echo $data->NOMBRE;?></td>
                                            <td><?php echo $data->FECHAELAB;?></td>
                                            <td align="right"><?php echo '$ '.number_format($data->IMPORTE,2);?></td>
                                            <td><?php echo $data->NUM_MONED;?></td>
                                            <td><?php echo $data->TIPCAMB;?></td>
                                            <td><?php echo $data->DOC_ANT;?></td>
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
<br />
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Partidas de la Recepcion .
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-aplicapago">
                                    <thead>
                                        <tr>
                                            <th>Partida</th>
                                            <th>Clave</th>
                                            <th>Descripcion</th>
                                            <th>Precio</th>
                                            <th>IVA</th>
                                            <th>Descuento</th> 
                                            <th>Unidad Entrada</th>
                                            <th>Almacen</th>
                                            <th>Cantidad</th>
                                            <th>Flete </th>
                                            <th>Agente Aduanal</th>
                                            <th>Seguro</th>
                                            <th>Impuestos</th>
                                            <th>TC</th>
                                            <th>Total Costo</th>
                                            <th>Guardar</th>
                                        </tr>
                                    </thead>                                   
                                  <tbody>
                                        <?php 
                                        foreach ($partidasas as $data): 
                                        ?>
                                        <tr>
                                            <td><?php echo $data->NUM_PAR;?></td>
                                            <td><?php echo $data->CVE_ART;?></td>
                                            <td><?php echo $data->DESCR;?></td>
                                            <td align="right"><?php echo '$ '.number_format($data->COST,2);?></td>
                                            <td><?php echo $data->TOTIMPU4;?></td>
                                            <td><?php echo $data->DESCU;?></td>
                                            <td><?php echo $data->UNI_VENTA;?></td>
                                            <td><?php echo $data->NUM_ALM;?></td>
                                            <td><?php echo $data->CANT;?></td>
                                            <td>
                                                <input type="number" step="any" name="flete" required="required" placeholder="Monto Flete">
                                            </td>
                                            <td>
                                                <input type="number" step="any" name="aa" required="required" placeholder="Agente Aduanal">
                                            </td>
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