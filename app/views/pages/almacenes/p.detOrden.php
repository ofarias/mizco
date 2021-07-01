<br /><br />
<style type="text/css">
    .num {
        width: 5em;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div>Detalles del Archivo: <label><?php echo $cabecera->ARCHIVO?> </label><br/>Para el Cliente: <label><?php echo $cabecera->CLIENTE?></label>  <?php echo !empty($cabecera->ORDEN)? '<br/>Incluye las ordenes:<label>'.$cabecera->ORDEN.'</label>':''?></label></div>
            <a class="btn-sm btn-success fin">Finalizar</a>&nbsp;&nbsp;&nbsp; <a class="btn-sm btn-danger hidden" id="close">Cerrar</a>
            <br/>
            <br/>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
            <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover" id="dataTables-detOrd">
                                    <thead>
                                        <tr>
                                            <th> ln </th>
                                            <th> Orden</th>
                                            <th> Clave <br><font color="purple">Clave SKU</font></th>
                                            <th> Producto </th>
                                            <th> Cajas </th>
                                            <th> Piezas por Caja</th>
                                            <th> Piezas Totales </th>
                                            <th> Color </th>
                                            <th> Cedis </th>
                                            <th> Piezas Surtidas </th>
                                            <th> Cajas Surtidas </th>
                                            <th> Estado </th>
                                            <th> <font color='blue'>SKU</font> <br/><font color="green"> ITEM</font></th>
                                        </tr>
                                    </thead>
                                  <tbody>
                                    <?php $ln=0; foreach ($orden as $ord): 
                                        $color='';$ln++;
                                        if(empty($ord->DESCR)){
                                            $color = "style='background-color: #FFF7C6;'";
                                        }
                                        //$color = '';if(trim($kp->STATUS) == 'Eliminado'){ $color="style='background-color:#f33737'";}
                                        ?>
                                       <tr class="odd gradeX color" <?php echo $color?>>
                                            <td><input type="checkbox" name="selector" value="<?php echo $ord->ID_ORDD?>"></td>
                                            <td><?php echo $ord->ORDEN?></td>
                                            <td><text id="new_<?php echo htmlspecialchars($ord->PROD)?>"><?php echo htmlspecialchars($ord->PROD)?></text>
                                            <a title="Actualizar" class="actProd"  prod="<?php echo htmlspecialchars($ord->PROD)?>" prodn="<?php echo $ord->PROD_SKU?>"><br/>
                                                <font color="purple" > <?php echo $ord->PROD_SKU ?></font> </a>
                                                <?php if($ord->PZAS <> $ord->ASIG){?>
                                                <br/>
                                                <input type="text" id="rem_<?php echo htmlspecialchars($ord->PROD)?>" class="chgProd hidden" placeholder="Remplazar" prod="<?php echo htmlspecialchars($ord->PROD)?>">
                                                <br/>
                                                <a title="Reemplazar el producto" class="reemp" p="<?php echo htmlspecialchars($ord->PROD)?>">Remplazar</a>
                                                <?php }?>
                                            </td>
                                            <td id="det_<?php echo $ln?>"><b><text id="newD_<?php echo htmlspecialchars($ord->PROD)?>"><?php echo $ord->DESCR?></text></b>
                                                <label class="det" 
                                                    prod="<?php echo htmlspecialchars($ord->PROD)?>" 
                                                    ln="<?php echo $ln?>" 
                                                    id="det+_<?php echo $ln?>">+</label>
                                                <label class="detm hidden" ln="<?php echo $ln?>" id="det-_<?php echo $ln?>">-</label>
                                            </td>
                                            <td><?php echo $ord->CAJAS?></td>
                                            <td><?php echo $ord->UNIDAD?></td>
                                            <td><?php echo $ord->PZAS?></td>
                                            <td><?php echo $ord->COLOR?></td>
                                            <td><?php echo $ord->CEDIS?></td>
                                            <td><?php echo $ord->PZAS_SUR?></td>
                                            <td><?php echo $ord->CAJAS_SUR?></td>
                                            <td><?php echo $ord->STATUS?></td>
                                            <td><?php echo '<font color="blue">'.$ord->UPC.'<br/></font> <br/><font color="green">'.$ord->ITEM.'</font>'?></td>
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
    
    $(".fin").click(function(){
        $.ajax({
            url:'index.wms.php',
            type:'post',
            dataType:'json',
            data:{aOC:ord},
            success:function(data){
                alert(data.msg)
                $("#close").removeClass("hidden")
            },  
            erroe:function(error){
            }
        })
    })

    $("#close").click(function(){
        window.close()
    })

    $(".actProd").click(function(){
        var prod = $(this).attr('prod')
        var prodn = $(this).attr('prodn')
        $.ajax({
            url:'index.wms.php',
            type:'post',
            dataType:'json',
            data:{actProOrd:1, prod, oc:ord, prodn},
            success:function(data){
                //$.alert('Se ha actualizado')
                if(data.status == 'ok'){
                    document.getElementById("new_"+prod).innerHTML= data.prod
                    document.getElementById("newD_"+prod).innerHTML= data.desc

                    //Cambiar el codigo y traer el nuevo nombre
                }
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
        var ln = document.getElementById("rem_"+p)
        ln.classList.remove('hidden')
        ln.focus()        
    })

    $(".chgProd").change(function(){
        var a = $(this)
        var ln = $(this).attr('ln')
        var nP = $(this).val()
        var p = $(this).attr('prod')
        nP = nP.split(":")
        $.confirm({
            title: 'Cambio de producto',
            content: 'Desea Cambiar el producto ' + p+ ' por el producto '+ nP[0] ,
            buttons:{
                Si:{
                    text:"Si",
                    keys:['enter', 's','S'],
                    action:function(){
                        $.ajax({
                            url:'index.wms.php',
                            type:'post',
                            dataType:'json',
                            data:{chgProd:1, p, nP:nP[0], oc:ord, t:'p'}, 
                            success:function(data){
                                if(data.status=='ok'){
                                    document.getElementById('new_'+p).innerHTML=nP[0]
                                    document.getElementById('newD_'+p).innerHTML=nP[1]
                                    document.getElementById('det+_'+ln).setAttribute('prod', nP[0])
                                    a.val('')
                                    a.attr('class', 'chgProd hidden')
                                }
                            },
                            error:function(){
                                /// regresar al valor inicial
                            }
                        },10000 )
                    }
                },
                No:{
                    text:'No',
                    keys:['esc', 'N', 'n'],
                    action:function(){
                       return;
                    }
                }
            }
        });
    })
    
</script>
