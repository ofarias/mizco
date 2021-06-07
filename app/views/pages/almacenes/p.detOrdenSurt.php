<br /><br />
<style type="text/css">
    .num {
        width: 5em;
    }
</style>
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
                                            <th> Cedis</th>
                                            <th> UPC</th>
                                            <th> Modelo</th>
                                            <th> Cantidad / <br/> Asignado</th>
                                            <th> Cajas </th>
                                            <th> Piezas por Caja</th>
                                            <th> Piezas Surtidas </th>
                                            <th> Cajas Surtidas </th>
                                            <th> Etiqueta </th>
                                            <th> Estado <br/> Finalizar</th>
                                        </tr>
                                    </thead>
                                  <tbody>
                                    <?php $ln=0; foreach ($orden as $ord): 
                                        $color=''; $ln++;
                                        if(empty($ord->DESCR)){
                                            $color = "style='background-color: #FFF7C6;'";
                                        }
                                        //$color = '';if(trim($kp->STATUS) == 'Eliminado'){ $color="style='background-color:#f33737'";}
                                        ?>
                                       <tr class="odd gradeX color" <?php echo $color?>>
                                            <td><?php echo $ord->CEDIS?></td>
                                            <td><?php echo '<font color="blue">'.$ord->UPC.'<br/></font> <br/><font color="green">'.$ord->ITEM.'</font>'?></td>
                                            <td><?php echo $ord->PROD?>
                                            <br/> <font color="purple" > <?php echo $ord->PROD_SKU ?></font>
                                            </td>
                                            <td><?php echo $ord->PZAS.' / '.$ord->ASIG?><br/>
                                                <p class="comp" mod="<?php echo $ord->PROD?>" ln="<?php echo $ln?>" ordd="<?php echo $ord->ID_ORDD?>"></p></td>
                                            <td><?php echo $ord->CAJAS?></td>
                                            <td><?php echo $ord->UNIDAD?></td>
                                            <!--<td><?php echo $ord->COLOR?></td>-->
                                            <td align="center"><input type="text" size="10" class="surtir" ><br/><?php echo $ord->PZAS_SUR ?></td>
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
        $(".comp").each(function(){
            var mod = $(this).attr("mod")
            var ln = $(this).attr("ln")
            var ordd = $(this).attr('ordd')
            var comp = $(this)
            $.ajax({
                url:'index.wms.php',
                type:'post',
                dataType:'json',
                data:{comPro:mod},
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
                            comp.append('<p><b>Linea: </b>' +prim+'</p>')
                            comp.append('<p><b>Tarima: </b>' +secu+ '</p>')
                            comp.append('<p><b>Cantidad:</b> <a class="surte" value="100" comps="'+id_comp+'" cant="'+pzas+'" ordd="'+ordd+'" mov="'+mov+'"><font color="red">'+pzas+'</font></a></p>')
                            //comp.append('<hr size=20 color="red"/>')
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
    })


    $("body").on("click", ".surte", function(e){
        e.preventDefault();
        var ordd = $(this).attr('ordd');
        var comps = $(this).attr('comps');
        var mov = $(this).attr('mov')
        $.ajax({
            url:'index.wms.php',
            type:'post',
            dataType:'json',
            data:{surte:mov, ordd, comps},
            success:function(data){

            }, 
            error:function(){

            }
        })
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
