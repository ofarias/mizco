
<br/>
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                              Clientes
                        </div>
                           <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover" id="dataTables">
                                    <thead>
                                        <tr>
                                            <th>Clave</th>
                                            <th>Cliente</th>
                                            <th>Direccion</th>
                                            <th>Departamento</th>
                                            <th>Nombre del Departamento</th>
                                            <th>Ver Productos</th>
                                        </tr>
                                    </thead>
                                  <tbody>
                                        <?php
                                        foreach ($cliente as $datos): 
                                          ?>
                                       <tr>
                                            <td><?php echO $datos->CLAVE;?></td>
                                            <td><?php echo $datos->NOMBRE;?></td>
                                            <td><?php echo $datos->CALLE.', No '.$datos->NUMINT;?></td>
                                            <td><?php echo $datos->DEPTONUMBER;?></td>
                                            <td><?php echo $datos->DEPTONAME;?></td>
                                            <form action="index.php" method="POST">
                                            <td>
                                            <input name= "deptonumber" type="hidden" value="<?php echo $datos->DEPTONUMBER;?>"/>
                                            <input type="hidden" name="cliente" value ="<?php echo $datos->CLAVE?>">
                                            <input type="hidden" name="nombre" value="<?php echo $datos->NOMBRE?>">
                                            <input type="hidden" name="deptoname" value = "<?php echo $datos->DEPTONAME?>">
S                                              <button name="traeProductosCliente" type = "submit" value = "enviar">Asociar Producto</button>
                                            </td>
                                            </form>
                                        <?php endforeach ?>
                                        </tr>
                                       
                                 </tbody>
                                 </table>
                            <!-- /.table-responsive -->
                      </div>
            </div>
        </div>
</div>