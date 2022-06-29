
<style type="text/css">
    .num {
        width: 5em;
    }
</style>
<br/>


</div>
<br/>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Detalle del Movimiento <b><?php echo $op?></b>
            </div>
            <div class="panel-body">
                <div class="table-responsive">                            
                    <table class="table table-striped table-bordered table-hover" id="dataTables-componentes">
                        <thead>
                            <tr>
                                <th>Almacen</th>
                                <th>Tipo</th>
                                <th>Usuario</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Hora Inicio</th>
                                <th>Hora Final</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Unidad</th>
                                <th>Piezas</th>
                                <th><font color="blue">Linea</font> /<font color="green"> Tarima</font> </th>
                                
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td colspan="14"></td>
                                <!--
                                <td><a target="_blank" href="index.php?action=imprimircatgastos" class="btn btn-info">Imprimir <i class="fa fa-print"></i></a></td>
                                <td><button class="btn-sm btn-info add" >Agregar &nbsp;<i class="fa fa-plus"></i></button></td>-->
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php $i=0;foreach($info as $mov): $i++; 
                                
                            ?>
                            <tr class="color" id="linc<?php echo $i?>">
                                <td><?php echo $mov->ALMACEN?></td>
                                <td><?php echo $mov->TIPO?></td>
                                <td><?php echo $mov->USUARIO?></td>
                                <td><?php echo $mov->FECHA?></td>
                                <td><?php echo $mov->STATUS?></td>
                                <td><?php echo $mov->HORA_I?></td>
                                <td><?php echo $mov->HORA_F?></td>
                                <td><?php echo $mov->PROD?></td>
                                <td><?php echo $mov->CANT?></td>
                                <td><?php echo $mov->UNIDAD?></td>
                                <td><?php echo $mov->PIEZAS?></td>
                                <td><font color="blue"><?php echo $mov->COMPP?></font>
                                    <br/><font color="green"><?php echo $mov->SECUNDARIO?></font>
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

    $(".filtro").click(function(){
        var t = $(".ftcomp").val()
        var a = $(".falma").val()
        var p = $(".fprod").val()
        var e = $(".fsta").val()
        var as = $('input:radio[name=aso]:checked').val()
        $.alert('Se filtra por los siguientes valores' + t + a + p + e + as)
        window.open('index.wms.php?action=wms_menu&opc=c{"t":'+t+',"a":'+a+',"p":'+p+',"e":'+e+',"as":'+as+'}', '_self')
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
        var id = $(this).attr('id')
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