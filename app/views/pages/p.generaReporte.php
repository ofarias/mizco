<br /><br />
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                                Datos de la Unidad.
                        </div>
                         <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <form action="index.php" method="post" id ="form1">
                                    <input  type="text" class="form-control" name="vehiculo" placeholder="Vehiculo" required="required" /><br />
                                    <input  type="text" class="form-control" name="placas" placeholder="Placas" required="required"/><br />
                                    <input  type="text" class="form-control" name="fecha" placeholder="Fecha" id="fecha" required="required"/><br />
                                    <input  type="number" step="any" class="form-control" name="cajas" placeholder="Cajas" required="required" /><br />
                                    <input  type="text" class="form-control" name="operador" placeholder ="Operador" required="required" /><br/>
                                    <input  type="text" class="form-control" name="observaciones" placeholder="Observaciones" required="required"/>
                                    <br/>
                                    <button type="submit" value="enviar" name="imprimirReporte" class="btn btn-success"> Guardar </button>

                                </form>
                            </div>
                        </div>
                   </div>
                </div>
</div>
<br /><br />

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
                                            <th>Factura / Remision</th>
                                            <th>Fecha Factura</th>
                                            <th>Cliente</th>
                                            <th>Pedido</th>
                                            <th>Importe</th>
                                        </tr>
                                    </thead>
                                  <tbody>
                                        <?php
                                        foreach ($datos as $data): 
                                           $color= $data->SELECCION == '1'? "style='background-color: green;'":"";
                                        ?>
                                       <tr class="odd gradex" <?php echo $color?> >
                                            <td><?php echo $data->CVE_DOC;?></td>
                                            <td><?php echo $data->FECHAELAB;?></td>
                                            <td><?php echo $data->NOMBRE;?></td>
                                            <td><?php echo $data->CVE_PEDI;?></td>
                                            <td><?php echo '$ '.number_format($data->IMPORTE,2);?></td>
                                        </tr> 
                                        <?php endforeach; ?>
                                 </tbody>
                                 </table>
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
    $("#fecha").datepicker({dateFormat: 'dd.mm.yy'});
  } );
  
</script>