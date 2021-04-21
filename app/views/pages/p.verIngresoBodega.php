<br /><br />
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Listado de Recepciones de &oacute;rdenes de compra
            </div>
            <div class="panel-body">             
                <div class="table-responsive">  
                    <table class="table table-striped table-bordered table-hover" id="dataTables-oc-recepciones">
                        <thead>
                            <tr>
                                <th>Descripcion</th>
                                <th>Cantidad</th>
                                <th>Unidad</th>
                                <th>Fecha</th>
                                <th>Proveedor</th>
                                <th>Costo x Unidad</th>                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($ingresos != null) {
                                foreach ($ingresos as $data):
                                    ?>
                                    <tr class="odd gradeX">
                                     <form action ="index.php" method="post">
                                        <td><?php echo $data->DESCRIPCION; ?></td>
                                        <td><input name= "cantidad" type="number" step="any" value="<?php echo $data->CANT;?>" required = required </td>
                                        <td><input type="text" name="unidad" placeholder="Unidad de Medida" value="<?php echo $data->UNIDAD;?>"></td>
                                        <td><?php echo $data->FECHA; ?></td>
                                        <td><input type = "text" name="proveedor" placeholder="Proveedor" value="<?php echo $data->PROVEEDOR?>" />
                                        </td>
                                        <td align="right"><input type="number" step="any" name="costo" placeholder="<?php echo $data->COSTO?>" value="<?php echo (empty($data->COSTO))? '0':$data->COSTO; ?>"  /> </td>
                                        <td><button name="editIngresoBodega" type="submit" value="enviar" class="btn btn-info"> Guardar </button></td>
                                        <input type="hidden" name="idi" value="<?php echo $data->ID;?>" />
                                        </form>
                                    </tr>                                
                                    <?php
                                endforeach;
                            } else {
                                ?>                               
                                <tr class="odd gradeX">
                                    <td colspan="6">No hay datos</td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
