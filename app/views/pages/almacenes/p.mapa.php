<br/><br/>
<!--<style type="text/css">
        .marked {
        background-color: yellow;
        border: 3px red solid;
        }
</style>-->
<?php $a=0;  foreach ($infoA1['datos'] as $ka){ if($ka->STATUS == '7'){$a++;}}
            foreach ($infoA1['sec'] as $sa){if($sa->STATUS == '7'){$a++;}}           
?>         
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           <font size="5px"> Mapa del Almacen <?php echo $param?></font> 
                        </div>
                           <div class="panel-body">
                                <p><label>Para ver el contenido de la tarima colocar el cursor sobre la etiqueta.</label></p>
                                <p><label>Para ingresar por Linea dar click en la primer columna.</label></p>
                                <p><label>Para ingresar por Tarima dar click en la etiqueta.</label></p>
                                <p id="can" class="<?php echo ($a>0)? '':'hidden'?>" ><label >Cancelar Reubicación: </label>&nbsp;&nbsp;<input type="button" value="cancelar" class="canReu"></p>
                                
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <?php $t=0; for($i=0; $i <=$infoA1['tarimas'] ; $i++):?>
                                                <th><?php echo $t==0? 'Pasillo | Tarima':$t; $t++ ?></th>
                                            <?php endfor; ?>
                                        </tr>
                                    </thead>
                                  <tbody>
                                    <?php foreach ($infoA1['datos'] as $k): ?>         
                                        <?php if(!empty($k->LETRA)): 
                                            $colorL = '';
                                            if($k->STATUS == '7'){
                                                $colorL= "style=background-color:#00E1FE;";
                                            }elseif($k->STATUS >= 3 and $k->STATUS <= 6){
                                                $colorL= "style=background-color:#A69A74;";
                                            }
                                        ?>
                                            <tr>
                                                <td class="odd gradeX exe compp" t="l" idc="<?php echo $k->ID_COMP?>" desc="<?php echo $k->ETIQUETA?>" tar="" <?php echo $colorL?> id="<?php echo $k->ID_COMP?>"> <?php echo $k->ETIQUETA?></td>

                                                <?php foreach ($infoA1['sec'] as $sec):?>
                                                    <?php if($sec->COMPP == $k->ID_COMP):
                                                        $color = '';
                                                        if($sec->DISP == 'si'){
                                                            $color ="style='background-color:lightblue';";
                                                        }else{
                                                            $color ="style='background-color:#FFE0CA';";
                                                        }
                                                        if($sec->STATUS == 7){$color = "style='background-color:#00E1FE'";}elseif($sec->STATUS >= 3 and $sec->STATUS<=6){$color= "style=background-color:#A69A74;";}
                                                    ?>
                                                        <td title="" class="odd gradeX info exe" t="t" idc="<?php echo $sec->ID_COMP?>" desc="<?php echo $sec->ETI?>" <?php echo $color ?> dis="<?php echo $sec->DISP?>" id="<?php echo $sec->ID_COMP?>"> <?php echo $sec->ETI.'('.$sec->EXIS.')'?> </td>
                                                    <?php endif;?>
                                                <?php endforeach;?>

                                            </tr>               
                                        <?php endif;?>
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
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script type="text/javascript">  
    
    var alm = <?php echo "'".$param."'"?>;
    var titulo = ''; var tipo = ''; var tarDisp = 0;
    $(".info").mouseover(function(){
        var contenido = ''
        var comp = $(this)
        var comps = $(this).attr('idc')
        var dis = $(this).attr('dis')
        if(dis == 'si'){
            return false
        }
        $.ajax({
            url:'index.wms.php',
            type:'post',
            dataType:'json',
            data:{prods:comps},
            success:function(data){
                for(const [key, value] of Object.entries(data.datos)){
                    for(const [k, val ] of Object.entries(value)){
                        if(k == 'INTELISIS'){var prod = val}
                        if(k == 'DISPONIBLE'){var disp = val}
                        if(k == 'CANT'){var cant = val}
                        if(k == 'UNIDAD'){var uni = val}
                        if(k == 'PIEZAS'){var pzas = val}
                        if(k == 'PIEZAS_SAL'){var pzaSal = val}
                        if(k == 'SALIDAS'){var salidas = val}
                        if(k == 'PIEZAS_SURT'){var surtidas = val}
                    }
                        var totSal = parseFloat(pzaSal)+ parseFloat(surtidas);
                    contenido += cant + ' ' + uni  + ' ' + prod + 'Salidas: '+ totSal + ', Disponibles: ' + disp + ' \n'
                }
                comp.prop('title', contenido)
            },
            error:function(){
            }
        })
    })

    $(document).ready(function(){
        disp()
    })

    $(".canReu").click(function(){
        var idc='a'
        $.ajax({
            url:'index.wms.php',
            type:'post',
            dataType:'json',
            data:{reuMap:idc, opc:9},
            success:function(data){
                if(data.status=='c'){
                    $.alert(data.msg)
                    if(data.tipo == 1){
                        $("#"+data.idc).css("background-color", "#FFE0CA")
                        document.getElementById("can").classList.add('hidden')
                    }else{
                        $("#"+data.idc).css("background-color", "")
                        document.getElementById("can").classList.add('hidden')
                    }
                }
            },
            error:function(error){
            }
        })
    })

    function disp(){
        $(".compp").each(function(){
            var idc = $(this).attr('idc')
            var lin = $(this)
            $.ajax({
                url:'index.wms.php',
                type:'post',
                dataType:'json',
                data:{dispLin:idc},
                success:function(data){
                    if(data.status == 'ok'){
                        lin.attr("tar", data.disp)
                    }
                },
                error:function(){

                }
            })
        })
    }

    $(".exe").mousedown(function(e){
        if(e.which == 3){
            var tar =$(this)
            var idc = $(this).attr('idc')
            var desc = $(this).attr('desc')
            var t = $(this).attr('t')
            if(t == 'l'){
                tarDisp = $(this).attr('tar')
                titulo = 'Cambio de uso del componente'
                tituloR = 'Cambio de uso de la tarima '
                tipo = 'Linea'
                xtar = '<br/><br/>Cajas por tarima <input type="text" size="5" class="ft">'+
                        '<br/><br/> Tarimas disponibles = ' + tarDisp;
                disp = 'si'
            }else{
                tarDisp = 1
                titulo = 'Entrada al almacen por tarima.'
                tituloR = 'Cambio de uso de la tarima '
                tipo = 'Tarima'
                xtar = '<input type="hidden" value="1" class="ft">'
                disp = $(this).attr('dis')
            }
            if(disp == 'si'){
                $.confirm({
                    columnClass: 'col-md-8',
                    title:tituloR,
                    content:'Cambio de Uso' +
                    '<br/> Cambiar el uso de' + tipo + ': '+ desc +
                    '<br/> '+ tipo +':' + desc +
                    '<br/><br/><select class="uso" >'+
                        '<option value="">Seleccione un Uso</value>'+
                        '<option value="D">Devoluciones</value>'+
                        '<option value="R">Mercancia a Reparar</value>'+
                        '<option value="E">Empaque</value>'+
                        '<option value="I">Inventario de Tarimas</value>'+
                        '<option value="P">Productos (Se marca como disponible)</value>'+
                    '</select>'+
                    '<br/><br/> <b>Al cambiar el uso, no se contempla como disponible en el almacen</b>'
                    ,
                    buttons:{
                        si:{
                        text:'Si',
                        keys:['enter'],
                        btnClass:'btn-green',
                            action:function(){
                                var uso = this.$content.find('.uso').val()
                                if(uso == ''){
                                    $.alert("Seleccione un valor, por favor.")
                                    return false
                                }
                                $.ajax({
                                    url:'index.wms.php',
                                    type:'post',
                                    dataType:'json',
                                    data:{usoComp:idc, opc:uso},
                                    success:function(data){
                                        if(data.status=='ok'){
                                            tar.css("background-color","#A69A74")
                                            //document.getElementById("can").classList.remove('hidden')
                                        }else{
                                            $.alert(data.msg)
                                        }
                                    }, 
                                    error:function(){
                                        $.alert('Ocurrio un error, favor de actualizar, si persiste comunicarse con sistemas 55 50553392')
                                    }
                                })
                            }
                        },
                        cancelar:{
                        text:'No',
                        keys:['esc'],
                        btnClass:'btn-red',
                        action:function(){
                        }
                        },
                        eliminar:{
                            text:'Eliminar Movimientos',
                            keys:['e'],
                            btnClass:'btn-orange',
                            action:function(){
                                alert('Eliminacion de los movimientos del componente' + idc)
                                $.ajax({
                                    url:'index.wms.php',
                                    type:'post',
                                    dataType:'json',
                                    data:{delMovs:idc, tipo:'comp'},
                                    success:function(data){
                                        
                                    }, 
                                    error:function(){
                                        $.alert('Ocurrio un error, favor de actualizar, si persiste comunicarse con sistemas 55 50553392')
                                    }
                                })
                            }
                        },
                    },
                });
            }else{
                //$.alert("Para cambiar el uso el componente debe estar vacio.")
                $.confirm({
                    columnClass: 'col-md-8',
                    title: 'Eliminacion de Movimientos',
                    content:'Eliminar Movimientos del componente'
                    ,
                    buttons:{
                        cancelar:{
                        text:'No',
                        keys:['esc'],
                        btnClass:'btn-red',
                        action:function(){
                        }
                        },
                        eliminar:{
                            text:'Eliminar Movimientos',
                            keys:['e'],
                            btnClass:'btn-orange',
                            action:function(){
                                alert('Eliminacion de los movimientos del componente' + idc)
                                $.ajax({
                                    url:'index.wms.php',
                                    type:'post',
                                    dataType:'json',
                                    data:{delMovs:idc, tipo:'comp'},
                                    success:function(data){
                                        
                                    }, 
                                    error:function(){
                                        $.alert('Ocurrio un error, favor de actualizar, si persiste comunicarse con sistemas 55 50553392')
                                    }
                                })
                            }
                        },
                    },
                });
            }
        }
    })

    $(".exe").click(function(){
        var tar =$(this)
        var idc = $(this).attr('idc')
        var desc = $(this).attr('desc')
        var t = $(this).attr('t')
        if(t == 'l'){
            tarDisp = $(this).attr('tar')
            titulo = 'Entrada al almacen por linea.'
            tituloR = 'Reubicacion por linea'
            tipo = 'Linea'
            xtar = '<br/><br/>Cajas por tarima <input type="text" size="5" class="ft">'+
                    '<br/><br/> Tarimas disponibles = ' + tarDisp;
            disp = 'si'
        }else{
            tarDisp = 1
            titulo = 'Entrada al almacen por tarima.'
            tituloR = 'Reubicacion por tarima'
            tipo = 'Tarima'
            xtar = '<input type="hidden" value="1" class="ft">'
            disp = $(this).attr('dis')
        }
        if(tarDisp <= 0 || disp =='no'){
            $.ajax({
                url:'',
                type:'post',
                dataType:'json',
                data:{reuMap:idc, opc:1},
                success:function(data){
                    if(data.status == 'no'){
                        $.alert("Existe un componente pendiente por copiar. ")
                        return false
                    }else{
                        $.confirm({
                            columnClass: 'col-md-8',
                            title:tituloR,
                            content:'Reubicacion de producto' +
                            '<br/> Desea reubicar los productos de la tarima ' + tipo + ': '+ desc +
                            '<br/> Almacen: ' + alm +
                            '<br/> '+ tipo +':' + desc +
                            '<br/> Origen: <input type="checkbox" value="'+idc+'" class="marca">'+
                            '<br/> <b>Se marcara como origen y no se podra seleccionar otro origen hasta terminar la reubicacion</b>'
                            ,
                            buttons:{
                                ingresar:{
                                text:'Si',
                                keys:['enter'],
                                btnClass:'btn-green',
                                    action:function(){
                                        var marca = this.$content.find('.marca')
                                        if(marca.prop('checked')){
                                            $.ajax({
                                                url:'index.wms.php',
                                                type:'post',
                                                dataType:'json',
                                                data:{reuMap:idc, opc:0},
                                                success:function(data){
                                                    if(data.status=='ok'){
                                                        tar.css("background-color","#00E1FE")
                                                        document.getElementById("can").classList.remove('hidden')
                                                    }else{
                                                        $.alert(data.msg)
                                                    }
                                                }, 
                                                error:function(){
                                                    $.alert('Ocurrio un error, favor de actualizar, si persiste comunicarse con sistemas 55 50553392')
                                                }
                                            })
                                        }else{
                                            $.alert('Seleccione una cantidad valida')
                                            return false
                                        }
                                    }
                                },
                                cancelar:{
                                text:'No',
                                keys:['esc'],
                                btnClass:'btn-red',
                                action:function(){
                                }
                                },
                            },
                        });
                    }
                }, 
                error:function(){
                }
            })            
        }else{
        $.confirm({
            columnClass: 'col-md-8',
            title:titulo,
            content:'Entrada al almacen' +
            '<br/> Se dara entrada a  la ' + tipo + ': '+ desc +
            '<br/> Almacen: ' + alm +
            '<br/> '+ tipo +':' + desc +
            '<br/>Seleccione el producto a ingresar: <input type="text" placeholder="Producto" class="prod chgProd" size="100">' + 
            '<br/><br/>Caja Master:'+
            /*' <select class="uni">'+
            <?php foreach($uni as $u):?>
               '<option value="<?php echo $u->ID_UNI?>" factor="<?php echo $u->FACTOR?>"><?php echo $u->FACTOR."-->".$u->DESC?></option>'+
            <?php endforeach;?>
            '</select>'+
            */
            '<input type="text" class="uni" size="5" >'+
            'Categoria: <select class="cat" >'+
                '<option value="0"> Categoria </option>' +
                '<option value="1"> Primera </option>' +
                '<option value="2"> Segunda </option>' +
                '<option value="3"> Tercera </option>' +
                '</select>'+
            '<br/><br/>Cantidad (Cajas): <input type="text" size="5" class="cant" >'+
             xtar +
            '<br/><br/> Piezas totales: <label class="pzas"></label>' 
            ,
            buttons:{
                pegar:{
                text:'Pegar',
                keys:['p, v'], 
                btnClass:'btn-blue',
                    action:function(){
                            $.ajax({
                                url:'index.wms.php',
                                type:'post',
                                dataType:'json',
                                data:{reuMap:idc, opc:5},
                                success:function(data){
                                    if(data.status == 'ok'){
                                        $.alert(data.msg)
                                        return false
                                    }
                                }, 
                                error:function(error){

                                }
                            })
                    }    
                },
                ingresar:{
                text:'Ingresar',
                keys:['enter'],
                btnClass:'btn-green',
                    action:function(){
                        var prod = this.$content.find('.prod').val()
                        var uni = this.$content.find('.uni').val()
                        var cant = this.$content.find('.cant').val()
                        var pzas = this.$content.find('.pzas').html()
                        var cat = this.$content.find('.cat').val()
                        var ft = this.$content.find('.ft').val()
                        if(!$.isNumeric(ft) || !$.isNumeric(cant) || !$.isNumeric(uni) || !$.isNumeric(pzas)){
                            $.alert("Coloque un número valido")
                            return false
                        }
                        if(cat == 0){
                            $.alert("Categoria no válida!!!")
                            return false   
                        }
                        if(!$.isNumeric(pzas)){
                            $.alert("Numero de piezas no válida!!!")
                            return false   
                        }
                        if( t=='l' && ((parseFloat(cant) / parseFloat(ft)) > parseFloat(tarDisp)) ){
                            this.$content.find('.ft').focus()
                            $.alert("Se necesitan mas tarimas des las diponibles cantidad: " + cant  + " ft "  + ft  + 'Disp ' + tarDisp)
                            return false
                        }
                        if($.isNumeric(cant)){
                            $.ajax({
                                url:'index.wms.php',
                                type:'post',
                                dataType:'json',
                                data:{ingMap:idc, prod, cant, uni, pzas, ft, t, cat},
                                success:function(data){
                                    if(data.status=='ok'){
                                        $.alert('Se ingresa el producto' + prod)
                                        ///cambiar color a rojo y actualizar la cantidad
                                        //tar.()
                                    }else{
                                        $.alert(data.msg)
                                    }
                                }, 
                                error:function(){
                                    $.alert('Ocurrio un error, favor de actualizar, si persiste comunicarse con sistemas 55 50553392')
                                }
                            })
                        }else{
                            $.alert('Seleccione una cantidad valida')
                            return false
                        }
                    }
                },
                cancelar:{
                text:'Cancelar',
                keys:['esc'],
                btnClass:'btn-red',
                action:function(){
                }
                },
            },
        });
        }
    })
    
    $("body").on("click",".prod", function(e){
        e.preventDefault();
        $(".chgProd").autocomplete({
            source: "index.wms.php?producto=1",
            minLength: 2,
            select: function(event, ui){
            }
        })

        $(".cant").change(function(){
            var cant = $(this).val()
            var fact = $(".uni").val()
            var piezas = cant * fact;
            $(".pzas").html(piezas)
        })
    });

    
   // $(".tarimas:button").addClass( "marked" );

</script>