
<br/>
<div class="row">
    <div class="container-fluid">
        <div class="form-horizontal">
            <div class="panel panel-default">
                <div class="panel panel-heading">
                    <h3>Datos del Cliente:</h3>
                </div>
                <br />
                <?php foreach ($cli as $dato):
                    $A = $dato->NOMBRE;
                ?>
                <div class="panel panel-body">
                    <form action="index.php" method="post" id="formpago">
                        <div class="form-group">
                            <label for="concepto" class="col-lg-2 control-label">Nombre: </label>
                            <div class="col-lg-10">
                                <label class = "col-lg-2 control-label"><?php echo $A; ?> --- SALDO : <?php echo $dato->SALDO;?></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="proveedor" class="col-lg-2 control-label">Direccion: </label>
                            <div class="col-lg-10">
                               <label class = "col-lg-2 control-label"><?php echo $dato->CALLE;?> No Ext: <?php echo $dato->NUMEXT?> </label>
                        </div>
                        </div>
                        <div class="form-group">
                            <label for="clasificacion" class="col-lg-2 control-label">Telefono: </label>
                            <div class="col-lg-10">
                               <label class="col-lg-2 control-label"><?php echo $dato->TELEFONO;?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cuenta" class="col-lg-2 control-label">Cuenta de pago: </label>
                            <div class="col-lg-10">
                                <select class="form-control" name="banco" required = "required"><br/>
                                    <?php foreach ($cuenta as $cb): ?>
                                        <option value="<?php echo $cb->BANCO; ?>"> <?php echo $cb->BANCO; ?></option>
                                    <?php endforeach; ?>
                                    
                                </select>
                                <input type = "hidden" name = "cliente" value = "<?php echo $dato->CLAVE?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="referencia" class="col-lg-2 control-label">Monto del Pago : </label>
                            <div class="col-lg-10">
                                <input type="number" step = "any" class="form-control" name="monto" placeholder="Monto del Pago" required = "required"/><br>
                            </div>
                        </div>
                        
                        

                        <div class="form-group">
                            <label for="monto" class="col-lg-2 control-label">Fecha de Registro Pago: </label>
                            <div class="col-lg-10">
                                <input type="text" class="fecha" name="fechaR" placeholder="Fecha Registro" required = "required"/><br>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="movpar" class="col-lg-2 control-label">Fecha de Aplicacion: </label>
                            <div class="col-lg-10">
                                <input name="fechaA" required="required" class="fecha" placeholder="Fecha Aplicacion" ><br/>
                                  
                                </select>
                            </div>
                        </div>
                       
                <div class="panel-footer">
                    <!-- Submit Button  -->
                    <div class="form-group">
                        <div class="col-lg-11 col-lg-offset-1 text-right">
                            <button name="guardaPago" form="formpago" type="submit" class="btn btn-warning"> Generar <i class="fa fa-file"></i></button>
                            <a href="index.php?action=inicio" class="btn btn-warning"> Cancelar <i class="fa fa-times"></i></a>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
            </div>
        </div>
    </div>
</div>

<?php
if (empty($reg)){
}else{ 
?>
<br /><br />
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                          Pagos Registrados con Saldo.
                        </div>
                           <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover" id="dataTables">
                                    <thead>
                                        <tr>
                                            <!--<th>Todos: <input type="checkbox" name="marcarTodo" id="marcarTodo" /></th>-->
                                            <!--<th>Or</th>-->
                                            <th>ID</th>
                                            <th>FECHA REGISTRO</th>
                                            <th>MONTO</th>
                                            <th>SALDO ACTUAL</th>
                                            <th>BANCO</th>
                                            <th>USUARIO QUE REGISTRO</th>
                                        </tr>
                                    </thead>
                                  <tbody>
                                        <?php
                                        foreach ($reg as $datos): ?>
                                       <tr>
                                            <td><?php echo $datos->ID?></td>
                                            <td><?php echo $datos->FECHA;?></td>
                                            <td><?php echo $datos->MONTO;?></td>
                                            <td><?php echo $datos->SALDO;?></td>
                                            <td><?php echo $datos->BANCO;?></td>
                                            <td><?php echo $datos->USUARIO;?></td>
                                        
                                        </tr>
                                        <?php endforeach; ?>
                                 </tbody>
                                 </table>
                            <!-- /.table-responsive -->
                      </div>
            </div>
        </div>
</div>
<?php
        }
?>

<!--Modified by GDELEON 3/Ago/2016-->
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script>

  $(document).ready(function() {
    $(".fecha").datepicker({dateFormat: 'mm-dd-yy'});
  } );
  
  
  </script>