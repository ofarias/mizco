
<style type="text/css">
    .num {
        width: 5em;
    }
</style>
<br/>
<div class="hidden">
    <font color="black"><b>Tipo:</b></font>  
    <select class="ftcomp">
            <option value='"none"'>Tipo de componente</option>
        <?php foreach($tc as $tcomp):?>
            <option value="<?php echo $tcomp->ID_TC?>"><?php echo $tcomp->NOMBRE?></option>
        <?php endforeach;?>
    </select>
    <font color="black"><b>Almacen:</b></font> 
        <select class="falma">
                <option value='"none"'>Almacen</option>
            <?php foreach($alm as $almac):?>
                <option value="<?php echo $almac->ID?>"><?php echo $almac->NOMBRE?></option>
            <?php endforeach;?>
        </select>    
    
    <font color="black"><b>Estado:</b></font>  
        <select class="fsta">
                <option value='"none"'>Seleccione el estado</option>
                <option value="1">Activo</option>
                <option value="0">Baja</option>
                <option value="3">Agotado</option>
        </select>      
    <font color="black"><b>Producto:</b></font> 
        <select class="fprod">
                <option value='"none"'>Productos</option>
            <?php foreach($prod as $pro):?>
                <option value="<?php echo $pro->ID_PINT?>"><b><?php echo '<b>'.$pro->ID_INT.'</b>--'.substr($pro->DESC,0,30)?></b></option>
            <?php endforeach;?>
        </select>
    <font color="black"><b>Asociado:</b></font>
        <input type="radio" name="aso" class="hidden" value='"none"' checked="checked">
        &nbsp;Si:<input type="radio" name="aso" value='"si"'>&nbsp;
        &nbsp;&nbsp;No:<input type="radio" name="aso" value='"no"'>&nbsp;&nbsp;

    <input type="button" value="ir" name="tipo" class="btn-sm btn-primary filtro">

</div>
<br/>
<?php foreach($info as $k){}?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Movimientos del componente <b><?php echo $k->TIPO.' ('.$k->ETIQUETA.') Observaciones: '.$k->OBS?></b>
            </div>
            <div class="panel-body">
                <div class="table-responsive">                            
                    <table class="table table-striped table-bordered table-hover" id="dataTables-componentes">
                        <thead>
                            <tr>
                                <th>Mov</th>
                                <th>Almacen</th>
                                <th>Componente</th>
                                <th>Tipo</th>
                                <th>Usuario</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Hora Inicio</th>
                                <th>Hora Final</th>
                                <th>Producto</th>
                                <th>Cantidas</th>
                                <th>Unidad</th>
                                <th>Entrada</th>
                                <th>Salidas</th>
                                <th>Disponible</th>
                                <th>Color</th>
                                <th>Componente Primario</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td colspan="14"></td>
                                <!--
                                <td><a target="_blank" href="index.php?action=imprimircatgastos" class="btn btn-info">Imprimir <i class="fa fa-print"></i></a></td>
                                <td><button class="btn-sm btn-info add" >Agregar &nbsp;<i class="fa fa-plus"></i></button></td>-->
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php $i=0;foreach($det as $mov): $i++; ?>
                            <tr class="color" id="linc<?php echo $i?>">
                                <td><?php echo $mov->MOV?></td>
                                <td><?php echo $mov->ALMACEN?></td>
                                <td><?php echo $mov->SECUNDARIO?></td>
                                <td><?php echo $mov->TIPO?></td>
                                <td><?php echo $mov->USUARIO?></td>
                                <td><?php echo $mov->FECHA?></td>
                                <td><?php echo $mov->STATUS?></td>
                                <td><?php echo $mov->HORA_I?></td>
                                <td><?php echo $mov->HORA_F?></td>
                                <td><?php echo $mov->PROD?></td>
                                <td align="right"><?php echo number_format($mov->CANT,0)?></td>
                                <td><?php echo $mov->UNIDAD?></td>
                                <td align="right"><?php echo number_format($mov->PIEZAS,0)?></td>
                                <td align="right"><?php echo number_format($mov->SALIDAS,0)?></td>
                                <td align="right"><?php echo number_format($mov->PIEZAS-$mov->SALIDAS,0)?></td>
                                <td><?php echo $mov->COLOR?></td>
                                <td><?php echo $mov->COMPP?></td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script type="text/javascript">


    
</script>