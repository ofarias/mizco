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
                           <font size="5px"> Movimiento de Salida Bodeja <?php echo $fol?></font> 
                        </div>
                        <br/>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            Tipo:    <select class="tip control" name="algo">
                                        <option value="s">Salida</option>
                                    </select>
                            <br/><br/>

                            <label >&nbsp;&nbsp;&nbsp;&nbsp;Almacen:
                                    <select class="alm control">
                                            <option value="<?php echo $al?>"><?php echo 'Almacen '.$al ?></option>
                                    </select>
                            </label>

                            <br/><br/>
                            <label>&nbsp;&nbsp;&nbsp;&nbsp;Producto: <input type="text" size="50" placeholder="<?php echo empty($pr)? 'Filtro por Producto':$prod ?>" class="prod" > </label>
                            <br/><br/>
                            <label> &nbsp;&nbsp;&nbsp;&nbsp;Compente Primario (principalmente lineas):
                                        <select class="compP control">
                                            <?php if($cp!='a'){?>
                                                <?php foreach($comp as $compp): ?>
                                                    <?php if($compp->ID_COMPP == $cp): ?>
                                                        <option value="<?php echo $compp->ID_COMPP?>"><?php echo $compp->ETIQUETA?></option>
                                                    <?php endif;?>
                                                <?php endforeach;?>
                                            <?php }else{?>
                                                <option>Seleccione un componente</option>
                                                    <?php foreach($comp as $compp): ?>
                                                        <option value="<?php echo $compp->ID_COMPP?>"><?php echo $compp->ETIQUETA?></option>
                                                    <?php endforeach;?>
                                            <?php }?>
                                        </select>    
                            </label><br/><font size="1.8x">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo empty($pr)? 'Solo se muestran las lineas que tienen productos.':'Solo se muestran las lineas que tiene el producto <b>'.$prod.'<b/>' ?></font>
                            <br/><br/>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php if($fol=='x'){?>
                                <a class="btn-sm btn-primary" href="index.wms.php?action=wms_menu&opc=newMov" target="_self">Limpiar</a><br/>
                            <?php }else{ ?>
                                <a class="btn-sm btn-primary fin" >Finalizar</a><br/>
                            <?php }?>

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
                                            <th> Linea </th>
                                            <th> Tarima </th>
                                            <th> Producto </th>
                                            <th> Unidad </th>
                                            <th> Cantidad</th>
                                            <th> Usuario <br/> Entrada </th>
                                            <th> Piezas <br/> Entrada </th>
                                            <th> Salida </th>
                                            <th> Piezas <br/>Disponibles </th>
                                            <th> Piezas a <br/>Sacar </th>
                                            <th> Ejecutar </th>
                                            
                                        </tr>
                                    </thead>
                                  <tbody>
                                    <?php $ln=0; foreach ($partidas as $kp): 
                                        $ln++; $color = '';if(trim($kp->STATUS) == 'Eliminado'){ $color="style='background-color:#f33737'";
                                    }?>
                                       <tr class="odd gradeX color" <?php echo $color?>>
                                            <td><?php echo $kp->LINEA?></td>
                                            <td><?php echo $kp->TARIMA?></td>
                                            <td><?php echo $kp->INTELISIS?></td>
                                            <td><?php echo $kp->UNIDAD?></td>
                                            <td id="c_<?php echo $ln?>" cnt="<?php echo $kp->DISPONIBLE?>"><?php echo number_format($kp->CANT,0)?></td>
                                            <td><?php echo $kp->USR_ENT?></td>
                                            <td><?php echo $kp->PIEZAS?></td>
                                            <td><?php echo $kp->PIEZAS_SAL?></td>
                                            <td><?php echo $kp->DISPONIBLE?></td>
                                            <td align="right"><input type="text" placeholder="Cantidad" size="5" class="cant" onpaste="alert('No puedes pegar');return false" ln="<?php echo $ln?>" cs="<?php echo $kp->ID_COMPS?>" cp="<?php echo $kp->ID_COMPP?>" mov="<?php echo $kp->ID_AM?>"></td>
                                            <td><a class="exeSal hidden <?php echo $ln?>" id="<?php echo $ln?>" >&#x23f5;</a></td>
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

<?php if(count($movimiento)>0){?>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                           <font size="5px"> Movimiento <?php echo $fol?></font> 
                        </div>
            <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover" id="dataTables">
                                    <thead>
                                        <tr>
                                            <th> Linea </th>
                                            <th> Tarima </th>
                                            <th> Producto </th>
                                            <th> Unidad </th>
                                            <!--<th> Cantidad </th>-->
                                            <th> Usuario <br/> Salida </th>
                                            <th> Piezas <br/> Salida </th>
                                            <th> Fecha de Salida </th>
                                        </tr>
                                    </thead>
                                  <tbody>
                                    <?php $ln=0; foreach ($movimiento as $mv): 
                                        $ln++; $color = '';if(trim($mv->STATUS) == 'Eliminado'){ $color="style='background-color:#f33737'";
                                    }?>
                                       <tr class="odd gradeX color" <?php echo $color?>>
                                            <td><?php echo $mv->LINEA?></td>
                                            <td><?php echo $mv->TARIMA?></td>
                                            <td><?php echo $mv->PRODUCTO?></td>
                                            <td><?php echo $mv->UNIDAD?></td>
                                            <!--<td><?php echo number_format($mv->CANT,0)?></td>-->
                                            <td><?php echo $mv->NOMBRE?></td>
                                            <td><?php echo $mv->PIEZAS?></td>
                                            <td><?php echo $mv->FECHA?></td>
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

    var a = <?php echo "'".$al."'"?>;
    var cp = <?php echo empty($cp)? "'a'":"'".$cp."'"?>;
    var prod = <?php echo empty($prod)? "'a'":"'".$prod."'"?>;
    var fol = <?php echo empty($fol)? "'x'":"'".$fol."'" ?>;
    var ser = 'A';
    var mov=<?php echo $mov==''? "'nuevo'":$mov?>

    $(".fin").click(function(){
        $.ajax({
            url:'index.wms.php',
            type:'post',
            dataType:'json',
            data:{finSal:fol},
            success:function(data){                
                window.open("index.wms.php?action=wms_menu&opc=newMov", "_self")
            },
            error:function(error){

            }
        })
    })
     
    $(".exeSal").click(function(){
        var i = 0; var e=0; var t='';var c=0; var data='';
        $(".hidden").each(function(ind,elem){i++;})
        $(".exeSal").each(function(index,element){e++;})
        $(".cant").each(function(index, element){
            var cant = $(this).val();
            if(cant !=''){
                c++;
                var cant = $(this).val()
                var cs   = $(this).attr('cs')
                var cp   = $(this).attr('cp')
                var mov  = $(this).attr('mov')
                data += 'f:'+fol+':c:'+cant+':cs:'+cs+':cp:'+cp+':mov:'+mov+','; 
            }
        })
        if(e==1){ t = 'Singular';}else if( i>0 ){ t = 'plural'}
        ejecutar(t, data)
    })

    function ejecutar(t, data){
        $.ajax({
            url:'index.wms.php',
            type:'post',
            dataType:'json',
            data:{exeSal:data, fol},
            success:function(data){
                window.open("index.wms.php?action=wms_menu&opc=newMovv2:t:s:a:"+a+":compp:"+cp+":comps::prod:"+prod+":fol:"+data.folio, "_self")
            }, 
            error:function(error){
            }
        })        
    }

    $(".cant").change(function(){
        var cant = $(this)
        var ln = $(this).attr('ln')
        if(!$.isNumeric(cant.val())){
            $("#"+ln).addClass("hidden")
            $.alert(cant.val() + ' No es una cantidad valida.')
            cant.val("")
        }else{
            var cnt = document.getElementById("c_"+cant.attr('ln')).getAttribute("cnt")
            if(parseFloat(cnt) >= parseFloat(cant.val()) && parseFloat(cant.val())<=10000 && parseFloat(cant.val())>0){
                document.getElementById(cant.attr('ln')).classList.remove("hidden")
                cant.addClass('proc')
            }else{
                $("#"+ln).addClass("hidden")
                cant.val("")
                $.alert("Solo se puede sacar como maximo 20 piezas")
            }
        }
    })


    $(".prod").autocomplete({
        source: "index.wms.php?producto=1",
        minLength: 2,
        select: function(event, ui){
        }
    })

    $(".prod").change(function(){
        var prod = $(this).val()
        //$.alert("newMovv2:t:s:a:"+a+":compp:"+cp+":p:"+prod )
        window.open("index.wms.php?action=wms_menu&opc=newMovv2:t:s:a:"+a+":compp:a:comps::prod:"+prod+":fol:"+fol, "_self")
    })

    $(".compP").change(function(){
        var cp = $(this).val()
        //$.alert("es salida a"+a+":compp:"+cp+":comps::prod:"+prod+':folio:'+fol)
        window.open("index.wms.php?action=wms_menu&opc=newMovv2:t:s:a:"+a+":compp:"+cp+":comps::prod:"+prod+":fol:"+fol, "_self")
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

    function actualiza(){
        var cant = $(".cant").val();
        var fact = $(".uni option:selected").attr('fact')
        var totPzas = '';
        if($.isNumeric(fact) && $.isNumeric(cant)){
            totPzas = cant * fact    
            document.getElementById('totPzas').innerHTML=totPzas
        }
    }



</script>