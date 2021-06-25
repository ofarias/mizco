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
}?>
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
            <input type="button" name="" value="Imprimir" class="btn-sm btn-primary imp" p="<?php echo $param?>">
        </div>
            <br/>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
            <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover" id="dataTables-detOrd">
                                    <thead>
                                        <tr>
                                            <!--<th style="width:10%"> Cedis</th>-->
                                            <th > UPC</th>
                                            <th > Modelo</th>
                                            <th > Cantidad / <br/> Asignado</th>
                                            <th > Cajas </th>
                                            <th > Piezas por Caja</th>
                                            <th > <b>Piezas Surtidas</b> <br/> <font color="blue">Pendientes</font> </th>
                                            <th > Piezas x Caja </th>
                                            <th > Etiqueta </th>
                                            <th > Estado <br/> Finalizar</th>
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
                                            <!--<td><?php echo $ord->CEDIS?></td>-->
                                            <td><?php echo '<font color="blue">'.$ord->UPC.'<br/></font> <br/><font color="green">'.$ord->ITEM.'</font>'?></td>
                                            <td><?php echo $ord->PROD?>
                                            <br/> <font color="purple" > <?php echo $ord->PROD_SKU ?></font>
                                            </td>
                                            <td><?php echo $ord->PZAS.' / '.$ord->ASIG?><br/>
                                                
                                                <label id="ocu_<?php echo $ln?>" ln="<?php echo $ln?>" class="ocultar" hidden="true"><font color ="blue">+/-</font><br/><br/></label>

                                                <label class="comp verComp vc<?php echo $ln?>" mod="<?php echo $ord->PROD?>" ln="<?php echo $ln?>" ordd="<?php echo $ord->ID_ORDD?>" pnd="<?php echo ($ord->ASIG-$ord->PZAS_SUR)?>" srt="<?php echo ($ord->PZAS_SUR)?>"> <font color="red">+ / -</font></label>


                                            </td>
                                            <td><?php echo $ord->CAJAS?></td>
                                            <td><?php echo $ord->UNIDAD?></td>
                                            <!--<td><?php echo $ord->COLOR?></td>-->
                                            <td align="center">

                                                    <text id="act_<?php echo $ord->ID_ORDD?>">
                                                        <b><?php echo $ord->PZAS_SUR ?></b> / 
                                                        <font color="blue"><?php echo ($ord->ASIG-$ord->PZAS_SUR)?></font>
                                                    </text>

                                                <p id="surt_<?php echo $ln?>"></p> 
                                            </td>
                                            <td align="center"><input type="text" size="10" class="surtir" ><br/><?php echo $ord->CAJAS_SUR?></td>
                                            <td><input type="text" size="10" class="Etiqueta" ></td>
                                            <td><?php echo $ord->STATUS?>
                                            <br/>
                                                <a href="index.wms.php?action=detOrden&orden=<?php echo $ord->ID_ORD?>" target="popup" onclick="window.open(this.href, this.target, 'width=800,height=600'); return false;"> Finalizar</a>
                                            <br/><a >Informar</a></td>
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
    
    $(document).ready(function(){
        //revisa()
        revisaSurt()
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

    $(".ocultar").click(function(){
        var ln = $(this).attr('ln')
        var comp = $(this).attr('comp')
        var info = $(".vc"+ln)//.prop('hidden', true)
        $(this).prop("hidden", true)
        info.empty()
        info.append('<font color="red">+ / -</font>')

    })

    function revisaComp(mod, ln, ordd, comp, pos, pnd){
        $('#ocu_'+ln).prop('hidden', false);
        //$('.ocultar').append("<br/>")
        $.ajax({
        url:'index.wms.php',
        type:'post',
        dataType:'json',
        data:{comPro:mod, ordd},
        success:function(data){
            if(data.status== 'ok'){
                //comp.append('<label class="ocultar"><font color ="blue">+/-</font></label>')
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
                    comp.append('<br/><text class="Lit "><b>Linea: </b>' +prim+'</text>')
                    comp.append('<br/><text class="Lit "><b>Tarima: </b>' +secu+ '</text>')
                    comp.append('<br/><text class="Lit "><b>Cantidad:</b> <a class="surte" value="100" comps="'+id_comp+'" cant="'+pzas+'" ordd="'+ordd+'" mov="'+mov+'" pnd="'+pnd+'" ><font color="red">'+pzas+'</font></a></text>')
                            //comp.append('<hr size=20 color="red"/>')
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

    function revisaSurt(){
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
                        //for(const [key, value] of Object.entries(data.datos)){
                        //    for(const[k, val] of Object.entries(value)){
                        //        if(k == 'COMPP'){var compp=val}
                        //        if(k == 'COMPS'){var comps=val}
                        //        if(k == 'ID_COMPS'){var id_comp=val} 
                        //        if(k == 'PIEZAS_A'){var pzas= val}
                        //        if(k == 'PRIMARIO'){var prim= val}
                        //        if(k == 'SECUNDARIO'){var secu= val}
                        //        if(k == 'ID_AM'){var mov= val}
                        //    }
                        //    comp.prop('id', ln+'_'+id_comp)
                        //    comp.prop('title', compp + '_:_' + comps)
                        //    comp.append('<br/><text class="Lit hidden"><b>Linea: </b>' +prim+'</text>')
                        //    comp.append('<br/><text class="Lit hidden"><b>Tarima: </b>' +secu+ '</text>')
                        //    comp.append('<br/><text class="Lit hidden"><b>Cantidad:</b> <a class="surte" value="100" comps="'+id_comp+'" cant="'+pzas+'" ordd="'+ordd+'" mov="'+mov+'" pnd="'+pnd+'" ><font color="red">'+pzas+'</font></a></text>')
                            //comp.append('<hr size=20 color="red"/>')
                        //}
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
        })
    }


    function revisa(algo){    
        $(".comp").each(function(){
            var mod = $(this).attr("mod")
            var ln = $(this).attr("ln")
            var ordd = $(this).attr('ordd')
            var comp = $(this)
            var pos = document.getElementById("surt_"+ln)
            var pnd = $(this).attr('pnd')
            pos.innerHTML=''
            //comp.remove('p')
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
                            //comp.append('<hr size=20 color="red"/>')
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

    $("body").on("click", ".surte", function(e){
        e.preventDefault();
        return false
        var ordd = $(this).attr('ordd');
        var comps = $(this).attr('comps');
        var mov = $(this).attr('mov')
        var pnd = $(this).attr('pnd')
        if(pnd == 0){
            $.alert("El pedido esta surtido")
            return
        }else{    
            $.ajax({
                url:'index.wms.php',
                type:'post',
                dataType:'json',
                data:{surte:mov, ordd, comps},
                success:function(data){
                    if(data.status=='ok'){
                        /// aqui ponemos los nuevos datos de las existencias
                        revisa()
                        document.getElementById('act_'+ordd).innerHTML=(parseFloat(data.srt))+'\/<font color="blue">'+(parseFloat(data.pnd))+'</font>'
                        // restamos lo asignado a lo pendiente para que cuadre la informacion. 
                    }
                }, 
                error:function(){
                }
            })
        }
    })


    $(".chgProd").autocomplete({
        source: "index.wms.php?producto=1",
        minLength: 2,
        select: function(event, ui){
        }
    })

    $(".report").click(function(){
        var t = $(this).val()
        var out = $("#"+t).val()
        $.ajax({
            url:'index.wms.php',
            type:'post',
            dataType:'json',
            data:{report:1, t, out},
            success:function(data){
                if(data.status == 'ok'){
                    if(out == 'x'){
                        $.alert('Descarga del archivo de Excel')
                        window.open( data.completa , 'download')
                    }
                    if(out == 'p'){
                        $.alert('Abrir la pagina en una ventana nueva')
                        window.open(data.completa, '_blank')
                    }
                    if(out == 'b'){
                        $.alert('Descargar el archivo en excel y Abrir la pagina en una ventana nueva')   
                        window.open( data.completa , 'download')
                    }
                }
            },
            error:function(){
                    $.alert('Ocurrio un error')   
            }
        })
    })

    $(".reemp").click(function(){
        var p = $(this).attr('p')
        document.getElementById("rem_"+p).classList.remove('hidden')
    })

    $(".chgProd").change(function(){
        var nP = $(this).val()
        var p = $(this).attr('prod')
        nP = nP.split(":")
        $.confirm({
            title: 'Cambio de producto',
            content: 'Desea Cambiar el producto ' + p+ ' por el producto '+ nP[0] ,
            buttons:{
                Si: function(){
                    $.ajax({
                        url:'index.wms.php',
                        type:'post',
                        dataType:'json',
                        data:{chgProd:1, p, nP:nP[0], oc:ord, t:'p'}, 
                        success:function(data){
                            $.alert(data.msg)
                            /// cambiar valor en el Prod...
                            setTimeout(function(){
                                location.reload(true)
                            })
                        },
                        error:function(){
                            /// regresar al valor inicial
                        }
                    },10000 )
                },
                No:function(){
                   return;
                }
            }
        });
    })
    
</script>
