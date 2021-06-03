<br /><br />
<style type="text/css">
    .num {
        width: 5em;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div>Detalles del Archivo: </div>
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
                                            <th> Pizas por Caja</th>
                                            <th> Piezas Totales </th>
                                            <th> Color </th>
                                            <th> Cedis </th>
                                            <th> Piezas Surtidas </th>
                                            <th> Cajas Surtidas </th>
                                            <th> Estado </th>
                                            <th> <font color='blue'>SKU</font> <br/><font color="green"> ITEM</font></th>
                                            <th> Finalizar </th>
                                            <th> Informar / Correo  </th>
                                        </tr>
                                    </thead>
                                  <tbody>
                                    <?php foreach ($orden as $ord): 
                                        $color='';
                                        if(empty($ord->DESCR)){
                                            $color = "style='background-color: #FFF7C6;'";
                                        }
                                        //$color = '';if(trim($kp->STATUS) == 'Eliminado'){ $color="style='background-color:#f33737'";}
                                        ?>
                                       <tr class="odd gradeX color" <?php echo $color?>>
                                            <td><input type="checkbox" name="selector" value="<?php echo $ord->ID_ORDD?>"></td>
                                            <td><?php echo $ord->ORDEN?></td>
                                            <td><?php echo htmlspecialchars($ord->PROD)?>
                                            
                                            <a title="Actualizar" class="actProd"  prod="<?php echo htmlspecialchars($ord->PROD)?>" prodn="<?php echo $ord->PROD_SKU?>"><br/>
                                                <font color="purple" > <?php echo $ord->PROD_SKU ?></font> </a>
                                                <?php if($ord->PZAS <> $ord->ASIG){?>
                                                <br/>
                                                <input type="text" id="rem_<?php echo htmlspecialchars($ord->PROD)?>" class="chgProd" placeholder="Remplazar" prod="<?php echo htmlspecialchars($ord->PROD)?>">
                                                <br/>

                                                <a title="Reemplazar el producto" class="reemp" p="<?php echo htmlspecialchars($ord->PROD)?>">Remplazar</a>
                                                <?php }?>

                                            </td>
                                            <td><?php echo $ord->DESCR?></td>
                                            <td><?php echo $ord->CAJAS?></td>
                                            <td><?php echo $ord->UNIDAD?></td>
                                            <td><?php echo $ord->PZAS?></td>
                                            <td><?php echo $ord->COLOR?></td>
                                            <td><?php echo $ord->CEDIS?></td>
                                            <td><?php echo $ord->PZAS_SUR?></td>
                                            <td><?php echo $ord->CAJAS_SUR?></td>
                                            <td><?php echo $ord->STATUS?></td>
                                            <td><?php echo '<font color="blue">'.$ord->UPC.'<br/></font> <br/><font color="green">'.$ord->ITEM.'</font>'?></td>
                                            <td>
                                                <a href="index.wms.php?action=detOrden&orden=<?php echo $ord->ID_ORD?>" target="popup" onclick="window.open(this.href, this.target, 'width=800,height=600'); return false;"> Finalizar</a></td>
                                            <td><a >Informar</a></td>
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
