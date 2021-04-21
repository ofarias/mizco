
<br>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default"> 
            <div class="panel-heading">
                A P L I C A C I O N.
            </div>
  <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                <?php 
                                    foreach ($pago as $key):
                                ?>
                                    
                                    <label> El monto del pago es de: $ <?php echo number_format($key->MONTO,2)?> </label><br>
                                    <label> El saldo actual es de: $ <?php echo number_format($key->SALDO,2)?></label><br>
                                    <label> El total de monto aplicado es: $ <?php echo number_format($total,2)?></label><br>
                                <?php endforeach; ?>
                                </table>
                            </div>
                    </div>
                    </div>
    </div>
</div>

<br>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default"> 
            <div class="panel-heading">
                R e l a c i o n ---  D e  ---   F a c t u r a s  ---   P a g a d a s.
            </div>
  <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>No.Aplicacion</th>
                                            <th>Clave</th>
                                            <th>CLIENTE</th>
                                            <th>Fecha Aplicacion</th>
                                            <th>Documento</th>
                                            <th>Importe</th>
                                            <th>Saldo Documento</th>
                                            <th>Monto Aplicado</th>
                                            <th>Saldo Pago</th>
                                            <th>Usuario</th>
                                            <th>Imprimir</th>
                                        </tr>
                                    </thead>                                   
                                  <tbody>
                                        <?php 
                                        foreach ($facturas as $data):
                                    
                                            ?>
                                        <tr>
                                         <!--<tr class="odd gradeX" style='background-color:yellow;' >-->
                                            <td><?php echo $data->ID;?></td>
                                            <td><?php echo $data->CLAVE?></td>
                                            <td><?php echo $data->CLIENTE;?></td>
                                            <td><?php echo $data->FECHA;?></td>
                                            <td><?php echo $data->DOCUMENTO;?></td>
                                            <td><?php echo '$ '.number_format($data->IMPORTE,2)?></td>
                                            <td><?php echo '$ '.number_format($data->SALDO_DOC,2);?></td>
                                            <td><?php echo '$ '.number_format($data->MONTO_APLICADO,2);?></td>
                                            <td><?php echo '$ '.number_format($data->SALDO_PAGO,2)?></td>
                                            <td><?php echo $data->USUARIO?></td>
                                            <form action="index.php" method="post">
                                            <td>
                                                <input type="hidden" name="ida" value ="<?php echo $data->ID?>">
                                                <button type="submit" value="enviar" name="impAplicacion" class="btn btn-warning"> Imprimir </button>
                                            </td>     
                                            </form>
                                        </tr>
                                        
                                        <?php endforeach; ?>
                            
                                 </tbody>
                                </table>
                            </div>
                    </div>
                    </div>
    </div>
</div>


