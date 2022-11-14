<br /><br />
<style type="text/css">
    .num {
        width: 5em;
    }
</style>
<div class="row">
    <div class="col-lg-12">
            <div tyle="color: blue;"> 
                <br/>
                Fecha inicial:&nbsp;&nbsp;<input type="date" class="ini" value="<?php echo date('d/m/Y')?>" > Fecha Final:&nbsp;&nbsp;<input type="date" class="fin" value="<?php echo date('d/m/Y')?>" >&nbsp;&nbsp;<button class="btn-sm btn-info filtro" tipo='normal'>Ir</button>
                Todos <input type="button" value="Todos" class="btn-sm btn-primary filtro" tipo="all">
                &nbsp;&nbsp;&nbsp;&nbsp;
                    <select class="tipo">
                        <option value="a">Seleccione el tipo</option>
                        <option value="c">Cerradas</option>
                    </select>
                    &nbsp;&nbsp;&nbsp; <a  style="cursor:pointer;" class="xls">xls</a>
                <br/><br>
            </p>
            </div>
            <br/>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
            <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover" id="dataTables-monitorOCAll">
                                    <thead>
                                        <tr>
                                            <th> Ln</th>
                                            <th> Cliente </th>
                                            <th> Orden </th>
                                            <th> Fecha de Carga <br/> <font color="blue">Final</font> </th>
                                            <th> Cedis (Enviar A) </th>
                                            <th> Productos </th>
                                            <th> Piezas </th>
                                            <th> Documento </th>
                                            <th> Estado WMS<br/> <font color="blue">Intelisis</font></th>
                                            <th> Fecha Asigna <br/> <font color="brown">Final</font></th>
                                            <th> Fecha Almacen <br/><font color="green">Final</font></th>
                                            <th> Usuario </th>
                                            <th> Prioridad </th>
                                            <th> Trabajar </th>
                                            
                                        </tr>
                                    </thead>
                                  <tbody>
                                    <?php $ln=0; foreach ($ordenes as $ord): 
                                        $color='';$ln++;
                                        if($ord->ID_STATUS == 3){
                                            $color="style='background-color:#ddf8ff'";
                                        }elseif($ord->ID_STATUS == 8){
                                            $color="style='background-color:#ffb7b2'";
                                        }
                                        if($ord->NUM_PROD >0){
                                            $color="style='background-color:#ffbcc3'";
                                        }
                                        if(trim($ord->STA_INT) == 'CONCLUIDO'){
                                            $color="style='background-color:#afff65'";
                                        }
                                        if(trim($ord->STA_INT) == 'CANCELADO'){
                                            $color="style='background-color:#ff5726'";
                                        }

                                        ?>
                                       <tr class="odd gradeX" <?php echo $color?> id="lin_<?php echo $ln?>" title="<?php echo $ord->ID_INT?>">
                                            <td><?php echo $ln?></td>
                                            <input type="hidden" name="" class="orden" ord="<?php echo $ord->ID_ORD?>">
                                            <td><?php echo $ord->CLIENTE?><br/>
                                                <?php if($ord->LOGS > 0 or $ord->LOGS_DET > 0){?>
                                                    <label title="Tiene los siguientes movimientos" class="logs" ido="<?php echo $ord->ID_ORD?>">Movimientos </label> 
                                                <?php }?>
                                            </td>
                                            <td title="<?php echo $ord->ORDEN?>"><?php echo substr($ord->ORDEN, 0, 20) ?></td>
                                            <td><?php echo $ord->FECHA_CARGA?>
                                            <br/><font color="blue"><?php echo $ord->FECHA_CARGA_F?></font></td>
                                            <td><?php echo $ord->CEDIS?></td>
                                            <td align="right"><?php echo $ord->PRODUCTOS?></td>
                                            <td align="right"><?php echo number_format($ord->PIEZAS,0)?></td>
                                            

                                            <td><a class="imp" o="<?php echo $ord->ID_ORD?>"
                                                p="<?php echo $param?>" 
                                                cliente = "<?php echo $ord->CLIENTE?>" 
                                                cedis = "<?php echo $ord->CEDIS?>" 
                                                orig = "<?php echo $ord->ORDEN?>" 
                                                pedido = "<?php echo $ord->ARCHIVO?>">
                                                <?php echo $ord->ARCHIVO?>
                                                </a>
                                            </td>

                                            <td><b><?php echo $ord->STATUS?></b> <br/> <font color="blue"><?php echo $ord->STA_INT?></font></td>
                                            <td><?php echo $ord->FECHA_ASIGNA?>
                                            <br/><font color="brown"><?php echo $ord->FECHA_ASIGNA_F?></font></td>
                                            <td><?php echo $ord->FECHA_ALMACEN?><br/><font color="green"><?php echo $ord->FECHA_ALMACEN_F?></font></td>
                                            <td><?php echo $ord->USUARIO?></td>
                                            <td><?php echo $ord->PRIORIDAD?></td>
                                            <td>
                                                <br/>
                                                <?php if($lt==1){?>
                                                    <select class="tAsig" title="Limp Asi">
                                                        <option value="0" title="Limp As">Opciones:</option>
                                                        <option value="1" title="Limp A">Regresar a Asignación:</option>
                                                        <option value="2" title="Limp ">Limpia Asignación</option>
                                                    </select>
                                                <?php }?>
                                                <br/>
                                                <?php if($lt==2){?>
                                                <!--<a href="index.wms.php?action=detOrden&orden=<?php echo $ord->ID_ORD?>&t=p" target="popup" onclick="window.open(this.href, this.target, 'width=1600,height=600'); return false;">Productos de la Orden</a>-->
                                                    <select class="utilOdn" title="Limpia la Asignación de forma parcial o total,para que se vuelva a asignar." ln="<?php echo $ord->ID_ORD?>" name="utils">
                                                        <option value="0" title="Limpia la Asignación de forma parcial o total,para que se vuelva a asignar.">Opciones:</option>
                                                        <option value="2" title="Se regresa para ser asignada con todos los valores en 0">Regresa a 0 </option>
                                                        <option value="1" title="Se regresa para ser asignada con los valores actuales">Regresa asignación</option>
                                                    </select>
                                                <?php }?>
                                                <br/>
                                                <?php if($lt==3){?>
                                                <!--<a href="index.wms.php?action=detOrden&orden=<?php echo $ord->ID_ORD?>&t=s" target="popup" onclick="window.open(this.href, this.target, 'width=1600,height=600'); return false;" class="marcar" lin="<?php echo $ln?>">Surtir Orden</a>-->
                                                    <select class="utilOdn" title="Regresa o Cancela el surtido" ln="<?php echo $ord->ID_ORD?>" name="utils">
                                                        <option value="0" title="Limp As">Opciones:</option>
                                                        <option value="3" title="Cancela el surtido y lo regresa a la pantalla de asignaciones.">Regresar a la asignación:</option>
                                                        <option value="4" title="Regresa la orden al area de surtido para que se retrabaje.">Regresar al Surtido:</option>
                                                        <option value="5" title="Cancela el surtido de la Orden y el producto regresa a su ubicacion original">Cancelar surtido (error Intelisis)</option>
                                                        <option value="6" title="Cancela el surtido de la orden, los productos se envian a una ubicacion de reacomodo" >Cancela surtido (reacomodo)</option>
                                                    </select>
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
</div>

<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script type="text/javascript">

    $(".xls").click(function(){
        var urls = window.location.href
        $.ajax({
            url:urls+':xls',
            type:'get',
            success:function(response){
                setTimeout(function(){
                    window.open("../Reportes_Almacen/Ordenes Cerradas.xlsx", 'download')
                }, 2000);
            }
        })
    })
/*
    $(".imp").click(function(){
        var param = $(this).attr('p')
        var ord = $(this).attr('o')
        $.alert("Impresion de la orden" + ord + " cedis " + param)
        //window.open("index.wms.php?action=impOrden&orden="+ord+"&t=s&param="+param, "download")

    })
*/
    
    $(".imp").click(function(){
        var ord =$(this).attr('o')
        var param = ''
        var cte = $(this).attr('cliente')
        var cds = $(this).attr('cedis')
        var org = $(this).attr('orig')
        var ped = $(this).attr('pedido')
        var dir = "index.wms.php?action=impOrden&orden="+ord+"&t=s&param="+param
        $.ajax({
            url:dir,
            type:'get',
            success:function(response){
                //'Picking list '.$cabecera->CLIENTE.' '.$cabecera->CEDIS.' '.$cabecera->ORDEN.' '.$cabecera->ARCHIVO.'.pdf'
                setTimeout(function(){
                    //window.open("../Reportes_Almacen/Ordenes Cerradas.xlsx", 'download')
                    window.open("..//Reportes_Almacen//Picking//Picking list "+ cte + ' ' + cds +' '+ org +' '+ ped + '.pdf', 'download')
                }, 2000);
            }
        })
    })

    $(".utilOdn").change(function(){
        var t = $(this).val()
        var ln = $(this).attr('ln')
        var texto = $('.utilOdn option:selected').html()
        //var texto =  $('select[name="utils"] option:selected').text()
        //$.alert("Valor" + t + ' PARA LA LINEA ' + ln )
        $.confirm({
            title:'Utilidades de los Pedidos',
            content:'Desea ' + texto + ' el Pedido ? '+
            '<form action="" class="formName">' +
                '<br/><input type="text" placeholder="Observaciones" class="obs form-control" >'+
            '</form>',
            buttons:{
                formSubmit:{
                    text:'Aceptar',
                    btnClass: 'btn-primary',
                    //keys:[''],
                    action:function(){
                        var obs = this.$content.find('.obs').val();
                        $.ajax({
                            url:'index.wms.php',
                            type:'post',
                            dataType:'json',
                            data:{utilOdn:t, ln, obs},
                            success:function(data){
                                $.alert(data.msg)
                            }, 
                            error:function(){
                            }
                        })
                    }
                },
                cancelar:{
                    text:'Cancelar',
                    btnClass:'btn-red',
                    keys:['esc'],
                    action:function(){
                        return;
                    }
                }
            }
        })
    })

    $(".all").click(function(){
        window.open("index.wms.php?action=wms_menu&opc=oall", "_self")
    })
    

    $(".marcar").click(function(){
        var lin = $(this).attr("lin");
        var linea= document.getElementById("lin_" + lin);
        linea.style.background="#ffbcc3";
    })

    $(".filtro").click(function(){
        var ini = $(".ini").val()
        var fin = $(".fin").val()
        var sta = $(".status").val()
        var tipo= $(this).attr('tipo')
        var sub = $(".tipo").children("option:selected").val()
        window.open("index.wms.php?action=wms_menu&opc=o:"+ini+":"+fin+":"+sta+":"+tipo+":"+sub, "_self")
    })
    /*
    $(".marca").click(function(){
        var lin = $(this).attr("lin")
        document.getElementById("lin_"+lin).style.background='#DCF0F9';
    })
    */
    
    $(".logs").mouseover(function(){
        var ido = $(this).attr('ido')
        var titulo = ''
        var t = $(this)
        $.ajax({
            url:'index.wms.php',
            type:'post',
            dataType:'json',
            data:{log:1, ido, d:2 },
            success:function(data){
                if(data.logs > 0){
                    for(const [key, value] of Object.entries(data.datos)){
                        for (const [k, val] of Object.entries(value)){
                            console.log(k + ' valor: ' + val);
                            if(k == 'SUBTIPO'){var sub = val;}
                            if(k == 'USUARIO'){var usr = val;}
                            if(k == 'FECHA'){var fecha = val;}
                            if(k == 'OBS'){var obs = val;}
                        }
                        //alert(sub)
                        titulo += sub + " -- " +usr + " el " + fecha + " - "+ obs +"\n"
                    }
                }
                t.prop('title', titulo)
            },
            error:function(){

            }
        });
    });

        

</script>