<br /><br />
<style type="text/css">
    .num {
        width: 5em;
    }
</style>
<?php $pers=''; if(count($persona)>0){
        foreach ($persona as $per) {
            $pers=$per->NOMBRE; 
        }
    }
foreach($orden as $od){
            $status = $od->ID_STATUS;
        }
?>

<div class="row">
    <div class="col-lg-12">
        <div>Detalles del Archivo: <label><?php echo $cabecera->ARCHIVO?> </label><br/>Para el Cliente: <label><?php echo $cabecera->CLIENTE?></label>  <?php echo !empty($cabecera->ORDEN)? '<br/>Incluye las ordenes:<label>'.$cabecera->ORDEN.'</label>':''?></label>
            <p> <?php if(!empty($cabecera->CEDIS)):
                $cedis= explode(":", trim($cabecera->CEDIS));?>
                <label>Cedis:</label>
                    <?php for($i=0; $i< count($cedis); $i++):?>
                        <a class="fCedis" c="<?php echo trim($cedis[$i])?>"><?php echo $cedis[$i] ?> </a>
                        <label>, </label>
                    <?php endfor; ?>
                <?php endif;?>
                <a class="fCedis" c="">TODOS </a>
            </p>
            <p><input type="text" placeholder="Persona Asignada" class="asignar" size="80" cedis="<?php echo (isset($param)? $param:'todos')?>" value="<?php echo $pers?>"></p>
            <?php echo (!empty(@$param))? '<b>Cedis: '.$param.'</b><br/>':''?>
            <input type="button" name="" value="Imprimir" class="btn-sm btn-primary imp" p="<?php echo $param?>"> &nbsp;&nbsp;&nbsp;
            <input type="button" value="<?php echo $status==7? 'Finalizado':'Finalizar' ?>" <?php echo $status==7? 'disabled':'' ?> cedis="<?php echo $cabecera->CEDIS?>" class="finSurt" >
            &nbsp;&nbsp;&nbsp;<input type="checkbox" <?php echo $cabecera->CAJAS ==0? 'checked':''?> class="surtAuto"> <label>Surtido Automatico </label>
        </div>
            <br/>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
            <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover" id="dataTables-ordSurt">
                                    <thead>
                                        <tr>
                                            <th width="3"> Ln </th>
                                            <th > UPC</th>
                                            <th > Asignado / <br/> <font color="purple"> Original </font> </th>
                                            <th > Cantidad / <br/> Asignado</th>
                                            <th > Cajas </th>
                                            <th > Piezas por Caja</th>
                                            <th > <b>Piezas Surtidas</b> <br/> <font color="blue">Pendientes</font> </th>
                                            <th > Piezas x Caja </th>
                                            <th > Etiqueta </th>
                                        </tr>
                                    </thead>
                                  <tbody>
                                    <?php $ln=0; foreach ($orden as $ord): 
                                        $color=''; $ln++;
                                        if(empty($ord->DESCR)){
                                            $color = "style='background-color: #FFF7C6;'";
                                        }
                                        if($ord->ASIG == 0){
                                            $color = "style='background-color: #f3e5ff;'";
                                        }
                                        //$color = '';if(trim($kp->STATUS) == 'Eliminado'){ $color="style='background-color:#f33737'";}
                                        ?>
                                       <tr class="odd gradeX color" <?php echo $color?>>
                                            <td><?php echo $ln?></td>
                                            <td><?php echo '<font color="blue">'.$ord->UPC.'<br/></font> <br/><font color="green">'.$ord->ITEM.'</font>'?></td>

                                            <td><input type="hidden" value="<?php echo $ord->PROD.'|'.$id_o?>" class="asignado"><b><?php echo $ord->PROD?><br/><p id="<?php echo $ord->PROD.'|'.$id_o?>"></p></b>

                                            <br/> <?php if($ord->PROD != $ord->PROD_SKU):?>
                                                <font color="purple" > <?php echo $ord->PROD_SKU ?></font>
                                                <?php endif;?>
                                            </td>
                                            <td><?php echo $ord->PZAS.' / '.$ord->ASIG?><br/>
                                                
                                                <label id="ocu_<?php echo $ln?>" ln="<?php echo $ln?>" class="ocultar" hidden="true"><font color ="blue">+/-</font><br/><br/></label>

                                                <label class="comp verComp vc<?php echo $ln?>" mod="<?php echo $ord->PROD?>" ln="<?php echo $ln?>" ordd="<?php echo $ord->ID_ORDD?>" pnd="<?php echo ($ord->ASIG-$ord->PZAS_SUR)?>" srt="<?php echo ($ord->PZAS_SUR)?>">
                                                    <font color="red"> + / - </font>
                                                </label>
                                                <label class="infoComp<?php echo $ln?>"></label>

                                            </td>
                                            <td><?php echo $ord->PZAS/$ord->CAJAS ?>
                                            <td><?php echo $ord->CAJAS?></td>
                                            <td align="center">
                                                    <text id="act_<?php echo $ord->ID_ORDD?>">
                                                        <b><?php echo $ord->PZAS_SUR ?></b> / 
                                                        <font color="blue"><?php echo ($ord->ASIG-$ord->PZAS_SUR)?></font>
                                                    </text>
                                                <p class="ordd" id="surt_<?php echo $ln?>" ordd="<?php echo $ord->ID_ORDD?>" ln="<?php echo $ln?>" mod="<?php echo $ord->PROD?>"></p> 
                                            </td>
                                            <td align="center"><input type="text" size="10" class="factor revpxc" ordd="<?php echo $ord->ID_ORDD?>" t="u" id="u_<?php echo $ord->ID_ORDD?>"><br/><text id="<?php echo $ord->ID_ORDD?>"><?php echo $ord->UNIDAD?></text></td>
                                            <td><input type="text" size="10" class="factor" t="e" ordd="<?php echo $ord->ID_ORDD?>" placeholder="<?php echo $ord->ETIQUETA?>"></td>
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
    var ord = <?php echo $id_o?>;

    $(".surtAuto").change(function(){
        //var tipo = $(this).prop('checked')
        if($(this).prop('checked')  == true){
            var tipo= 1;
        }else{
            var tipo =2;
        }
        $.ajax({
            url:'index.wms.php',
            type:'post',
            dataType:'json',
            data:{surtAuto:ord, tipo},
            success:function(data){
                if(data.status == 'ok'){
                    $.alert(data.msg)
                }
            },
            error:function(){

            }
        })
    })

    $(document).ready(function(){
        $(".asignado").each(function(){
            var valor = $(this).val()
            var label = ''
            //console.log(valor)
            $.ajax({
                url:'index.wms.php',
                type:'post',
                dataType:'json', 
                data:{pres:valor}, 
                success:function(data){
                    for (const [key, value] of Object.entries(data.datos)){
                        for (const [k, val] of Object.entries(value)){
                            if(k == 'NUEVO'){ var nuevo = val;}
                            if(k == 'CANT'){ var cantidad = val;}
                            if(k == 'IDPROD'){ var idProd = val;}
                        }
                        label += '<br/> Asignado <a class="posicion" prod="' + idProd + '" nom="'+ idProd +'">' + nuevo + '</a> : ' + cantidad + '<a class="movs" prod="'+nuevo+'"> xls </a>';
                    }
                    document.getElementById(valor).innerHTML = label
                }, 
                error:function(){

                }
            })
        })    
    })
    

    $(".factor").change(function(){
        var ordd= $(this).attr('ordd')
        var uni = $(this).val()
        var txt = $("#"+ordd)
        var t = $(this).attr("t")
        $.ajax({
            url:'index.wms.php',
            type:'post',
            dataType:'json',
            data:{facOrdd:ordd, uni, t},
            success:function (data) {
                txt.html(uni)
            }, 
            error:function(error){

            }
        })
    })

    $(".finSurt").click(function(){
        var cedis = $(this).attr('cedis')
        $.ajax({
            url:'index.wms.php',
            type:'post',
            dataType:'json',
            data:{finSurt:ord, cedis},
            success:function(data){
                $.alert(data.msg)
            },
            error:function(error){

            }
        })
    })

    $(".asignar").change(function(){
        var nombre = $(this).val()
        var cedis = $(this).attr('cedis')
        $.ajax({
            url:'index.wms.php',
            type:'post',
            dataType:'json',
            data:{asiSurt:ord, cedis, nombre},
            success:function(data){

            },
            error:function(error){

            }
        })
    })  

    $(".imp").click(function(){
        var param = $(this).attr('p')
        //$.alert("Impresion de la orden" + ord + " cedis " + param)
        window.open("index.wms.php?action=impOrden&orden="+ord+"&t=s&param="+param, "_blank")
    })

    $(".fCedis").click(function(){
        var cedi = $(this).attr('c')
        window.open("index.wms.php?action=detOrden&orden="+ord+"&t=s&param="+cedi, '_self')
    })
    
    $(".verComp").click(function(){
        var mod = $(this).attr("mod")
        var ln = $(this).attr("ln")
        var ordd = $(this).attr('ordd')
        var comp = $(this)
        var pos = document.getElementById("surt_"+ln)
        var pnd = $(this).attr('pnd')
        pos.innerHTML=''
        comp.empty()
        revisaComp(mod, ln, ordd, comp, pos, pnd)   
    })

    function revisaComp(mod, ln, ordd, comp, pos, pnd){
        $('#ocu_'+ln).prop('hidden', false);
        var compV = $('.infoComp'+ln) 
        $.ajax({
        url:'index.wms.php',
        type:'post',
        dataType:'json',
        data:{comPro:mod, ordd},
        success:function(data){
            if(data.status== 'ok'){
                for(const [key, value] of Object.entries(data.datos)){
                    for(const[k, val] of Object.entries(value)){
                        if(k == 'COMPP'){var compp=val}
                        if(k == 'COMPS'){var comps=val}
                        if(k == 'ID_COMPS'){var id_comp=val} 
                        if(k == 'PIEZAS_A'){var pzas= val}
                        if(k == 'PRIMARIO'){var prim= val}
                        if(k == 'SECUNDARIO'){var secu= val}
                        if(k == 'ID_AM'){var mov= val}
                    }
                    compV.prop('id', ln+'_'+id_comp)
                    compV.prop('title', compp + '_:_' + comps)
                    //compV.removeClass('verComp')
                    compV.append('<br/><text class="Lit "><b>Linea: </b>' +prim+'</text>')
                    compV.append('<br/><text class="Lit "><b>Tarima: </b>' +secu+ '</text>')
                    compV.append('<br/><text class="Lit "><b>Cantidad:</b> <a class="surte" value="100" comps="'+id_comp+'" cant="'+pzas+'" ordd="'+ordd+'" mov="'+mov+'" pnd="'+pnd+'" ><font color="red">'+pzas+'</font></a></text>')

                    }
                    if(data.posiciones.length > 0){
                        for(const [key, value] of Object.entries(data.posiciones)){
                            for(const [k,val] of Object.entries(value)){
                                if(k=='LINEA'){var s_lin=val}
                                if(k=='TARIMA'){var s_tar=val}
                                if (k=='PIEZAS'){ var s_cant=val}    
                            }
                            pos.innerHTML+="<b>Lin: </b> " + s_lin
                            pos.innerHTML+="<b> Tar: </b> " + s_tar
                            pos.innerHTML+="<b> Pzas: </b> <font color='green'>" + s_cant +"</font><br/>"
                        }
                    }
                }else{
                    comp.text('Sin existencia')
                }
            }, 
            error:function(){
                comp.text("No se pudo leer la informacion, revise con soporte tecnico al 55-5055-3392")
            }
        })
    }

    $(document).ready(function(){
        //revisa()
        revisaSurt()
        revisaPxC()
    })

    function revisaSurt(){
         $(".ordd").each(function(){
            var mod = $(this).attr("mod")
            var ln = $(this).attr("ln")
            var ordd = $(this).attr('ordd')
            var comp = $(this)
            comp.removeClass('hidden')
            var pos = document.getElementById("surt_"+ln)
            pos.innerHTML=''
            $.ajax({
                url:'index.wms.php',
                type:'post',
                dataType:'json',
                data:{comPro:mod, ordd},
                success:function(data){
                    if(data.status== 'ok'){
                        if(data.posiciones.length > 0){
                            //$.alert('Posiciones del ' + ordd + ' : '+ data.posiciones.length)
                            for(const [key, value] of Object.entries(data.posiciones)){
                                for(const [k,val] of Object.entries(value)){
                                    if(k=='LINEA'){var s_lin=val}
                                    if(k=='TARIMA'){var s_tar=val}
                                    if (k=='PIEZAS'){ var s_cant=val}    
                                    if (k=='ID_MS'){ var movs=val}    
                                    if (k == 'CATEGORIA'){ var s_cat = val}
                                    if (k == 'PRODUCTO'){ var s_prod = val}

                                }
                                pos.innerHTML+="<b>Lin: </b> " + s_lin
                                pos.innerHTML+="<b> Tar: </b> " + s_tar
                                pos.innerHTML+="<b> Pzas: </b> <a class='liberar' id='ms_"+movs+"' npnd='' ln='"+ln+"'><font color='green'>" + s_cant +"</font></a>"
                                pos.innerHTML+="<b> " + s_prod + "<b/><br/>"
                                pos.innerHTML+="<b> Cat: </b> " + s_cat + "<br/>"
                            }
                        }
                    }else{
                        comp.text('Sin existencia')
                    }
                }, 
                error:function(){
                    comp.text("No se pudo leer la informacion, revise con soporte tecnico al 55-5055-3392")
                }
            })
        })
    }

    function revisaPxC(){
        $(".revpxc").each(function(){
            var val = $(this).val()
            var ordd = $(this).attr('ordd')
            if(val == ''){
                $.ajax({
                    url:'index.wms.php',
                    type:'post',
                    dataType:'json',
                    data:{pxc:ordd}, 
                    success:function(data){
                        if(data.sta == 'ok'){
                            //$(this).val(data.valor)
                            $("#u_"+ordd).val(data.valor)
                        }
                    }, 
                    error:function(error){
                    }
                })
            }
        })
    }

    $("body").on("click", ".liberar", function(e){
        e.preventDefault();
        var ln = $(this).attr('ln')
        var movs = $(this).attr('id')
        var p = $("#surt_"+ln)
        $.ajax({
            url:'index.wms.php',
            type:'post',
            dataType:'json',
            data:{liberar:movs}, 
            success:function(data){
                if(data.sta == 'ok'){
                    p.addClass("hidden")
                    $(this).attr('npnd', '1');
                    $.alert("Se ha liberado")
                }
            }, 
            error:function(error){
            }
        })
    })

/*
    function revisa(algo){    
        $(".comp").each(function(){
            var mod = $(this).attr("mod")
            var ln = $(this).attr("ln")
            var ordd = $(this).attr('ordd')
            var comp = $(this)
            var pos = document.getElementById("surt_"+ln)
            var pnd = $(this).attr('pnd')
            pos.innerHTML=''
            $("p").remove(".Lit")
            $.ajax({
                url:'index.wms.php',
                type:'post',
                dataType:'json',
                data:{comPro:mod, ordd},
                success:function(data){
                    if(data.status== 'ok'){
                        for(const [key, value] of Object.entries(data.datos)){
                            for(const[k, val] of Object.entries(value)){
                                if(k == 'COMPP'){var compp=val}
                                if(k == 'COMPS'){var comps=val}
                                if(k == 'ID_COMPS'){var id_comp=val} 
                                if(k == 'PIEZAS_A'){var pzas= val}
                                if(k == 'PRIMARIO'){var prim= val}
                                if(k == 'SECUNDARIO'){var secu= val}
                                if(k == 'ID_AM'){var mov= val}
                            }
                            comp.prop('id', ln+'_'+id_comp)
                            comp.prop('title', compp + '_:_' + comps)
                            comp.append('<br/><text class="Lit hidden"><b>Linea: </b>' +prim+'</text>')
                            comp.append('<br/><text class="Lit hidden"><b>Tarima: </b>' +secu+ '</text>')
                            comp.append('<br/><text class="Lit hidden"><b>Cantidad:</b> <a class="surte" value="100" comps="'+id_comp+'" cant="'+pzas+'" ordd="'+ordd+'" mov="'+mov+'" pnd="'+pnd+'" ><font color="red">'+pzas+'</font></a></text>')
                        }
                        if(data.posiciones.length > 0){
                            for(const [key, value] of Object.entries(data.posiciones)){
                                for(const [k,val] of Object.entries(value)){
                                    if(k=='LINEA'){var s_lin=val}
                                    if(k=='TARIMA'){var s_tar=val}
                                    if (k=='PIEZAS'){ var s_cant=val}    
                                }
                                pos.innerHTML+="<br/><b>Linea: </b> " + s_lin
                                pos.innerHTML+="<br/><b>Tarima: </b> " + s_tar
                                pos.innerHTML+="<br/><b>Piezas: </b> <font color='green'>" + s_cant +"</font>"
                            }
                        }
                    }else{
                        comp.text('Sin existencia')
                    }
                }, 
                error:function(){
                    comp.text("No se pudo leer la informacion, revise con soporte tecnico al 55-5055-3392")
                }
            })
        })
    }
*/

    $("body").on("click", ".surte", function(e){
        e.preventDefault();
        var ordd = $(this).attr('ordd');
        var comps = $(this).attr('comps');
        var mov = $(this).attr('mov')
        var pnd = $(this).attr('pnd')
        //if(pnd == 0){
        //    $.alert("El pedido esta surtido")
        //    return
        //}else{    
            $.ajax({
                url:'index.wms.php',
                type:'post',
                dataType:'json',
                data:{surte:mov, ordd, comps},
                success:function(data){
                    if(data.status=='ok'){
                        /// aqui ponemos los nuevos datos de las existencias
                        revisaSurt()
                        document.getElementById('act_'+ordd).innerHTML=(parseFloat(data.srt))+'\/<font color="blue">'+(parseFloat(data.pnd))+'</font>'
                        // restamos lo asignado a lo pendiente para que cuadre la informacion. 
                    }
                }, 
                error:function(){
                }
            })
        //}
    })

    $(".ocultar").click(function(){
        var ln = $(this).attr('ln')
        var comp = $(this).attr('comp')
        var info = $(".vc"+ln)//.prop('hidden', true)
        $(this).prop("hidden", true)
        info.empty()
        info.append('<font color="red">+ / -</font>')

    })

    $("body").on("click", ".posicion", function (e){
            e.preventDefault();
            var prod = $(this).attr('prod')
            var nombre = $(this).attr('nom')
            var info = "El producto <b>"+ nombre +"</b> se encuenta en: <br/><br/>"
            //info += "Posicion 1<br/>"
            //info += "Posicion 2<br/>"
            $.ajax({
                url:'index.wms.php',
                type:'post',
                dataType:'json',
                data:{posiciones:prod},
                success:function(data){
                    for(const [key, value] of Object.entries(data.datos)){
                        for(const[k,val] of Object.entries(value)){
                            if(k == 'LINEA'){var lin = val}
                            if(k == 'TARIMA'){var tar = val}
                            if(k == 'DISPONIBLE'){var disp = val}
                            if(k == 'CATEGORIA'){var cat = val}
                        }
                        info += lin + ':' + tar + ', piezas: '+ disp +':' + cat+ '<br/>'
                    }
                    if(data.status='ok'){
                        $.alert(info + "<br/><div><input type='hidden' value='test' class='btn-sm btn-success imprimir'></div>")
                    }
                },
                error:function(error){
                }
            })
    })
    
    $("body").on("click", ".movs", function (e){
         e.preventDefault();
            var prod = $(this).attr('prod')
            $.ajax({
                url:'index.wms.php',
                type:'post',
                dataType:'json', 
                data:{movsProd:prod},
                success:function(data){
                    window.open(data.completa, 'download')
                }, 
                error:function(){
                    $.alert('no encontre informacion')
                }
            })
        
    })
    
</script>
