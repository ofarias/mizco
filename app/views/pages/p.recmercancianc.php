<br /> <br/> <br/>
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Recibir Mercancia de Devolucion a Bodega.
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-oc">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>ENVIO</th>
                                            <th>Documento</th>
                                            <th>Caja</th>
                                            <th>Fecha</th>                                         
                                            <th>Paquete</th>
                                            <th>Clave</th>
                                            <th>Descripcion</th>
                                            <th>Cantidad</th>
                                            <th>Cantidad Devuelta</th>
                                            <th>Paquete1</th>
                                            <th>de</th>
                                            <th>Paquete2</th>
                                            <th>Tipo</th>
                                            <th>Peso</th>
                                            <th>Cantidad</th>
                                            <th>Recibir</th>

                                        </tr>
                                    </thead>                                   
                                  <tbody>
                                        <?php 
                                        foreach ($embalaje as $data):
                                            ?>
                                        <tr>
                                          <!--<tr class="odd gradeX" style='background-color:yellow;' >-->
                                            <td><?php echo $data->ID_PREOC;?></td>
                                            <td><?php echo $data->TIPO_ENVIO;?></td>
                                            <td><?php echo $data->DOCUMENTO;?></td>
                                            <td><?php echo $data->IDCAJA;?></td>
                                            <td><?php echo $data->FECHA_PAQUETE;?></td>
                                            <td><?php echo $data->EMPAQUE;?></td>
                                            <td><?php echo $data->ARTICULO;?></td>
                                            <td><?php echo $data->DESCRIPCION;?></td>
                                            <td><?php echo $data->CANTIDAD;?></td>
                                            <td><?php echo $data->DEVUELTO;?></td>
                                            <td><?php echo $data->PAQUETE1;?></td>
                                            <td> de </td>
                                            <td><?php echo $data->PAQUETE2;?></td>
                                            <td><?php echo $data->TIPO_EMPAQUE;?></td>
                                            <td><?php echo $data->PESO;?></td>  
                                            <form action="index.php" name="form" method="post">
                                            <td><input name = "cantr" value ="0" required = "required" /></td>
                                            <input name="docf" type="hidden" value="<?php echo $data->DOCUMENTO?>"/>
                                            <input name="idc" type="hidden" value="<?php echo $data->IDCAJA?>" />
                                            <input name="id" type= "hidden" value="<?php echo $data->ID?>"/>
                                            <input name="idpreoc" type = "hidden" value = "<?php echo $data->ID_PREOC?>" />                                            
                                            <td> 
                                            <button name = "recibirCajaNC" type="submit" value="enviar"> Recibir </button>
                                            </td>    
                                            </form>
                                        </tr>
                                        <?php endforeach; ?>
                                 </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                      </div>
                      <!--<div class= "panel-footer" >
                            <button form = "recibir" name = "recibirCaja"> Recibir </button>
                      </div>-->
            </div>
        </div>

</div>
<br/>

<br /> <br/> <br/>
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Mercancia de Devolucion en Bodega.
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-oc">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>ENVIO</th>
                                            <th>Documento</th>
                                            <th>Caja</th>
                                            <th>Fecha</th>                                         
                                            <th>Paquete</th>
                                            <th>Clave</th>
                                            <th>Descripcion</th>
                                            <th>Cantidad</th>
                                            <th>Devuelto</th>
                                            <th>Paquete1</th>
                                            <th>de</th>
                                            <th>Paquete2</th>
                                            <th>Tipo</th>
                                            <th>Peso</th>

                                        </tr>
                                    </thead>                                   
                                  <tbody>
                                        <?php 
                                        foreach ($devuelto as $data):
                                            ?>
                                        <tr>
                                          <!--<tr class="odd gradeX" style='background-color:yellow;' >-->
                                            <td><?php echo $data->ID_PREOC;?></td>
                                            <td><?php echo $data->TIPO_ENVIO;?></td>
                                            <td><?php echo $data->DOCUMENTO;?></td>
                                            <td><?php echo $data->IDCAJA;?></td>
                                            <td><?php echo $data->FECHA_PAQUETE;?></td>
                                            <td><?php echo $data->EMPAQUE;?></td>
                                            <td><?php echo $data->ARTICULO;?></td>
                                            <td><?php echo $data->DESCRIPCION;?></td>
                                            <td><?php echo $data->CANTIDAD;?></td>
                                            <td><?php echo $data->DEVUELTO;?></td>
                                            <td><?php echo $data->PAQUETE1;?></td>
                                            <td> de </td>
                                            <td><?php echo $data->PAQUETE2;?></td>
                                            <td><?php echo $data->TIPO_EMPAQUE;?></td>
                                            <td><?php echo $data->PESO;?></td>
                                            <form action="index.php" name="form1" method="post" id="recimp">
                                            <input name="cantr" type="hidden" value = "<?php echo $data->DEVOLUCION?>" />
                                            <input name="docf" type="hidden" value="<?php echo $data->DOCUMENTO?>"/>
                                            <input name="idc" type="hidden" value="<?php echo $data->IDCAJA?>" />
                                            <input name="id" type= "hidden" value="<?php echo $data->ID?>"/>
                                            <input name="idpreoc" type = "hidden" value = "<?php echo $data->ID_PREOC?>" />
                                            </form>
                                        </tr>
                                        <?php endforeach; ?>
                                 </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                      </div>
                      <div class= "panel-footer" >
                            <button form = "recimp" name = "ImprimirDevolucion"> Recibir e Imprimir </button>
                      </div>
            </div>
        </div>

</div>
<br/>
