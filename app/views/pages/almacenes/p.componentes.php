
<style type="text/css">
    .num {
        width: 5em;
    }
</style>
<br/>
<div>
    <font color="black"><b>Tipo:</b></font>  
    <select class="ftcomp">
            <option value='"none"'>Tipo de componente</option>
        <?php foreach($tc as $tcomp):?>
            <option value="<?php echo $tcomp->ID_TC?>"><?php echo $tcomp->NOMBRE?></option>
        <?php endforeach;?>
    </select>
    <font color="black"><b>Almacen:</b></font> 
        <select class="falma">
                <option value='"none"'>Almacen</option>
            <?php foreach($alm as $almac):?>
                <option value="<?php echo $almac->ID?>"><?php echo $almac->NOMBRE?></option>
            <?php endforeach;?>
        </select>    
    
    <font color="black"><b>Estado:</b></font>  
        <select class="fsta">
                <option value='"none"'>Seleccione el estado</option>
                <option value="1">Activo</option>
                <option value="0">Baja</option>
                <option value="3">Agotado</option>
        </select>      
    <font color="black"><b>Producto:</b></font> 
        <select class="fprod">
                <option value='"none"'>Productos</option>
            <?php foreach($prod as $pro):?>
                <option value="<?php echo $pro->ID_PINT?>"><b><?php echo '<b>'.$pro->ID_INT.'</b>--'.substr($pro->DESC,0,30)?></b></option>
            <?php endforeach;?>
        </select>
    <font color="black"><b>Asociado:</b></font>
        <input type="radio" name="aso" class="hidden" value='"none"' checked="checked">
        &nbsp;Si:<input type="radio" name="aso" value='"si"'>&nbsp;
        &nbsp;&nbsp;No:<input type="radio" name="aso" value='"no"'>&nbsp;&nbsp;

    <input type="button" value='"ir"' name="tipo" class="btn-sm btn-primary filtro">
    <button class="btn-sm btn-info add" >Agregar &nbsp;<i class="fa fa-plus"></i></button>
    <br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <b>Con movimientos del: <input type="date" id="fi" value='"none"'> &nbsp;&nbsp; al:  <input type="date" id="ff" value='"none"'>
    &nbsp;&nbsp;&nbsp;&nbsp;
    Guardar en : <button class="filtro" value='"p"'><font color="purple">Pdf</font></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <button class="filtro" value='"x"'><font color="grey">Excel</font></button>
    </b>
</div>
<br/>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Componentes 
            </div>
            <div class="panel-body">
                <div class="table-responsive">                            
                    <table class="table table-striped table-bordered table-hover" id="dataTables-componentes">
                        <thead>
                            <tr>
                                <th>Sel</th>
                                <th>Etiqueta</th>
                                <th>Descripción</th>
                                <th>Tipo <br/> <font color="blue">Asociado con:</font></th>
                                <th>Largo <br/> <font color="blue">Volumen:</font><br/><font color="green">Disponible</font></th>
                                <th>Ancho</th>
                                <th>Alto</th>
                                <th>Almacen</th>
                                <th>Productos</th>
                                <th>Observaciones</th>
                                <th>Estado</th>
                                <th><font color="green">Entradas</font> /<br/><font color="red"> Salidas</font></th>
                                <th>Contenido</th>
                                <th>Utilerias</th>
                                <th>Imprimir /<br/> QR</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td colspan="14"></td>
                                <!--
                                <td><a target="_blank" href="index.php?action=imprimircatgastos" class="btn btn-info">Imprimir <i class="fa fa-print"></i></a></td>-->
                                <td></td>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php $i=0;foreach($info as $row): $i++; 
                                if($row->ID_TIPO == 1){
                                    $entradas = $row->ENTRADASS;
                                    $salidas = $row->SALIDASS;       
                                }else{
                                    $entradas = $row->ENTRADASP;
                                    $salidas = $row->SALIDASP;       
                                }
                            ?>
                            <tr class="color" id="linc<?php echo $i?>">
                                <td><input type="checkbox" class="selCompP" name="componentes" value="<?php echo $row->ID_COMP?>"></td>
                                <td><?php echo $row->ETIQUETA?></td>
                                <td><textarea cols="25" rows="3" class="chgDesc" t="d" o="<?php echo $row->DESC?>" c="<?php echo $row->ID_COMP?>"><?php echo $row->DESC?></textarea></td>
                                <td><?php echo $row->TIPO?><br/><font color="blue"><?php echo $row->COMPPR?></font></td>
                                <td><?php echo number_format($row->MEDICION=='m'? $row->LARGO/100: $row->LARGO,2).' '.$row->MEDICION?>
                                    <br/> <font color="blue"><?php echo number_format($row->VOLUMEN,0).'cm3'?></font>
                                    <br/> <font color="green"><?php echo number_format($row->DISP,0).'cm3'?>
                                </td>
                                <td><?php echo number_format($row->MEDICION=='m'? $row->ANCHO/100: $row->ANCHO,2).' '.$row->MEDICION?></td>
                                <td><?php echo number_format($row->MEDICION=='m'? $row->ALTO/100: $row->ALTO,2).' '.$row->MEDICION?></td>
                                <td><?php echo $row->ALMACEN?></td>
                                <td title="<?php echo $row->PRODUCTOS?>"><?php echo substr($row->PRODUCTOS,0,50).'...'?></td>
                                <td><textarea cols="25" rows="3" class="chgDesc" t="o" o="<?php echo $row->OBS?>" c="<?php echo $row->ID_COMP?>"><?php echo $row->OBS?></textarea></td>
                                <td>
                                    <SELECT class="sta" comp="<?php echo $row->ID_COMP?>">
                                        <option value="<?php echo $row->ID_STATUS?>"><?php echo $row->STATUS?></option>
                                        <option value="<?php echo ($row->ID_STATUS==0)? 1:0?>"><?php echo ($row->ID_STATUS==0)? 'Activo':'Baja'?></option>>
                                    </SELECT>
                                </td>
                                <td><?php echo '<font color="green">'.$entradas.'</font>/<font color="red">'.$salidas.'</font>'?></td>
                                
                                <td>
                                    <?php if(($entradas - $salidas) > 0):?>
                                        <a href="index.wms.php?action=wms_menu&opc=detComp<?php echo $row->ID_COMP?>" target="popup" onclick="window.open(this.href, this.target, 'width=1200,height=820'); return false;"><font color="green">Contenido del componente</font></a>
                                        <br/>/<br/> 
                                        <a href="index.wms.php?action=wms_menu&opc=detComp<?php echo $row->ID_COMP?>"><font color ="blue">Reporte del componente</font></a>
                                    <?php endif;?>
                                </td>
                                
                                <td>
                                    <input type="button" name="dup" value="Duplicar" class="btn-sm btn-primary dup" et="<?php echo $row->ETIQUETA?>" tipo="<?php echo $row->TIPO?>" id="<?php echo $row->ID_COMP?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php if($row->ID_TIPO == 1):?>
                                    <br/>
                                    <input type="button" value="Asociar" class="btn-sm btn-info asocia" tipo="<?php echo $row->TIPO ?>" id="c_<?php echo $row->ID_COMP?>" idc="<?php echo $row->ID_COMP?>" et="<?php echo $row->ETIQUETA?>"><?php endif;?>
                                    <?php if(($entradas - $salidas) > 0):?>
                                        <br/><br/>
                                    <input type="button" it="<?php echo $row->ID_TIPO?>" value="Reubicar" t="<?php echo $row->TIPO?>" eti="<?php echo $row->ETIQUETA?>" idc="<?php echo $row->ID_COMP?>" title="Mover componente" class="btn-sm btn-success mov <?php echo $row->ID_COMP?>" >
                                    <?php endif;?>
                                </td>
                                <td><?php if(($entradas - $salidas) > 0){?>
                                        <input type="button" value="Reporte" class="btn-sm btn-warning "><br/><input type="button" value="QR" class="btn-sm btn-primary" >
                                    <?php }else{?>
                                        <input type="button" class="btn-sm btn-danger del" value="Eliminar" id="e_<?php echo $row->ID_COMP?>" idc="<?php echo $row->ID_COMP?>" t="<?php echo $row->ID_TIPO?>">
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
<div id='formAdd' class="hidden">
    <div class="panel-body">
                <div class="table-responsive">                            
                    <table class="table table-striped table-bordered table-hover" id="dataTables-oc">
                        <thead>
                            <tr>
                                <th>Etiqueta</th>
                                <th>Descripción</th>
                                <th>Tipo</th>
                                <th>Largo<br/>Profundo</th>
                                <th>Ancho</th>
                                <th>Alto</th>
                                <th>Almacen</th>
                                <th>Observaciones</th>
                                <th>Agregar /<br/>Cancelar</th>
                            </tr>
                        </thead>
                        <tfoot>
                            
                        </tfoot>
                        <tbody>
                            <tr>
                                <td><input type="text" size="15" id="et" placeholder="Etiqueta" maxlength="15" minlength="1"></td>
                                <td><textarea rows="5" cols="60" id="desc" placeholder="Descripción" maxlength="100" minlength="1"></textarea></td>
                                <td><SELECT class="selTipo" id="selT">
                                    <option value="none">Seleccione un tipo</option>
                                    <?php foreach($tc as $t):?>
                                        <option value="<?php echo $t->TIPO?>" tipo="<?php echo $t->MEDICION?>"><?php echo $t->NOMBRE?></option>
                                    <?php endforeach;?>
                                </SELECT></td>
                                <td><input type="number" step="any" class="num" min="1" max="100000" id="lg"><br/><br/><label id="esc1"></label></td>
                                <td><input type="number" step="any" class="num" min="1" max="100000" id="an"><br/><br/><label id="esc2"></label></td>
                                <td><input type="number" step="any" class="num" min="1" max="100000" id="al"><br/><br/><label id="esc3"></label></td>
                                <td title="Almacen donde estara el componente">
                                    <select id="selA">
                                        <option value="none">Seleccione el almacen</option>
                                        <?php foreach($alm as $a):?>
                                            <option value="<?php echo $a->ID?>"><?php echo $a->NOMBRE.'-->'.$a->UBICACION?></option>
                                        <?php endforeach;?>
                                    </select>
                                </td>
                                <td><textarea rows="5" cols="60" id="Obs" placeholder="Observaciones" maxlength="300" minlength="1"></textarea></td>
                                <td><input type="button" class="btn-sm btn-success accion" value="Agregar"><br/><br/>
                                    <input type="button" class="btn-sm btn-danger accion" value="Cancelar" ></td>
                            </tr> 
                        </tbody>
                    </table>
                </div>
            </div>
</div>

<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script type="text/javascript">

    var form = document.getElementById('formAdd')
    
   

    $(".mov").click(function(){
        var ln = $(this)
        var it = $(this).attr("it")
        var t = $(this).attr("t")
        var eti = $(this).attr("eti")
        var idc = $(this).attr("idc")
        $.confirm({
            columnClass: 'col-md-6 col-md-offset-3',
            title:"Reubicación el componente",
            content:"Solo se pueden mover componentes del mismo tipo."+
            "<br/> "+
            "<br/> <b>Componente Origen <b>:"+
            "<font color='blue'>"+t + " <b>--> Etiqueta: " + eti + "</font></b>" +
            "<br/><br/>Componente Destino:"+
            
            "<br/><br/> <font color='#01890e'>Lineas Dispobibles</font> : <br/>"+
            "<select class='compp'>"+
            "<option value='0' tipp='0'>Seleccione una Linea</option>"+
            <?php foreach ($infoL as $key):?> 
                <?php if($key->ID_TIPO==2):?>
                   <?php echo "'<option value="."'+".$key->ID_COMP."+' "." tipp="."'+".$key->ID_TIPO."+' ".">".$key->ALMACEN." -- ".$key->TIPO." -- ".$key->ETIQUETA."</option>'"?>+
                <?php endif; ?>
            <?php endforeach;?>
            "</select>" + 
            "<br/><br/><font color='#fc5000 '>Tarimas Disponibles</font>: <br/>"+
            "<select class='comps'>"+
            "<option value='0' tips='0'>Seleccione una Tarima</option>"+
            <?php foreach ($infoT as $key):?> 
                <?php if($key->ID_TIPO==1):?>
                   <?php echo "'<option value="."'+".$key->ID_COMP."+' "." tips="."'+".$key->ID_TIPO."+' ".">".$key->ALMACEN." -- ".$key->COMPP."--".$key->TIPO." -- ".$key->ETIQUETA."</option>'"?>+
                <?php endif; ?>
            <?php endforeach;?>
            "</select>" + 
            "<br/><br/><label> Recuerda que solo se puede reubicar Tarimas a Tarimas y Lineas a Lineas.</label> "+
            "<br/><br/> <label> Se tomara el valor del tipo de componente Original. </label" 
            ,
            buttons:{
                Aceptar:function(){
                    var compp = this.$content.find('.compp').val();
                    var tcompp = $('option:selected', this.$content.find('.compp')).attr('tipp')
                    var comps = this.$content.find('.comps').val();
                    var tcomps = $('option:selected', this.$content.find('.comps')).attr('tips')
                    if(compp == 0 && comps == 0){
                        $.alert("Debe seleccionar un componente")
                        return false
                    }
                    if(tcompp > 0 && tcomps > 0){
                        $.alert("Solo puedes seleccionar una opción, recuerda que solo se mueven del miso tipo")
                        return false
                    }
                    if(tcompp > 0 && it != tcompp){
                        $.alert("No puedes reubicar Tarimas a Lineas, selecciona la Tarima Destino")
                        return false
                    }
                    if(tcomps > 0 && it != tcomps){
                        $.alert("No puedes reubicar Lineas a Tarimas, selecciona la Linea Destino")
                        return false
                    }
                    $.ajax({
                        url:'index.wms.php',
                        type:'post', 
                        dataType:'json', 
                        data:{reasig:idc, compp, comps, it},
                        success:function(data){
                            if(data.status == 'ok'){
                                ln.hide()
                                $("."+idc).hide()
                            }
                        },
                        error:function(){
                            //c.val(o)
                        }
                    });
                },
                Cancelar:function(){
                     
                }
            }
        })
       
    })

    $(".chgDesc").change(function(){
        var t = $(this).attr("t")
        var o = $(this).attr("o")
        var c = $(this)
        var d = $(this).val()
        var idc = $(this).attr("c")
        $.confirm({
            title:"Cambio de " + t,
            content:"Se cambiara la descripcion del componente" + d,
            buttons:{
                Aceptar:function(){
                    $.ajax({
                        url:'index.wms.php',
                        type:'post', 
                        dataType:'json', 
                        data:{chgComp:1, idc, d, t},
                        success:function(data){
                            if(data.status == 'ok'){
                                c.val(d)
                            }
                        },
                        error:function(){
                            c.val(o)
                        }
                    });
                },
                Cancelar:function(){
                    c.val(o) 
                }
            }
        })
    })

    $(".del").click(function(){
        var id = $(this).attr('idc')
        var t = $(this).attr('t')
        $.confirm({
            title: 'Eliminar componente!',
            content: 'Solo se pueden eliminar componentes sin movimientos!',
            buttons: {
                Aceptar: function () {
                    $.ajax({
                        url:'index.wms.php',
                        type:'post',
                        dataType:'json',
                        data:{delComp:1, id, t},
                        success:function(data){
                            if(data.status == 'ok'){
                                $("#e_"+id).hide()
                                document.getElementById(id).classList.add('hidden')
                                document.getElementById("c_"+id).classList.add('hidden')
                            }else if(data.status== 'no'){
                                $.alert("Se encontraron movimientos o dependencias del componente")                                
                            }else if(data.status=='p'){
                                $.alert("El componente primario tiene asociaciones, hay que eliminar las asociaciones antes.")                                
                            }
                        },
                        error:function(){
                            $.alert('Ocurrio un error, favor de actualizar su pantalla he intentarlo nuevamente')
                        }
                    })
                },
                Cancelar: function () {
                    $.alert('No se realizo ninguna acción.');
                },
                //somethingElse: {
                //    text: 'Something else',
                //    btnClass: 'btn-blue',
                //    keys: ['enter', 'shift'],
                //    action: function(){
                //        $.alert('Something else?');
                //    }
                //}
            }
        });
    })

    $(".filtro").click(function(){
        var out = $(this).val()
        var o = ''
        var t = $(".ftcomp").val()
        var a = $(".falma").val()
        var p = $(".fprod").val()
        var e = $(".fsta").val()
        var as = $('input:radio[name=aso]:checked').val()
        var fi = document.getElementById('fi').value
        var ff = document.getElementById('ff').value
        //$.alert('Se filtra por los siguientes valores' + t + a + p + e + as)
        if(out=='"ir"' || out == '"p"'){
            if(out=='"ir"'){
                o='_self'
            }else{
                o='_blank'
            }
            window.open('index.wms.php?action=wms_menu&opc=c{"t":'+t+',"a":'+a+',"p":'+p+',"e":'+e+',"as":'+as+',"out":'+ out+',"fi":"'+fi+'","ff":"'+ff+'"}', o)
        }else{
            var param='{"t":'+t+',"a":'+a+',"p":'+p+',"e":'+e+',"as":'+as+',"out":'+ out+',"fi":"'+fi+'","ff":"'+ff+'"}';
            $.ajax({
                url:'index.wms.php',
                type:'post',
                dataType:'json',
                data:{xlsComp:1, op:'c', param},
                success:function(data){
                    window.open( data.completa , 'download')
                },
                error:function(){
                    $.alert('Algo ocurrio')
                }
            })
        }
    })

    $(".add").click(function(){
        form.classList.remove('hidden')
        document.getElementById('et').focus()
    })

    $(".cancel").click(function(){
        form.classList.add('hidden')
    })

    $(".selTipo").change(function(){
        var sel=$('option:selected',this).attr('tipo')
        if(sel == 'cm' ){
            document.getElementById('esc1').innerHTML='Centimetros'
            document.getElementById('esc2').innerHTML='Centimetros'
            document.getElementById('esc3').innerHTML='Centimetros'
        }else if(sel == 'm'){
            document.getElementById('esc1').innerHTML='Metros'
            document.getElementById('esc2').innerHTML='Metros'
            document.getElementById('esc3').innerHTML='Metros'
        }else{
            document.getElementById('esc1').innerHTML=''
            document.getElementById('esc2').innerHTML=''
            document.getElementById('esc3').innerHTML=''
        }
    })

    $(".accion").click(function(){
        var tipo=$(this).val()
        var et = document.getElementById('et')
        var desc = document.getElementById('desc')
        var selT = document.getElementById('selT')
        var lg = document.getElementById('lg')
        var an = document.getElementById('an')
        var al = document.getElementById('al')
        var alm = document.getElementById('selA')
        var ob = document.getElementById('Obs')
        var fact = $('option:selected',".selTipo").attr('tipo')
        if(tipo == 'Agregar'){
            if(et.value==''||desc.value==''||selT.value=='none'||lg.value==''||an.value==''||al.value==''||alm.value=='none'||ob.value==''){
                alert('Todos los campos deben de tener valor')
                return false;
            }
            $.ajax({
                url:'index.wms.php',
                type:'post',
                dataType:'json',
                data:{addComp:1, et:et.value, desc:desc.value, selT:selT.value, lg:lg.value, an:an.value, al:al.value, alm:alm.value, ob:ob.value, fact},
                success:function(data){
                    alert(data.msg)
                    location.reload(true)
                },
                error:function(){
                    alert('Algo no salio como se esperaba, favor de revisar la informacion')
                }
            })
        }else if(tipo =='Cancelar'){
            tipo.value='';et.value='';desc.value='';selT.value='';lg.value='';an.value='';al.value='';alm.value='';ob.value=''; 
            form.classList.add('hidden')
            return false;    
        }
    })

    $(".dup").click(function(){
        var et = $(this).attr('et')
        var tipo = $(this).attr('tipo')
        var id = $(this).attr('id')
           $.confirm({
            columnClass: 'col-md-8',
            title: 'Duplicar Componente'+'<font color="red"> '+ tipo +' ' + et +'  </font> ',
            content: 'Como quiere el consecutivo?' + 
            '<form action="index.php" class="formName">' +
            '<div class="form-group">'+
            '<br/>Consecutivo de Etiqueta: '+
                            '<select name="cns" class="cns">'+
                            '<option value="none">Seleccione un Tipo</option>'+
                            '<option value="le">Letra</option>'+
                            '<option value="nu">Numero</option>'+
                            '<option value="am">Ambos</option>'+
                            '<option value="ni">Ninguno</option>'+
                            '</select>'+
                            ' Cuantas copias ? <input type="number" step="1" class="num canr" min="1" max="1000">'+
                            '<br/>'+
                            '<br/> Letra: <input type="text" maxlength="10" size="12" class="ser" placeholder="letra">'+
                            ' Separador <select class="sep"><option value="-">-</option><option value="/">/</option><option value="&">&</option><option value=" ">Ninguno</option></select>'+
                            ' Número inicial: <input type="number" min="1"  class="num fol" > .<br/>'+
                            '<br/><b>Ejemplo:</b><br/> <font color="blue"><b>Letra:</b></font> A1, B1, C1, D1 ....'+
                            '<br/><b> <font color="blue">Numero:</font></b> A2, A3, A4, A5 ....</b>'+
                            '<br/><b> <font color="blue">Ambos:</font></b> A1, B2, C3, D4 ....</b>'+
                            '<br/><b> <font color="blue">Ninguno:</font></b> A1, A1, A1... </b>'+
                            '<br/><br/><b>Se puede cambiar posteriormente.</b>'+
            '</form>',
                buttons: {
                formSubmit: {
                text: 'Duplicar',
                btnClass: 'btn-blue',
                action: function () {
                    var cns = this.$content.find('.cns').val();
                    var can = this.$content.find('.canr').val();
                    var ser = this.$content.find('.ser').val();
                    var fol = this.$content.find('.fol').val();
                    var sep = this.$content.find('.sep').val();

                    if(cns=='none' ){
                        $.alert('Debe de colocar un tipo de consecutivo o ninguno...');
                        return false;
                    }else if(cns == 'le' && ser == ''){
                        $.alert('Se te olvido darme la letra que quieres.');
                        return false;    
                    }else if(cns == 'nu' && fol <=0){
                        $.alert('Dame el primer numero.');
                        return false;    
                    }else if(cns == 'am' && fol <= 0 && ser ==''){
                        $.alert('Necesito la letra y numero inicial')
                        return false;    
                    }else if(can =='' || can <= 0){
                        $.alert('La cantidad debe de ser un valor mayor a 0 el valor de la cantidad es ' + can);
                        return false;    
                    }else{
                        $.ajax({
                            url:'index.wms.php',
                            type:'post',
                            dataType:'json',
                            data:{cpComp:1, cns, can, id, ser, fol, sep},
                            success:function(data){
                                alert(data.msg);
                                location.reload(true)
                            }
                        });
                    }
                   }
            },
            cancelar: function () {
            },
        },
        onContentReady: function () {
            // bind to events
            var jc = this;
            //alert(jc);
            this.$content.find('form').on('submit', function (e) {
                // if the user submits the form by pressing enter in the field.
                e.preventDefault();
                jc.$$formSubmit.trigger('click'); // reference the button and click it
            });
        }
    });
    })

    $(".sta").change(function(){
        var mensaje =''
        var sta = $(this).val()
        var comp = $(this).val()
        if(sta==0){
            mensaje='Al dar de baja el componeNte, no se podra hacer mas movimientos de entrada...'
        }else{
            mensaje='Al dar de alta el componente se podra ingresar mercancia (productos) nuevamente'
        }

        $.confirm({
            title: 'Alta / Baja de componente.',
            content: mensaje,
            buttons:{
                Si : function(){
                    $.ajax({
                        url:'index.wms.php',
                        type:'post',
                        dataType:'json',
                        data:{staComp:comp, sta}, 
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
   
    $(".asocia").click(function(){
        var et = $(this).attr('et')
        var tipo = $(this).attr('tipo')
        var id = $(this).attr('idc')
        var t = 's'
        var e = 0
        var selec = cheks()
        var cont = ''
        if(selec['c']>1){
            cont='<b> Se ha detectado una seleccion de componentes, todos los componentes seran afectados </b>'
            id = selec['lista']; t='m'; e=selec['c'];
        }else{
            cont='Asociar <b>' + tipo +' --- '+ et +'</b> '
        }
           $.confirm({
            columnClass: 'col-md-8',
            title: 'Asociacion de componente Secundario con Componente Primario',
            content: cont + ' '+ 
            '<form action="index.php" class="formName">' +
            '<div class="form-group">'+
            '<br/>Asocias con : '+
                            '<select name="cns" class="cns">'+
                                    '<option value="none">Seleccione un componente primario</option>'+
                                    '<?php foreach($compP as $cp):?>'+
                                    '<option value="<?php echo $cp->ID_COMP?>"><?php echo $cp->ETIQUETA." - ".$cp->TIPO?></option>'+
                                    '<?php endforeach;?>'+
                            '</select>'+
                            '<br/><br/><b>Se puede cambiar posteriormente.</b>'+
            '</form>',
                buttons: {
                formSubmit: {
                text: 'Asociar',
                btnClass: 'btn-blue',
                action: function () {
                    var cp = this.$content.find('.cns').val();
                    if(cp=='none' ){
                        $.alert('Favor de seleccionar un componente primario valido...');
                        return false;
                    }else{
                        $.ajax({
                            url:'index.wms.php',
                            type:'post',
                            dataType:'json',
                            data:{asocia:id, cp, t, e},
                            success:function(data){
                                alert(data.msg);
                                location.reload(true)
                            }
                        });
                    }
                   }
            },
            cancelar: function () {
            },
        },
        onContentReady: function () {
            // bind to events
            var jc = this;
            //alert(jc);
            this.$content.find('form').on('submit', function (e) {
                // if the user submits the form by pressing enter in the field.
                e.preventDefault();
                jc.$$formSubmit.trigger('click'); // reference the button and click it
            });
        }
    });
    })

    function cheks(){
        var lista = '';
        var c = 0;
        $("input[name=componentes]").each(function (index) { 
           if($(this).is(':checked')){
              lista+= ','+$(this).val();
              c++;
           }
        });
        return {lista, c};
    }
    
</script>