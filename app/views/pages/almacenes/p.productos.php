<br /><br />
<style type="text/css">
    .num {
        width: 5em;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Productos en Intelisis <input type="button" value="Actualizar" class="btn-sm btn-primary actProdInt" t="p">&nbsp;&nbsp;&nbsp;<input type="button" value="Existencias Intelisis" class="btn-sm btn-info actProdInt" t="x" >
                <input type="button" value="Imprimir XLS" class="btn-sm btn-success xls">
                <input type="button" value="Descontinuados" class="btn-sm btn-warning descon">
            </div>
            <div class="panel-body">
                <div class="table-responsive">                            
                    <table class="table table-striped table-bordered table-hover" id="dataTables-productos">
                        <thead>
                            <tr>
                                <th>Clave<br/> Desc</th>

                                <th>Descripción</th>
                                <!--<th>Presentación <br/>entrada</th>-->
                                <th>Largo <br/> cm</th>
                                <th>Ancho <br/> cm</th>
                                <th>Alto <br/> cm</th>
                                <th>Peso <br/>Volumétrico</th>
                                <th title="Cantidad de piezas en una Palet/Tarima estandar americana 100x120x10. 25 Kg">Piezas <br/>x Palet</th>
                                <th title="Cantidad de Piezas de Origen por Caja.">Master</th>
                                <th>Tipo <br/>Intelisis</th>
                                <th>Estatus <br/>Intelisis</th>
                                <th>Guardar</th>
                                <th>Info Intelisis</th>
                                <th>Info Almacen</th>
                                <th>Diferencia</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td colspan="8"></td>
                                <!--
                                <td><a target="_blank" href="index.php?action=imprimircatgastos" class="btn btn-info">Imprimir <i class="fa fa-print"></i></a></td>
                                <td><a href="index.php?action=nuevogasto" class="btn btn-info">Agregar <i class="fa fa-plus"></i></a></td>
                                -->
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php $i=0;foreach($info as $row): $i++;
                                $vol=0; $volT = 960000; $volm = 0;
                                $dif=$row->DISP-$row->DISP_ALM;
                                $color='';
                                if($dif > 0 ){
                                    $color = "style=background-color:#ffbcc3;";
                                }elseif($dif<0){
                                    $color = "style=background-color:#eaf5fa;";
                                }
                                $vol = $row->ANCHO * $row->ALTO * $row->LARGO;
                                $volm = ($row->UNIDAD_ORIG>0 and $row->PZS_PALET_O > 0)? $vol*$row->UNIDAD_ORIG:0;

                            ?>
                            <tr class=" <?php echo $row->STATUS?>" id="linc<?php echo $i?>" <?php echo $color;?> <?php echo trim($row->STATUS)=='Alta'? '':'hidden'?> >
                                <td>
                                    <a class="posicion" prod="<?php echo $row->ID_PINT?>" nom="<?php echo $row->ID_INT?>"><?php echo $row->ID_INT;?></a>&nbsp;&nbsp;&nbsp;<i class="glyphicon glyphicon-info-sign infoAgrupada" prod="<?php echo $row->ID_PINT?>" nom="<?php echo $row->ID_INT?>"></i><br/><a class="movs" prod="<?php echo $row->ID_INT;?>">Movs a Excel</a>
                                    <br/>
                                    <input type="checkbox" name="desc" class="desc" <?php echo $row->STATUS == 'Alta'? '':'checked' ?> prod = "<?php echo $row->ID_PINT?>"> desc
                                </td>
                                <td id="prod_<?php echo $i?>"><?php echo $row->DESC;?></font></td>
                                
                                <!--<td><?php echo $row->PZS_ORIG;?></td>-->
                                
                                <td align="center"><input type="number" name="p_o" value="<?php echo $row->LARGO;?>" step="any" class="num lg marca" lin="<?php echo $i?>" id="lg<?php echo $i?>">
                                    <br/><font color="blue" ><?php echo $row->LARGO;?></font>
                                </td>
                                <td align="center"><input type="number" name="p_o" value="<?php echo $row->ANCHO;?>" step="any" class="num an marca" lin="<?php echo $i?>" id="an<?php echo $i?>"><br/><font color="blue" ><?php echo $row->ANCHO;?></font></td>
                                <td align="center"><input type="number" name="p_o" value="<?php echo $row->ALTO;?>" step="any" class="num al marca" lin="<?php echo $i?>" id="al<?php echo $i?>"><br/><font color="blue" ><?php echo $row->ALTO;?></font></td>
                                <td align="right"><?php echo number_format((($row->LARGO * $row->ANCHO * $row->ALTO)/166),5)?>
                                    <b>Volumen: </b> <?php echo number_format($vol).'cm3'?>
                                </td> 

                                <td align="center" title="Piezas por Palet/Tarima.">
                                    <!--<input type="number" name="p_o" value="<?php echo $row->PZS_PALET_O;?>" step="any" class="num p marca" lin="<?php echo $i?>" id="p<?php echo $i?>">
                                    <br/>-->
                                    <font color="blue" ><?php echo $row->PZS_PALET_O;?></font>
                                </td>
                                <td align="center" title="Cantidad de Piezas de Origen por Caja.">
                                    <input type="number" name="p_o" value="<?php echo $row->UNIDAD_ORIG;?>" step="any" class="num uo marca" lin="<?php echo $i?>" id="uo<?php echo $i?>"><br/><font color="blue" ><?php echo $row->UNIDAD_ORIG;?></font>
                                    <?php if($volm>0){?>
                                        <br/><b>Volumen Master:</b> <?php echo number_format($volm).' cm3'?>
                                    <?php }?>
                                </td>
                                <td><?php echo $row->TIPO_INT?></td>
                                <td><?php echo $row->STATUS?></td>
                                <td>
                                    <input type="button" name="gd" value="Guardar" class="btn-sm save hidden" ln="<?php echo $i?>" id="bg<?php echo $i?>" cve="<?php echo $row->ID_PINT?>">
                                </td>
                                <td title="Exi:Existencia, Res: Reservado, Rem: Remisionado">Disponible: <b><?php echo number_format($row->DISP,0)?></b><br/>Almacen:<b><?php echo $row->ALMACEN?></b>&nbsp;&nbsp;<?php echo 'Exis: <b>'.number_format($row->EXI,0).'</b>, Res: <b>'.number_format($row->RES,0).'</b>, Rem: <b>'.number_format($row->REM,0).'</b>'?><br/><b><?php echo $row->FECHA?><b></td>                                
                                <td>Disponible:<?php echo number_format($row->DISP_ALM,0)?><br/> <b>Entradas :</b><?php echo number_format($row->ING,0).'&nbsp;&nbsp; <b>Asignado :</b> '.number_format($row->ASIG,0).'&nbsp;&nbsp; <b>Salidas :</b> '.number_format($row->OUT)?></td>
                                <td><?php echo number_format($dif,0)?></td>
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


    $(".infoAgrupada").click(function(){
        //posicion
        var prod = $(this).attr('prod')
        var nombre = $(this).attr('nom')
        var almacenes = ''
        var lineas = ''
        var tarimas = ''
        var cantidades = ''
        var categorias = ''
        var comps = ''
        var movs  = ''
        var info = "El producto <b>"+ nombre +"</b> se encuenta en: <br/><br/>"
        info += 'Ubicacion Actual:<br/><br/>'
        $.ajax({
            url:'index.wms.php',
            type:'post',
            dataType:'json',
            data:{posiciones:prod, tipo:'a'},
            success:function(data){
                for(const [key, value] of Object.entries(data.datos)){
                    for(const[k,val] of Object.entries(value)){
                        if(k == 'LINEA'){var lin = val}
                        if(k == 'TARIMA'){var tar = val}
                        if(k == 'DISPONIBLE'){var disp = val}
                        if(k == 'ID_COMPS'){ var idComps = val}
                        if(k == 'CATEGORIA'){ var cat = val}
                        if(k == 'ID_AM'){ var mov = val}
                        if(k == 'ALMACEN'){var almacen = val}
                        if(k == 'PIEZAS'){var pzas = val}
                        if(k == 'PIEZAS_SAL'){var pSal = val}
                        if(k == 'PIEZAS_SURT'){var pSurt = val}
                    }
                    if (parseFloat(disp) > 0){
                        info += 'Almacen: <b>'+ almacen +'</b> : <b>' +lin + '</b> : <b><input type="hidden" value="'+idComps+'" class="comps"> <input type="hidden" value="'+mov+'" class="idmovs"> ' + tar + 
                        '</b> Exist: <b>' + (pzas - pSal) +
                        '</b> En Surtido: <b>' + pSurt +
                        '</b> Disp: <b><input type="hidden" value="'+disp+'" class= "disps" >'+ disp + 
                        '</b> Cat: <b>' + cat + '</b>' +
                        '<br/>'
                    }
                }
                if(data.status='ok'){
                    $.confirm({
                        columnClass: 'col-md-8',
                        title:'Ubicacion del producto',
                        content: '' + 
                            '<form action="index.wms.php" type="post" name="reubicaCant" class="formName">'+
                            '<div class="form-group">'+
                            '<label> </label> <br/>'+
                            info +
                            '</div>' +
                            '</form>'
                        ,
                        buttons:{
                            Cerrar:{
                                text:'Cerrar',
                                btnClass:'btn-red',
                                keys:['esc'],
                                action:function(){
                                    return
                                }
                            }
                        }/*,
                        onContentReady:function(){
                            var jc = this;
                            this.content.find('form').on('submit', function(e){
                                e.preventDefault();
                                jc.$$formSubmit.trigger('click');
                            });
                        }*/
                    });
                }
            },
            error:function(error){
            }
        })
    })

    $(".descon").click(function(){
        $(".Descontinuado").show()
    })

    $(".desc").click(function (){
        var prod = $(this).attr('prod')
        $.ajax({
            url:'index.wms.php',
            type:'post',
            dataType:'json',
            data:{desc:prod},
            success:function(data){
                $.alert('Descontinudo')
            }, 
            error:function(){

            }
        })
    })

    $(".xls").click(function(){
        var out = 'x'
        $.ajax({
            url:'index.wms.php',
            type:'post',
            dataType:'json',
            data:{report:1, t:'prod', out},
            success:function(data){
                if(data.status == 'ok'){
                    if(out=='x'){
                        $.alert('Descarga del archivo de Excel')
                        window.open( data.completa , 'download')
                    }
                }
            },
            error:function(){
                    $.alert('Ocurrio un error')   
            }
        })
    })

    $(".posicion").click(function(){
        var prod = $(this).attr('prod')
        var nombre = $(this).attr('nom')
        var almacenes = ''
        var lineas = ''
        var tarimas = ''
        var cantidades = ''
        var categorias = ''
        var comps = ''
        var movs  = ''
        var info = "El producto <b>"+ nombre +"</b> se encuenta en: <br/><br/>"
        info += 'Ubicacion Actual:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nueva Ubicacion <br/><br/>'
        $.ajax({
            url:'index.wms.php',
            type:'post',
            dataType:'json',
            data:{posiciones:prod, tipo:'n'},
            success:function(data){
                for(const [key, value] of Object.entries(data.datos)){
                    for(const[k,val] of Object.entries(value)){
                        if(k == 'LINEA'){var lin = val}
                        if(k == 'TARIMA'){var tar = val}
                        if(k == 'DISPONIBLE'){var disp = val}
                        if(k == 'ID_COMPS'){ var idComps = val}
                        if(k == 'CATEGORIA'){ var cat = val}
                        if(k == 'ID_AM'){ var mov = val}
                        if(k == 'ALMACEN'){var almacen = val}
                        if(k == 'PIEZAS'){var pzas = val}
                        if(k == 'PIEZAS_SAL'){var pSal = val}
                        if(k == 'PIEZAS_SURT'){var pSurt = val}

                    }
                    if (parseFloat(disp) > 0){
                        info += 'Almacen: <b>'+ almacen +'</b> : <b>' +lin + '</b> : <b><input type="hidden" value="'+idComps+'" class="comps"> <input type="hidden" value="'+mov+'" class="idmovs"> ' + tar + 
                        '</b> Exist: <b>' + (pzas - pSal) +
                        '</b> En Surtido: <b>' + pSurt +
                        '</b> Disp: <b><input type="hidden" value="'+disp+'" class= "disps" >'+ disp + 
                        '</b> Cat: <b>' + cat + '</b>' +
                        '<select class="alm">  '+
                            '<option value="0">Almacen</option>'+
                            '<option value="1">Almacen 1</option>'+
                            '<option value="2">Almacen 2</option>'+
                        '</select>' +
                        ' | ' + '<input type="text" size="7" placeholder="Linea" class="lin">' +
                        ' | ' + '<input type="text" size="7" placeholder="Tarima" class="tar">' +
                        ' | ' + '<select class="cat">' +
                                    '<option value="0"> Categoria</option>'+
                                    '<option value="1"> Primera </option>'+
                                    '<option value="2"> Segunda </option>'+
                                    '<option value="3"> Tercera </option>'+
                                '</select>'+
                        ' | ' + '<input type="text" size="7" placeholder="Cantidad" class="cant">' +
                        '<br/>'
                    }
                }
                if(data.status='ok'){
                    $.confirm({
                        columnClass: 'col-md-12',
                        title:'Cambio de ubicacion',
                        content: '' + 
                            '<form action="index.wms.php" type="post" name="reubicaCant" class="formName">'+
                            '<div class="form-group">'+
                            '<label> Reubicacion del producto </label> <br/>'+
                            info +
                            '</div>' +
                            '</form>'
                        ,
                        buttons:{
                            formSubmit:{
                                text:'Mover',
                                btnClass:'btn-green',
                                keys:['enter'],
                                action:function(){
                                    $(".alm").each(function(){
                                        almacenes += '|' + $(this).val()
                                    })
                                    $(".lin").each(function(){
                                        lineas += '|' + $(this).val()
                                    })
                                    $(".tar").each(function(){
                                        tarimas += '|' + $(this).val()
                                    })
                                    $(".cat").each(function(){
                                        categorias += '|' + $(this).val()
                                    })
                                    $(".cant").each(function(){
                                        cantidades += '|' + $(this).val()
                                    })
                                    $(".comps").each(function(){
                                        comps += '|' + $(this).val()
                                    })
                                    $(".idmovs").each(function(){
                                        movs += '|' + $(this).val()
                                    })
                                    //$.alert('Valor de los almacenes: ' + almacenes + '<br/> Lineas ' + lineas  + '<br/> Tarimas ' + tarimas  + '<br/> Categorias ' + categorias  + '<br/> Cantidades ' + cantidades + '<br/> Id producto: ' + prod + '<br/> Comps: ' + comps )
                                    var datos = [almacenes, lineas, tarimas, categorias, cantidades, comps, movs, prod]
                                    $.ajax({
                                        url:'index.wms.php',
                                        type:'post',
                                        dataType:'json',
                                        data:{reubPza:datos},
                                        success:function(data){
                                            if(data.status=='ok'){
                                                $.alert(data.mensaje)
                                            }
                                        }, 
                                        error:function(){
                                        }
                                    })
                                }
                            },
                            Cerrar:{
                                text:'Cerrar',
                                btnClass:'btn-red',
                                keys:['esc'],
                                action:function(){
                                    return
                                }
                            }
                        }/*,
                        onContentReady:function(){
                            var jc = this;
                            this.content.find('form').on('submit', function(e){
                                e.preventDefault();
                                jc.$$formSubmit.trigger('click');
                            });
                        }*/
                    });
                }
            },
            error:function(error){
            }
        })
    })

    $(".movs").click(function(){
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


    $("body").on("click",".imprimir", function(e){
        e.preventDefault();
        //$(".imprimir").click(function(){
            $.alert("Excel")
        //})
    })

    $(".actProdInt").click(function(){
        //$.alert('Actualiza productos desde intelisis')
        var title=''
        var content=''
        var t = $(this).attr('t')
        if(t== 'p'){
            title= 'Sincronizar Productos'
            content = 'Se sincronizo correctamente los productos'
        }else if(t == 'x'){
            title= 'Sincronizar Existencias'
            content = 'Se sincronizaron correctamente las existencias'
        }
        $.confirm({
            content: function () {
                var self = this;
                return $.ajax({
                    method: 'GET',
                    url: 'index.wms.php',
                    data: 'action=wms_menu&opc=pa'+t,
                }).done(function (response){
                    self.setContent(content);
                    self.setTitle(title);
                    window.open("index.wms.php?action=wms_menu&opc=p", '_self')
                }).fail(function(){
                    self.setContent('Algo se nos salio de control o no calculamos bien... favor de reportar al 5550553392');
                });
            }
        });
    })

    $(".marca").change(function(){
        var val = $(this).val()
        var lin = $(this).attr('lin')
        var obj=document.getElementById("bg"+lin)
        obj.classList.remove('hidden')
        var linea = document.getElementById("linc"+lin)
        linea.style.background="#ff953d";
    })

    $(".save").click(function(){
        var lin = $(this).attr('ln');
        var prod = document.getElementById('prod_'+lin).innerHTML
        var cve = $(this).attr('cve');
        var an = document.getElementById('an'+lin).value
        var al = document.getElementById('al'+lin).value
        var lg = document.getElementById('lg'+lin).value
        var p = 0 ///document.getElementById('p'+lin).value
        var uo = document.getElementById('uo'+lin).value
        var linea = document.getElementById("linc"+lin)
        var obj=document.getElementById("bg"+lin)
        if(confirm('Desea grabar los cambios?')){
            $.ajax({
                url:'index.wms.php',
                type:'post',
                dataType:'json',
                data:{actProd:cve, lg, an, al, p, uo}, 
                success:function(data){
                    alert(data.msg)
                    linea.style.background="#efffbb";
                    obj.classList.add('hidden')
                },
                error:function(){
                    alert('ocurrio un problema favor de intentar nuevamente')
                }
            })    
        }else{
            return false;
        }
    })
  
</script>