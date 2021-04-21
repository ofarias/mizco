<br/>
<br/>
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Lista de Validaciones pendientes de Impresión.
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-aplicapago">
                                    <thead>
                                        <tr>
                                            <th>Folio</th>
                                            <th>Proveedor</th>
                                            <th>OC</th>
                                            <th>Recepcion</th>
                                            <th>Importe</th>
                                            <th>Resultado</th>
                                            <th>Imprimir</th>
                                        </tr>
                                    </thead>                                   
                                  <tbody>
                                        <?php 
                                        foreach ($validaciones as $data): 
                                            ?>
                                        <tr>
                                         <!--<tr class="odd gradeX" style='background-color:yellow;' >-->
                                            <td><?php echo $data->IDVAL;?></td>
                                            <td><?php echo $data->PROVEEDOR;?></td>
                                            <td><?php echo $data->OC;?></td>
                                            <td><?php echo $data->RECEPCION;?></td>
                                            <td><?php echo $data->IMPORTE_VAL;?></td>
                                            <td><?php echo $data->RESULTADO;?></td>
                                            <form action="index.php" method="post">
                                            <input name="idval" type="hidden" value="<?php echo $data->IDVAL?>"/>
                                            <td>
                                                <button name="imprimeValidacion" type="submit" value="enviar" class="btn btn-info"> Imprimir <i class="fa fa-print"></i></button></td> 
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
