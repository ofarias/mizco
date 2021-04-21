<br /><br />
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                                Revision de Facturacion.
                        </div>
                           <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>UNIDAD</th>
                                            <th>OPERADOR</th>
                                            <th>OBSERVACIONES</th>
                                            <th>VEHICULO</th>
                                            <th>PLACAS</th>
                                            <th>FECHA_REPORTE</th>
                                            <th>FECHA_ELABORACION</th>
                                            <th>CAJAS</th>
                                            <th>ESTATUS</th>
                                        </tr>
                                    </thead>
                                  <tbody>
                                        <?php
                                        foreach ($reportes as $data): 
                                           $color= $data->ESTATUS == 'Cancelado'? "style='background-color: red;'":"";
                                        ?>
                                       <tr class="odd gradex" <?php echo $color?> >
                                            <td><?php echo $data->ID;?></td>
                                            <td><?php echo $data->UNIDAD;?></td>
                                            <td><?php echo $data->OPERADOR;?></td>
                                            <td><?php echo $data->OBSERVACIONES;?></td>
                                            <td><?php echo $data->VEHICULO?></td>
                                            <td><?php echo $data->PLACAS?></td>
                                            <td><?php echo $data->FECHA_REPORTE?></td>
                                            <td><?php echo $data->FECHA_ELABORACION?></td>
                                            <td><?php echo $data->CAJAS?></td>
                                            <td><?php echo $data->ESTATUS?></td>
                                            <form action="index.php" method="post">
                                            <input type="hidden" name="idr" value="<?php echo $data->ID;?>" />
                                            <td>
                                             <button name="reporteEmbarque" type="submit" value="enviar" class= "btn btn-warning"> 
                                               Ver</button>
                                             </td> 
                                            </form>
                                        </tr> 
                                        <?php endforeach; ?>
                                 </tbody>
                                 </table>
                                 <br/>
                      </div>
            </div>                 
        </div>
</div>



