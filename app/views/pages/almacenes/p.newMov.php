<br/><br/>
<!--
<style type="text/css">
    td.details-control {
        background: url('app/views/images/cuadre.jpg') no-repeat center center;
        cursor: pointer;
    }
    tr.shown td.details-control {
        background: url('app/views/images/cuadre.jpg') no-repeat center center;
    }
</style>
-->
<?php $mo=0; foreach($partidas as $m){
        $mo++;
    break;
}?>
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           <font size="5px"> Movimiento de Bodeja <?php echo $mov?></font> 
                        </div>
                        <br/>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            Tipo:    <select class="tip control" name="algo">
                                    <?php if($mo>=1 or $ver=='v2'){?>
                                        <option value="<?php echo isset($m->ID_TIPO)? $m->ID_TIPO: $t?>"><?php echo isset($m->TIPO)? @$m->TIPO:$datos->TIPO?></option>
                                    <?php }else{?>
                                        <option value="none">Seleccione un tipo</option>
                                        <option value="e">Entrada</option>
                                        <option value="s">Salida</option>
                                        <option value="r">Reacomodo</option>
                                        <option value="d">Entrada Devolucion</option>
                                        <option value="m">Merma</option>
                                        <option value="t">Traspaso entre Almacenes</option>
                                    <?php }?>
                                    </select>
                            <br/><br/>

                            <label class="<?php echo (@$ver=='v2' or  @$mo>=1)? '':'hidden' ?>" id="almv">&nbsp;&nbsp;&nbsp;&nbsp;Almacen:
                                    <select class="alm control">
                                        <?php if($mo>=1 or $ver=='v2'){?>
                                            <option value="<?php echo isset($m->ID_ALMACEN)? $m->ID_ALMACEN:$al?>"><?php echo isset($m->ALMACEN)? $m->ALMACEN:$datos->NOMBRE ?></option>
                                        <?php }else{?>
                                            <option value="none">Seleccione Almacen</option>
                                            <?php foreach($alm as $a):?>
                                                <option value="<?php echo $a->ID?>"><?php echo $a->NOMBRE.' -> '.$a->UBICACION?></option>
                                            <?php endforeach;?>
                                        <?php }?>
                                        
                                    </select>
                            </label>

                            <br/><br/>

                            <label class="<?php echo (@$ver=='v2' or  @$mo>=1)? '':'hidden' ?>" id="compPv">&nbsp;&nbsp;&nbsp;&nbsp;Compente Primario (principalmente lineas):
                                        <select class="compP control">
                                        <?php if($mo >= 1){?>
                                            <option value="<?php echo $m->ID_COMPP ?>"><?php echo $m->COMPP?></option>
                                        <?php }else{ ?>
                                        <?php if($ver=='v2'){ ?>
                                            <option value="<?php echo $c ?>"><?php echo $datos->COMPP?></option>
                                            <option value="none">Seleccione un componente</option>
                                            <?php foreach($compP as $c1):?>
                                                <option value="<?php echo $c1->ID_COMP?>"><?php echo $c1->ETIQUETA.' -> '.$c1->ALMACEN.' --> '.$c1->TIPO?></option>
                                            <?php endforeach;?>
                                        <?php }else{?>
                                            <option value="none">Seleccione un componente</option>
                                            <?php foreach($compP as $c1):?>
                                                <option value="<?php echo $c1->ID_COMP?>"><?php echo $c1->ETIQUETA.' -> '.$c1->ALMACEN.' --> '.$c1->TIPO?></option>
                                            <?php endforeach;?>
                                        <?php }}?>
                                        </select>    
                            </label>
                            <br/><br/>
                            <label class="<?php echo (@$ver=='v2' and !empty(@$c) or @$mo>=1)? '':'hidden' ?>" id="compSv">&nbsp;&nbsp;&nbsp;&nbsp;Compente Secundario (principalmente tarimas/palets):
                                    <select class="compS control" id="selcomps">
                                        <?php if($mo >= 1){?>
                                        <option value="<?php echo $m->ID_COMPS?>"><?php echo $m->COMPS?></option>
                                        <?php } ?>
                                        <option value="none">Seleccione un componente</option>
                                        <?php foreach($compA as $c2):?>
                                            <option value="<?php echo $c2->ID_COMP?>"><?php echo $c2->ETIQUETA.' -> '.$c2->ALMACEN.' --> '.$c2->TIPO?></option>
                                        <?php endforeach;?>
                                    </select>
                             </label>
                            <br/><br/>
                            <?php if($mov>=1){?>
                            &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="" value="<?php echo $m->STATUS=='Finalizado'? 'Finalizado':'Finalizar'.$m->COMPS?>" class="btn-sm btn-primary execMov" tipo="end" idMov="<?php echo $m->ID_AM?>" <?php echo $m->STATUS=='Finalizado'? 'disabled':''?>>
                            <?php if($m->STATUS == 'Finalizado'):?>
                            &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Imprimir" class="btn-sm btn-warning" >
                            &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Genera QR" class="btn-sm btn-success" >
                            &nbsp;&nbsp;&nbsp;&nbsp;<button class="btn-sm btn-info addMov" >Agregar &nbsp;<i class="fa fa-plus"></i></button>
                            <?php endif;?>
                            <?php }elseif($ver=='v2'){?>
                                &nbsp;&nbsp;&nbsp;&nbsp;<a class="btn-sm btn-primary" href="index.wms.php?action=wms_menu&opc=newMov" target="_self">Limpiar</a>
                            <?php }?>
                           <div class="panel-body">
                            <div class="table-responsive detalle">                            
                                <table class="table table-striped table-bordered table-hover" id="dataTables">
                                    <thead>
                                        <tr>
                                            <th> Codigo de Barras</th>
                                            <th> Producto </th>
                                            <th> Unidad </th>
                                            <th> Cantidad </th>
                                            <th> Color </th>
                                            <th> Total Piezas </th>
                                            <th> Agregar </th>
                                        </tr>
                                    </thead>
                                  <tbody>
                                       <tr>
                                            <td><input type="text" name="" placeholder="Codigo de Barras"></td>
                                            <td>
                                                <input type="text" placeholder="Producto" id="prod" class="prod" size="50" maxlength="100" required="required" >
                                        <!--        <select class="prod">
                                                <option value="none">Seleccione un producto</option>
                                                <?php foreach($prod as $p):?>
                                                    <option value="<?php echo $p->ID_PINT?>"><?php echo $p->ID_INT.' ('.$p->DESC.')'?></option>
                                                <?php endforeach;?>
                                            </select>
                                        -->
                                        </td>
                                            <td><select class="uni total">
                                                <option>Unidades de Entrada</option>    
                                                <?php foreach($uniE as $u):?>
                                                    <option value="<?php echo $u->ID_UNI?>" fact="<?php echo $u->FACTOR?>"><?php echo $u->FACTOR.'--'.$u->DESC.' -> Factor x '.$u->FACTOR.' En Palet caben: '.$u->PZS_PALET?></option>
                                                <?php endforeach;?>
                                            </select></td>
                                            <td><input class="cant total" type="number" min="1" max="100"></td>
                                            <td>
                                                <select class="col">
                                                    <option value="">Seleccione color</option>
                                                    <option>Rojo</option>
                                                    <option>Negro</option>
                                                    <option>Blanco</option>
                                                    <option>Verde</option>
                                                    <option>Mixto (R,N,B,V)</option>
                                                </select>
                                                <!--<input class="col" type="text" name="" placeholder="color">-->
                                            </td>
                                            <td align="rigth"><label id="totPzas"></label></td>
                                            <td>
                                                <?php if(@$m->STATUS!='Finalizado'):?>
                                                        <input type="button" class="btn-sm btn-primary add hidden" id="btnAdd" value="agregar">
                                                <?php endif;?>
                                            </td>
                                        </tr>               
                                 </tbody>
                                 </table>
                      </div>
            </div>
        </div>
    </div>
</div>
<?php if(count($partidas)>0){?>
<div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
            <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover" id="dataTables">
                                    <thead>
                                        <tr>
                                            <th> Mov </th>
                                            <th> Componente <br/>Secundario</th>
                                            <th> Producto </th>
                                            <th> Unidad </th>
                                            <th> Cantidad </th>
                                            <th> Color </th>
                                            <th> Total Piezas </th>
                                            <th> Estado </th>
                                            <th> Color </th>
                                            <th> Copiar a:</th>
                                            <th> Eliminar </th>
                                        </tr>
                                    </thead>
                                  <tbody>
                                    <?php foreach ($partidas as $kp): 
                                        $color = '';if(trim($kp->STATUS) == 'Eliminado'){ $color="style='background-color:#f33737'";}

                                        ?>
                                       <tr class="odd gradeX color" <?php echo $color?>>
                                            <td><?php echo $kp->MOV?></td>
                                            <td><?php echo $kp->COMPS?></td>
                                            <td><?php echo $kp->PROD?></td>
                                            <td><?php echo $kp->UNIDAD?></td>
                                            <td><?php echo $kp->CANT?></td>
                                            <td><?php echo $kp->COLOR?></td>
                                            <td><?php echo $kp->PIEZAS?></td>
                                            <td><?php echo $kp->STATUS?></td>
                                            <td><?php echo $kp->COLOR?></td>
                                            <td><select class="cpa" id="<?php echo $kp->ID_AM?>">
                                                <option value="none">Copiar a:</option>
                                                <?php foreach($compA as $ca):?>
                                                    <option value="<?php echo $ca->ID_COMP?>"><?php echo $ca->ETIQUETA.'('.$ca->TIPO.')'?></option>
                                                <?php endforeach;?>
                                            </select>&nbsp;&nbsp;&nbsp;<input type="button" name="" class="btn-sm btn-success cpLin" base="<?php echo $kp->ID_AM?>" value="&#x23f5;">
                                        </td>
                                            <td><input type="button" value="Eliminar" class="execMov" tipo="del" idMov="<?php echo $kp->ID_AM?>"></td>
                                        </tr>
                                    <?php endforeach ?>               
                                    </tbody>
                                </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php }?>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script type="text/javascript">  

    $(".prod").change(function(){
        $("#btnAdd").removeClass("hidden")
    })

    $(".compS").change(function(){
        var tipo = $(".tip").val()
        var det = $(".detalle")
        var a = $(".alm").val()
        var cp = $(".compP").val()
        var cs = $(this).val()
        if(tipo == 's'){
            det.addClass("hidden")
            //$.alert("es salida a"+a+":compp:"+cp+":comps:"+cs)
            window.open("index.wms.php?action=wms_menu&opc=newMovv2:t:s:a:"+a+":compp:"+cp+":comps:"+cs, "_self")
        }
    })

    $("#prod").autocomplete({
        source: "index.wms.php?producto=1",
        minLength: 2,
        select: function(event, ui){
        }
    })

    $(document).ready(function (){  
        //or if you want to be more efficient and use less characters, chain it
        $('#selcomps').focus().select()
    });

    var mov=<?php echo $mov==''? "'nuevo'":$mov?>

    $(".control").change(function(){
        var vtip = $(".tip").val()
        var alm = $(".alm").val()
        var compP = $(".compP").val()
        var compS = $(".compS").val()
        var almv = document.getElementById('almv')
        var compPv = document.getElementById('compPv')
        var compSv = document.getElementById('compSv')
        var button = document.getElementById('btnAdd')
        
        if(vtip != 'none'){
            almv.classList.remove('hidden')
        }else{
            almv.classList.add('hidden')
            compPv.classList.add('hidden')
            compSv.classList.add('hidden')
            button.classList.add("hidden")
        }
        
        if(vtip != 'none' && alm != 'none' && compP =='none'){
            window.open('index.wms.php?action=wms_menu&opc=newMovv2:t:'+vtip+':a:'+alm+':compp:', '_self')            
        }else{
            //compSv.classList.add('hidden')   
            button.classList.add("hidden")
        }
/*
        if(vtip != 'none' && alm != 'none' && compP != 'none'){
            $.alert("entra 2")
            compPv.classList.remove('hidden')
        }else{
            //compSv.classList.add('hidden')   
            button.classList.add("hidden")
        }
*/

        if(vtip !='none' && alm!='none' && compP != 'none' && compS=='none'){
            window.open('index.wms.php?action=wms_menu&opc=newMovv2:t:'+vtip+':a:'+alm+':compp:'+compP, '_self')            
            compSv.classList.remove('hidden')
        }

        if(vtip !='none' && alm!='none' && compP != 'none' && compS!='none'){
            button.classList.remove("hidden")
        }
    })

    $(".cpLin").click(function(){
        var base = $(this).attr('base')
        var cs = $("#"+base).val()
        if(cs == 'none'){
            $.alert('Seleccione un componente secundario (Tarima)')
            return false;
        }
        $.alert('Copiar linea' + base + ' cambia el componente ' + cs)
        $.ajax({
            url:'index.wms.php',
            type:'post',
            dataType:'json',
            data:{cpLin:1,base, cs},
            success:function(data){
                $.alert(data.msg)
                window.open('index.wms.php?action=wms_menu&opc=ediMov:'+ data.mov, "_self")
            },
            error:function(data){
                $.alert(data.msg)
                location.reload(true)
            }
        })
    })

    $(".add").click(function(){
        var tipo = $(".tip").val();
        var alm = $(".alm").val();
        var compP = $(".compP").val();
        var compS = $(".compS").val();
        var prod = $(".prod").val();
        var uni = $(".uni").val();
        var cant = $(".cant").val();
        var col = $(".col").val();
        var pza = document.getElementById("totPzas").innerHTML;
        $.ajax({
            url:'index.wms.php',
            type:'post',
            dataType:'json',
            data:{valProd:1, prod},
            success:function(data){
                if(data.val=='ok'){
                    $.ajax({
                        url:'index.wms.php',
                        type:'post',
                        dataType:'json',
                        data:{addMov:1, tipo, alm, compP, compS, prod:data.prod, uni, cant, col, mov, pza},
                        success:function(data){
                            window.open('index.wms.php?action=wms_menu&opc=ediMov:'+ data.mov, "_self")
                        }
                    })
                }
            },
            error:function(){

            }


        })
    })

    $(".total").change(function(){
        actualiza()
    })

    function actualiza(){
        var cant = $(".cant").val();
        var fact = $(".uni option:selected").attr('fact')
        var totPzas = '';
        if($.isNumeric(fact) && $.isNumeric(cant)){
            totPzas = cant * fact    
            document.getElementById('totPzas').innerHTML=totPzas
        }
    }

    $(".execMov").click(function(){
        var idMov = $(this).attr('idMov')
        var tp = $(this).attr('tipo')
        var mensaje = ""
        if(tp == 'end'){
            mensaje = "Desea finalizar el Movimiento? ya no se podra agregar o quitar lineas?";
            msj = 'Finalizar '
        }else{
            mensaje = "Desea eliminar el movimiento del Almacen?"
            msj = 'Eliminar '
        }
        $.confirm({
            title: msj + ' Movimiento',
            content: mensaje,
            buttons:{
                Si: function(){
                    $.ajax({
                        url:'index.wms.php',
                        type:'post',
                        dataType:'json',
                        data:{delMov:idMov, tp}, 
                        success:function(data){
                            $.alert(data.msg)
                            setTimeout(function(){
                                location.reload(true)
                            })
                        }
                    },10000 )
                },
                No:function(){
                   return;
                }
            }
        });
    })

    $(".addMov").click(function(){
        window.open('index.wms.php?action=wms_menu&opc=newMov', "_self")
    })


</script>