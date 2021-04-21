<br /><br />
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                                Revision de Facturacion.
                        </div>
                        <!-- /.panel-heading -->
                           <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <th>Factura</th>
                                            <th>Fecha Factura</th>
                                            <th>Cliente</th>
                                            <th>Pedido</th>
                                            <th>Importe</th>
                                            <th>Dias de Credito</th>
                                            <th>Fecha Entrega Original</th>
                                            <th>Fecha Entrega Modificafo</th>
                                            <th>Nuevo Vencimiento</th>
                                        </tr>
                                    </thead>
                                  <tbody>
                                        <?php
                                        foreach ($facturas as $data): 
                                           $color= $data->SELECCION == '1'? "style='background-color: green;'":"";
                                        ?>
                                       <tr class="odd gradex" <?php echo $color?> >
                                            <td><?php echo $data->CVE_DOC;?></td>
                                            <td><?php echo $data->FECHAELAB;?></td>
                                            <td><?php echo $data->NOMBRE;?></td>
                                            <td><?php echo $data->CVE_PEDI;?></td>
                                            <td><?php echo '$ '.number_format($data->IMPORTE,2);?></td>
                                            <td><?php echo $data->DIASCRED;?></td>
                                            <td><?php echo $data->FECHA_DOC;?></td>
                                            <td><?php echo substr($data->FECHA_ENT,0,10);?></td>
                                            <td><?php echo substr($data->FECHA_VEN, 0,10);?></td>
                                        </tr> 
                                        <?php endforeach; ?>
                                 </tbody>
                                 </table>
                                 <br/>
                      </div>
            </div>                 
        </div>
</div>

<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>

<script>    
  $(document).ready(function() {
    $(".fecha").datepicker({dateFormat: 'dd.mm.yy'});
  } );

</script>