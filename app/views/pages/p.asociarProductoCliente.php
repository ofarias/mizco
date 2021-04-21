
<br/>


<label>Cliente: <?php echo $nombre?></label><br/>
<label>Departamento: <?php echo $nomdepto?></label>

<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                              Productos
                        </div>
                           <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover" id="dataTables">
                                    <thead>
                                        <tr>
                                            <th>Clave</th>
                                            <th>Nombre</th>
                                            <th>Unidad de Medida</th>
                                            <th>Linea</th>
                                            <th>Existencias</th>
                                            <th>SKU</th>
                                            
                                            <th>Asociar</th>
                                        </tr>
                                    </thead>
                                  <tbody>
                                        <?php
                                        foreach ($productos as $datos): 
                                          ?>
                                       <tr>
                                            <td><?php echO $datos->CVE_ART;?></td>
                                            <td><?php echo $datos->DESCR;?></td>
                                            <td><?php echo $datos->UNI_MED?></td>
                                            <td><?php echo $datos->LIN_PROD;?></td>
                                            <td><?php echo $datos->EXIST;?></td>
                                            <form action="index.php" method="POST">
                                            <td>
                                            <input type="text" name="sku" value="<?php echo $datos->SKU?>" placeholder = "Favor de colocar el SKU" required = "required" >
                                            <input name= "deptonumber" type="hidden" value="<?php echo $datos->DEPTONUMBER;?>"/>
                                            <input type="hidden" name="cliente" value ="<?php echo $cliente?>">
                                            <input type="hidden" name="nombre" value="<?php echo $nombre?>">
                                            <input type="hidden" name="deptoname" value = "<?php echo $nomdepto?>">
                                            <input type="hidden" name="cprod" value = "<?php echo $datos->CVE_ART?>">
                                            </td>
                                            <td>
                                              <button name="asociarSKU" type = "submit" value = "enviar">Asociar SKU</button>
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