
<style type="text/css">
    .num {
        width: 5em;
    }
</style>
<br/>
<div>
    
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
                <option value="1">Finalizado</option>
                <option value="0">Pendiente</option>
                <option value="3">Cancelado</option>
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
    &nbsp;&nbsp;<button class="btn-sm btn-info add" >Agregar &nbsp;<i class="fa fa-plus"></i></button>

</div>
<br/>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Movimientos en el Almacen 
            </div>
            <div class="panel-body">
                <div class="table-responsive">                            
                    <table class="table table-striped table-bordered table-hover" id="dataTables-movimientos">
                        <thead>
                            <tr>
                                <th>Movimiento</th>
                                <th>Almacen</th>
                                <th>Origen</th>
                                <th>Tipo</th>
                                <th>Usuario</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Hora Inicial</th>
                                <th>Hora Final</th>
                                <th>Cantidad</th>
                                <th>Producto</th>
                                <th>Piezas</th>
                                <th>Componente <br/> Primario</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td colspan="13"></td>
                                <td></td>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php $i=0;foreach($info as $r): $i++; 
                                $color='';
                                if($r->STATUS == 'Pendiente '){
                                    $color="style='background-color: #FFF7C6;'";$sta=0;
                                }elseif($r->STATUS == 'Finalizado'){
                                    $color="style='background-color:#d1fef8;'";$sta=1;
                                }elseif($r->STATUS == 'Cancelado '){
                                    $color="style='background-color:#FFA07A;'";$sta=3;
                                }
                            ?>
                            <tr class="odd gradeX color" id="linc<?php echo $i?>" <?php echo $color?>>
                                <td><?php echo $r->MOV?></td>
                                <td><?php echo $r->ALMACEN?></td>
                                <td><?php echo $r->SIST_ORIGEN?></td>
                                <td><?php echo $r->TIPO?></td>
                                <td><?php echo $r->USUARIO?></td>
                                <td><?php echo $r->FECHA?></td>
                                <td><?php echo $r->STATUS?></td>
                                <td><?php echo $r->HORA_I?></td>
                                <td><?php echo $r->HORA_F?></td>
                                <td><?php echo $r->CANT?></td>
                                <td><?php echo $r->PROD?></td>
                                <td><?php echo $r->PIEZAS?></td>
                                <td><?php echo $r->COMPONENTE?></td>
                                <td><input type="button" value="Detalles" mov="<?php echo $r->MOV?>" class="btn-sm btn-info movDet"><br/><?php if($sta==0){?>
                                    <input type="button" value="Cancelar" mov="<?php echo $r->MOV?>" class="btn-sm btn-danger delMov"><?php }?></td>
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

    $(".add").click(function(){
        window.open('index.wms.php?action=wms_menu&opc=newMov', "_blank")
    })

    $(".movDet").click(function(){
        var mov = $(this).attr('mov');
        window.open('index.wms.php?action=wms_menu&opc=newMov&opc=ediMov:'+mov, "_blank")
    })
  
    $(".delMov").click(function(){
        var mov = $(this).attr('mov')
        $.confirm({
            columnClass: 'col-md-8',
            title: 'Desea cancelar el movimiento '+ mov +' ?',
            content: '<b>Cancelar Movimiento</b> <br/>' +
                    '<b>Motivo de la cancelacion:</b> <input type="text" size="50" maxlength="50" class="mot">'+
                    '<br/><font color="red">No se podra recuperar posteriormente.</font>',
                buttons: {
                    si:function(){
                        var mot = this.$content.find('.mot').val()
                        $.ajax({
                            url:'index.wms.php',
                            type:'post',
                            datatType:'json',
                            data:{canMov:1, mov, mot}, 
                            success:function(data){
                                location.reload(true)
                            },
                            error:function(data){
                                location.reload(true)
                            }
                        })
                    },
                    no:function(){
                        $.alert('vaya por fin algo congruente')
                    }
                }
        });
    })
</script>