<br/>
<br/>
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Lista de Solicitudes pendientes de Impresión.
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
                                        foreach ($ocfinalizadas as $data): 
                                            ?>
                                        <tr>
                                            <td><?php echo $data->CVE_DOC;?></td>
                                            <td><?php echo $data->NOMBRE;?></td>
                                            <td><?php echo $data->FECHAELAB;?></td>
                                            <td align="right"><?php echo '$ '.number_format($data->IMPORTE,2);?></td>
                                            <td><?php echo $data->NUM_MONED;?></td>
                                            <td><?php echo $data->TIPCAMB;?></td>
                                            <td><?php echo $data->DOC_ANT;?></td>
                                            <form action="index.php" method="post">
                                            <td>
                                                <button name="liberarRecepcion" value = "enviar" type="submit" class="btn btn-success"> Liberar Recepcion</button>
                                            </td>
                                            <input name="docr" type="hidden" value="<?php echo $data->CVE_DOC?>"/>
                                            </form>      
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
