
<style type="text/css">
    .num {
        width: 5em;
    }
</style>

<br/>
<div>
    
    <font color="black"><b>Almacen:</b></font> &nbsp;&nbsp; 
        <select class="falma">
                <option value='"none"'>Almacen</option>
            <?php foreach($alm as $almac):?>
                <option value="<?php echo $almac->ID?>"><?php echo $almac->NOMBRE?></option>
            <?php endforeach;?>
        </select>    
    
     &nbsp;&nbsp;<font color="black"><b>Estado:</b></font> &nbsp;&nbsp;  
        <select class="fsta">
                <option value='"none"'>Estado</option>
                <option value='"F"'>Finalizado</option>
                <option value='"P"'>Pendiente</option>
                <option value='"C"'>Cancelado</option>
                <option value='"B"'>Eliminado</option>
        </select>      
     &nbsp;&nbsp;<font color="black"><b>Producto:</b></font> &nbsp;&nbsp; 
                <input type="text" maxlength="60" id="prod" size="60"  placeholder="producto">
     &nbsp;&nbsp;<font color="black"><b>Tipo:</b></font> &nbsp;&nbsp;     
    <select class="ftipo">
        <option value='"none"'>Tipo</option>
        <option value='"e"'>Entrada</option>
        <option value='"s"'>Salida</option>
        <option value='"t"'>Traspaso</option>
        <option value='"r"'>Reacomodo</option>
        <option value='"d"'>Devolucion</option>
        <option value='"m"'>Merma</option>
    </select>

     &nbsp;&nbsp;<font color="black"><b>Usuario:</b></font> &nbsp;&nbsp;     
    <select class="fuser">
        <option value='"none"'>Usuario</option>
        <?php foreach($usuarios as $us):?>
            <option value="<?php echo $us->ID?>"><?php echo $us->NOMBRE?></option>
        <?php endforeach;?>
    </select>    

     &nbsp;&nbsp;
    <font color="black"><b>Componente:</b></font>&nbsp;&nbsp;   
        <input type="text" size="30" maxlength="45" id="comp" size="45" placeholder="Componente">
    
    &nbsp;&nbsp;
    <input type="button" value='"ir"' name="tipo" class="btn-sm btn-primary filtro">
    &nbsp;&nbsp;<button class="btn-sm btn-info add" >Agregar &nbsp;<i class="fa fa-plus"></i></button>
    <br/>
    <b>fecha del movimiento del: <input type="date" id="fi" value='"none"'> &nbsp;&nbsp; al: &nbsp;&nbsp; <input type="date" id="ff" value='"none"'>
    &nbsp;&nbsp;
    Guardar en : <button class="filtro" value='"p"'><font color="purple">Pdf</font></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <button class="filtro" value='"x"'><font color="grey">Excel</font></button>
    </b>
    &nbsp;&nbsp; 
    Detalle Movimientos: <button class="filtro" value='"dm"'>

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
                                $color='';$sta='';
                                if(trim($r->STATUS) == 'Pendiente'){
                                    $color="style='background-color: #FFF7C6;'";$sta=0;
                                }elseif(trim($r->STATUS) == 'Finalizado'){
                                    $color="style='background-color:#d1fef8;'";$sta=1;
                                    if($r->TIPO == 'Salida'){
                                        $color="style='background-color:#baffb3;'";$sta=1;
                                    }
                                }elseif(trim($r->STATUS) == 'Cancelado'){
                                    $color="style='background-color:#FFA07A;'";$sta=3;
                                }elseif (trim($r->STATUS) == 'Eliminado') {
                                    $color="style='background-color:#f33737;'";$sta=3;
                                }


                            ?>
                            <tr class="odd gradeX color" id="linc<?php echo $i?>" <?php echo $color?> >
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
                                <td title="<?php echo $r->PROD?>" ><?php echo substr($r->PROD,0,50).'...'?></td>
                                <td><?php echo $r->PIEZAS?></td>
                                <td><?php echo $r->COMPONENTE?></td>
                                <td><?php if($sta==0){?>
                                    <input type="button" value="Editar" mov="<?php echo $r->MOV?>" class="btn-sm btn-info movDet"><br/>
                                    <input type="button" value="Cancelar" mov="<?php echo $r->MOV?>" class="btn-sm btn-danger delMov" tipo="c"><?php }elseif($sta == 1){?>
                                        <a href="index.wms.php?action=wms_menu&opc=detMov:<?php echo $r->MOV.':'.$r->TIPO?>" target="popup" class="btn-sm btn-warning" onclick="window.open(this.href, this.target, 'width=1600, height=1000'); return false;">Detalle</a><br/><br/><input type="button" value="Eliminar" mov="<?php echo $r->MOV?>" class="btn-sm btn-danger delMov" tipo="b">
                                    <?php }elseif($sta == 3){?>
                                        <a href="index.wms.php?action=wms_menu&opc=detMov:<?php echo $r->MOV.':'.$r->TIPO?>" target="popup" class="btn-sm btn-warning" onclick="window.open(this.href, this.target, 'width=1600, height=1000'); return false;">Detalle</a><br/>
                                    <?php }?>
                                </td>
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

    $(".filtro").click(function(){
        var out = $(this).val()
        var o = ''
        var a = $(".falma").val()
        var e = $(".fsta").val()
        var p = '"'+document.getElementById("prod").value+'"' //auto complete
        var t = $(".ftipo").val()
        var us = $(".fuser").val()
        var comp = '"'+document.getElementById("comp").value +'"'//auto complete
        if(p===''){p='"none"';}
        if(comp===''){comp = '"none"';}

        var fi = document.getElementById('fi').value
        var ff = document.getElementById('ff').value
        //$.alert('Se filtra por los siguientes valores'+ out + a + e + 'prod: ' +p + t + ' us:'+us + 'comp:'+ comp)
        
        if(out=='"ir"' || out == '"p"' || out=='"dm"'){
            if(out=='"ir"'){
                o='_self'
            }else{
                o='_blank'
            }
            window.open('index.wms.php?action=wms_menu&opc=m{"t":'+t+',"a":'+a+',"p":'+p+',"e":'+e+',"us":'+us+',"out":'+ out+',"fi":"'+fi+'","ff":"'+ff+'", "cp":'+comp+'}', o)
        }else{
            var param='{"t":'+t+',"a":'+a+',"p":'+p+',"e":'+e+',"us":'+us+',"out":'+ out+',"fi":"'+fi+'","ff":"'+ff+'", "cp":"'+comp+'"}';
            $.ajax({
                url:'index.wms.php',
                type:'post',
                dataType:'json',
                data:{xlsComp:1, op:'c', param},
                success:function(data){
                    window.open( data.completa , 'download')
                },
                error:function(){
                    $.alert('Algo ocurrio')
                }
            })
        }
    })


    $(".add").click(function(){
        window.open('index.wms.php?action=wms_menu&opc=newMov', "_blank")
    })

    $(".movDet").click(function(){
        var mov = $(this).attr('mov');
        window.open('index.wms.php?action=wms_menu&opc=newMov&opc=ediMov:'+mov, "_blank")
    })
  
    $(".delMov").click(function(){
        var mov = $(this).attr('mov')
        var t = $(this).attr('tipo')
        var mensaje = ''; var titulo=''; var titulo2= '';
        if(t == 'c'){
            mensaje = 'Desea cancelar el movimiento '+ mov +' ?'; titulo = 'Cancelar '; titulo2 = 'Cancelacion';
        }else if(t == 'd'){
            mensaje = 'Desea eliminar el movimiento '+ mov +' ?';titulo = 'Eliminar'; titulo2= 'Eliminacion';
        }
        $.confirm({
            columnClass: 'col-md-8',
            title: mensaje,
            content: '<b>'+ titulo + ' Movimiento</b> <br/>' +
                    '<b>Motivo de la '+titulo2+' :</b> <input type="text" size="50" maxlength="50" class="mot">'+
                    '<br/><font color="red">No se podra recuperar posteriormente.</font>',
                buttons: {
                    si:function(){
                        var mot = this.$content.find('.mot').val()
                        $.ajax({
                            url:'index.wms.php',
                            type:'post',
                            datatType:'json',
                            data:{canMov:1, mov, mot, t}, 
                            success:function(data){
                                //location.reload(true)
                            },
                            error:function(data){
                                //location.reload(true)
                            }
                        })
                    },
                    no:function(){
                        $.alert('vaya por fin algo congruente')
                    }
                }
        });
    })

    $("#prod").autocomplete({
        source: "index.wms.php?producto=1",
        minLength: 2,
        select: function(event, ui){
        }
    })

    $("#comp").autocomplete({
        source: "index.wms.php?componente=1",
        minLength: 2,
        select: function(event, ui){
        }
    })
</script>