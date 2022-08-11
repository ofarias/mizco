<br /><br />
<style type="text/css">
    .num {
        width: 5em;
    }
    .ui-dialog {
        background: #b6ff00;
    }

    .boton{
        font-size:8px;
        font-family:Verdana,Helvetica;
        font-weight:bold;
        color:white;
        background:#638cb5;
        border:0px;
        width:20px;
        height:14px;
       }
</style>

<div class="row">
    <div class="col-lg-12">
        <div>Detalles del Documento: <label><?php echo $cabecera->ARCHIVO?> </label><br/>Para el Cliente: <label><?php echo $cabecera->CLIENTE?></label>  <?php echo !empty($cabecera->ORDEN)? '<br/>Incluye las ordenes:<label>'.$cabecera->ORDEN.'</label>':''?></label><br/>Usuario: <label><?php echo $_SESSION['user']->NOMBRE?></label></div>
            <br/>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
            <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover" id="dataTables-detOrd">
                                    <thead>
                                        <tr>
                                            <th title="Asignacion Total de la linea"> A/T </th>
                                            <th> Orden</th>
                                            <th> Clave <br><font color="purple">Clave SKU</font></th>
                                            <th> Producto </th>
                                            <th> Piezas Totales <!--<br/> Existencias AL-PT --></th>
                                            <!--<th> Color </th>-->
                                            <th> Cedis </th>
                                            <th> Piezas <br/>Asignadas </th>
                                            <th> <font color='blue'>SKU</font> <br/><font color="green"> ITEM</font></th>
                                            <th> Finalizar </th>
                                            
                                        </tr>
                                    </thead>
                                  <tbody>
                                    <?php $ln=0; foreach ($orden as $ord): 
                                        $color='';$ln++;$status= '';
                                        switch ($ord->ID_STATUS) {
                                            case 2:
                                                $color="style='background-color: #e2ffd8;'";$status= 'Asignado';
                                                break;
                                            case 3:
                                                $color="";$status= 'Preparado';
                                                break;
                                            case 4:
                                                $color="style='background-color: #fff5bc;'";$status= 'Modificado';
                                                break;
                                            case 5:
                                                $color="style='background-color: #fff0ee;'";$status= 'Pendiente';
                                                break;
                                            case 6:
                                                $color="style='background-color: #d7fffe;'";$status= 'Surtido Parcial';
                                                break;    
                                            case 7:
                                                $color="style='background-color: #9bfffd;'";$status= 'Surtido Total';
                                                break;        
                                            case 8:
                                                $color="style='background-color: #f3e4d8;'";$status= 'Cambio de color';
                                                break;        
                                            default:
                                                $color ="";
                                                break;
                                        }
                                        if(empty($ord->DESCR)){
                                            $color = "style='background-color: #FFF7C6;'";
                                        }
                                        ?>
                                       <tr class="odd gradeX color" <?php echo $color?> title="<?php echo $status?>" id="col_<?php echo $ln?>"> 
                                            
                                            <td title="Asignacion Total de la linea">
                                                
                                                <?php if($ord->ASIG == 0){?>
                                                <input type="checkbox" 
                                                    name="selector" 
                                                    prod="<?php echo htmlspecialchars($ord->PROD)?>" 
                                                    class="sel" 
                                                    t="a" 
                                                    value="<?php echo $ord->PZAS?>"
                                                    ln="<?php echo $ln?>" 
                                                    c="<?php echo $ord->PZAS?>" 
                                                    s="<?php echo $ord->ASIG?>" 
                                                    lin = "<?php echo $ln?>"
                                                    >
                                                    <br/><br/>
                                                    <input type="button" class="boton asgM" value="&#x23f5">
                                                <?php } ?>

                                            </td>
                                            <td><?php echo $ord->ORDEN?></td>
                                            
                                            <td><text id="new_<?php echo htmlspecialchars($ord->PROD)?>"><?php echo htmlspecialchars($ord->PROD)?></text>
                                                <br/>
                                                <a title="Actualizar" class="actProd"  prod="<?php echo htmlspecialchars($ord->PROD)?>" prodn="<?php echo $ord->PROD_SKU?>"><font color="purple" > <?php echo $ord->PROD_SKU ?></font> </a>
                                                <?php if($ord->PZAS <> $ord->ASIG){?>

                                                    <input type="text" id="rem_<?php echo htmlspecialchars($ord->PROD)?>" class="chgProd hidden" placeholder="Remplazar" prod="<?php echo htmlspecialchars($ord->PROD)?>" ln="<?php echo $ln?>">
                                                    
                                                    <select class="hidden chgProd" id="pres_<?php echo htmlspecialchars($ord->PROD)?>" prod="<?php echo htmlspecialchars($ord->PROD)?>">
                                                            <option>Seleccione la presentacion</option>
                                                    </select>
                                                    
                                                    <br/>
                                                    <!--<a title="Reemplazar el producto" class="reemp" p="<?php echo htmlspecialchars($ord->PROD)?>" art="<?php echo htmlspecialchars($ord->PROD)?>" >Remplazar</a>-->

                                                <?php }?>

                                            </td>



                                            <td id="det_<?php echo $ln?>"><b><text id="newD_<?php echo htmlspecialchars($ord->PROD)?>"><?php echo $ord->DESCR?></text></b>
                                                <label class="det" 
                                                    prod="<?php echo htmlspecialchars($ord->PROD)?>" 
                                                    ln="<?php echo $ln?>" 
                                                    id="det+_<?php echo $ln?>">+</label>
                                                <label class="detm hidden" ln="<?php echo $ln?>" id="det-_<?php echo $ln?>">-</label>
                                            </td>



                                            <td align="right" ><b><?php echo number_format($ord->PZAS)?></b>&nbsp;&nbsp;&nbsp;
                                            </td>
                                            <td><?php echo $ord->CEDIS?></td>
                                            <td align="right" id="casig_<?php echo $ln?>"><?php echo number_format($ord->ASIG)?>
                                                <?php if($ord->ASIG > 0){?>
                                                    <br/>
                                                    <input type="text" 
                                                    placeholder="Quitar" 
                                                    id="colasig_<?php echo $ln?>">
                                                    &nbsp;&nbsp;
                                                    <input type="button" 
                                                    class="btn-sm btn-success asg" 
                                                    value="&#x23f5" 
                                                    id="<?php echo htmlspecialchars($ord->PROD)?>" 
                                                    ln="<?php echo $ln?>" 
                                                    c="<?php echo $ord->PZAS?>" 
                                                    s="<?php echo $ord->ASIG?>" 
                                                    t="q">
                                                <?php }?>
                                                <p id="<?php echo $ord->PROD.'|'.$ord->ID_ORD?>" class="asigProd" asig="<?php echo $ord->ASIG?>"> <?php echo $ord->ID_ORD?></p>

                                                <p class="sincInt" t="i" ln="<?php echo $ord->ID_ORDD?>"><font color="blue">Asignado Intelisis: </font><b><?php echo $ord->INTELISIS?></b></p>

                                            </td>
                                            <td><?php echo '<font color="blue">'.$ord->UPC.'<br/></font> <br/><font color="green">'.$ord->ITEM.'</font>'?></td>
                                            <td>
                                                <a class="finA" lin="<?php echo $ln?>" p="<?php echo htmlspecialchars($ord->PROD)?>"> Finalizar</a></td>
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

    $("body").on("click",".sincInt", function(e){
        e.preventDefault()
        var t = $(this).attr("t")
        var ln = $(this).attr("ln")
        if(t == 'i'){
            var contenido = "Deseas <b> traer </b> los valores de intelisis? los datos se sobreescribiran" 
        }else if (t == 'w'){
            var contenido = "Deseas <b>enviar</b> los valores a intelisis? los datos se sobreescribiran"
        }
        $.confirm({
            title: 'Sincronización Intelisis',
            content: contenido, 
            buttons:{
                aceptat:{
                    text: 'Aceptar',
                    btnClass: 'btn-blue',
                    keys: ['enter'],
                    action: function(){
                        $.alert("intentamos enviar la informacion a intelisis, solo si el producto es el mismo.")
                        $.ajax({
                            url:'index.wms.php',
                            type:'post', 
                            dataType:'json',
                            data:{sincInt:1, ln, t}, 
                            success:function(data){
                            }, 
                            error:function(){
                            }
                        })
                    }
                }
                ,
                Cancelar: function(){
                    return;
                }
            }
        })
    })

    $(".finA").click(function(){
        var lin = $(this).attr('lin')
        var p = $(this).attr('p')
        $.confirm({
            columnClass: 'col-md-8',
            title: 'Concluir Asignación',
            content: 'Desaea finalizar la linea o finalizar la orden?',
            buttons:{
                si:{
                    text:'Producto',
                    keys:['enter', 'p', 'P'],
                    action:function(){
                        $.ajax({
                            url:'index.wms.php',
                            type:'post',
                            dataType:'json',
                            data:{finA:1, ord, t:'l', p}, 
                            success:function(data){
                                if(data.status == 'ok'){
                                    $.alert(data.msg)
                                }else{
                                    $.alert(data.msg)
                                }
                            },
                            error:function(){
                                $.alert("favor de actualizar")
                            }
                        })
                    }
                },
                orden:{
                    text:'Todo',
                    keys:['o', 'O'], 
                    action:function(){
                        $.ajax({
                            url:'index.wms.php',
                            type:'post',
                            dataType:'json',
                            data:{finA:1, ord, t:'o', p}, 
                            success:function(data){
                                if(data.status = 'ok'){
                                    $.alert({
                                        title: 'Asignacion de Productos',
                                        content: 'Se ha finalizado la orden.',
                                        buttons:{
                                            OK:{
                                                text:'Ok',
                                                keys:['enter']
                                            }
                                        }
                                    })
                                    location.reload()
                                }else{
                                    $.alert({
                                        title: 'Al parecer hay productos pendientes por Asignar',
                                        content: 'Alguno de los productos aún no tienen asignación.',
                                        buttons:{
                                            OK:{
                                                text:'Ok',
                                                keys:['enter']
                                            }
                                        }
                                    })
                                }
                            },
                            error:function(){
                                $.alert("favor de actualizar")
                            }
                        })
                        //$.alert("finaliza la orden completa, debe de validar que esten todos los productos asignados.")
                        //window.close()
                    }
                },
                cancelr:{
                    text:'Cancelar',
                    keys:['esc'],
                    action:function(){
                        $.alert("no se realiza cambio.")
                    }
                }
            }
        })
    })

    /*
    $(".chgProd").autocomplete({
        source: "index.wms.php?producto=1",
        minLength: 2,
        select: function(event, ui){
        }
    })
    */
    
    $(".chgProd").change(function(){
        var p = $(this).attr('prod')
        var nP = $(this).val()
        var descr = $('select[id="pres_'+p+'"] option:selected').text()
        //var a = $(this)
        //var ln = $(this).attr('ln')
        //var nP = $(this).val()
        //var p = $(this).attr('prod')
        //nP = nP.split(":")
        $.confirm({
            title: 'Cambio de producto',
            content: 'Desea Cambiar el producto ' + p+ ' por el producto '+ nP ,
            buttons:{
                Si:{
                    text:"Si",
                    keys:['enter', 's','S'],
                    action:function(){
                        $.ajax({
                            url:'index.wms.php',
                            type:'post',
                            dataType:'json',
                            data:{chgProd:1, p, nP, oc:ord, t:'p'}, 
                            success:function(data){
                                if(data.status=='ok'){
                                    document.getElementById('new_'+p).innerHTML=nP
                                    document.getElementById('newD_'+p).innerHTML=descr
                                    document.getElementById('det+_'+ln).setAttribute('prod', nP)
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

    $(".reemp").click(function(){
        var p = $(this).attr('p')
        //var ln = document.getElementById("rem_"+p)
        var lnS = document.getElementById("pres_" + p) // este es el selector
        //ln.classList.remove('hidden')
        lnS.classList.remove('hidden')
        lnS.focus()        
        var art = $(this).attr('art')
        $.ajax({
            url:'index.wms.php',
            type:'post',
            dataType:'json',
            data:{sincPres:art},
            success:function(data){
                for(const [key, value] of Object.entries(data.datos)){
                        for (const [k, val] of Object.entries(value)){
                            if(k == 'presentacion'){var art = val;}
                            if(k == 'descripcion1'){var descr = val;}
                        }
                        $("#pres_"+ p).prepend("<option value='"+ art +"'>"+ art +" - "+descr+"</option>");
                }
            },
            error:function(){
            }
        })
        
    })

    $(".asg").click(function(){    
        var t = $(this).attr('t'); var msg = '';var lin= $(this).attr('lin')
        if(t == 'a'){
            var prod = $(this).attr('prod')
            var pza = $(this).val()
        }else{
            var ln = $(this).attr('ln') 
            //var pza = $("#asig_"+ln).val() // valor a trabajar
            var pza = $("#colasig_"+ln).val()
            var prod = $(this).attr('id')
        }
        var c = $(this).attr('c')
        var s = $(this).attr('s')
        var pen = c - s;
        var th = $(this)
        //alert("valor de pieza" + pza)
        if (pza < pen && t == 'a'){
            msg = 'Se asignaran las piezas por cedis y se dejara el ultimo imcompleto?'
        }else if(pza > pen && t == 'a'){
            msg = 'Se asignan mas piezas de las necesarias, el sobrante simplemente no se asignara, '
        }else if(pza > pen && t == 'q'){
            msg = 'Se desasignaran las piezas por cedis y se dejara el ultimo imcompleto?'
        }else if(pza < pen && t == 'q'){
            msg = 'Se desasignaran las piezas'
        }
        $.confirm({
            title: "Asignacion de Productos",
            //content: "Se asignaran " + pza + " piezas del producto " + prod + " de la Orden " + ord + "<br/> "+ msg + "<p><font size='1.5 px'><b>Puede usar Enter para Si y ESC para No</b></font></p>", 
            content: msg + "<p><font size='1.5 px'><b>Puede usar Enter para Si y ESC para No</b></font></p>", 
            buttons:{
                Si:{
                    text:'Si',
                    keys:['enter'],
                    action: function(){
                    $.ajax({
                        url:'index.wms.php',
                        type:'post',
                        dataType:'json',
                        data:{asgProd:ord, prod, pza, t, c, s}, 
                        success:function(data){
                            if(data.status== 'ok'){
                                var linea = document.getElementById("col_"+lin)
                                linea.title='Asignado' /// Asignamos el titulo
                                linea.style.background='#e2ffd8' /// Pintamos de verde
                                th.hide() /// desaparecemos el checkbox
                                document.getElementById('casig_'+lin).innerHTML= c +'<br/> <input type="text" placeholder="Quitar" '+
                                'id="colasig_'+lin+'">'+
                                '&nbsp;&nbsp;'+
                                '<input type="button" '+
                                'class="btn-sm btn-success asg" '+
                                'value="&#x23f5" '+
                                'id="'+ prod +'<?php echo htmlspecialchars($ord->PROD)?>"' +
                                'ln="'+ lin +'<?php echo $ln?>" '+
                                'c="'+ c +'"' +
                                's="'+ c +'"'+
                                't="q">'

                                /// actualizamos la columna Asignados y hacemos que aparezcan las opciones de quitar.
                            }else{
                                $.alert("Se recomienda refrescar la pagina")
                            }
                        }
                        },10000 )
                    }
                },  
                No:{
                    text:'No',
                    keys:['esc'],
                    action:function(){
                        th.prop("checked",false);
                        return;
                    }    
                }
                
            }
        })
    })

    $(".asgM").click(function(){
        var prod=''; var pza = 0; var t = 'm'; var c = 0; var s = 0;
        $("input[name=selector]").each(function(index){ 
            if($(this).is(':checked')){
                prod += ':' + $(this).attr('prod');
            }
        });
        $.ajax({
            url:'index.wms.php',
            type:'post', 
            dataType:'json',
            data:{asgProd:ord, prod, pza, t, c, s}, 
            success:function(){
                $.alert("Se han cargado los productos")
            },
            error:function(){
                $.alert("Favor de Actualizar")
            }
        })
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

    $(".detm").click(function(){
        $.alert("se dio click en el menos")

    })

    $(".det").click(function(){
        var prod = $(this).attr("prod")
        var ln = $(this).attr("ln")
        //$.alert('Ver el detalle del producto ' + prod + ' de la Orden ' + ord)
        var det = document.getElementById("det_"+ln)
        $.ajax({
            url:'index.wms.php',
            type:'post',
            dataType:'json',
            data:{detLinOrd:1, ord, prod},
            success:function(data){
                if(data.status == 'ok'){
                    //$.alert('Mensaje de '+  data.datos.values())
                    for(const [key, value] of Object.entries(data.datos)){
                        //console.log(value);
                        for (const [k, val] of Object.entries(value)){
                            //onsole.log(k + ' valor: ' + val);
                            if(k == 'CEDIS'){var nomC = val;}
                            if(k == 'PZAS'){var pzasC = val;}
                            if(k == 'ID_ORDD'){var idOC = val;}
                            if(k == 'ASIG'){var asigC=val;}
                        }
                        det.innerHTML += '<br/>Cedis '+ nomC +': '+ pzasC +
                        '&nbsp;&nbsp; <input type="text" placeholder="Cantidad" size="6" class="asgLn" prod="'+prod+'" idOC="'+idOC+'" value="'+asigC+'" id="asl'+idOC+'" org="'+asigC+'" >'+
                        '&nbsp;&nbsp; <a class="chgLin" prod="'+prod+'" idOC="'+idOC+'" c="'+pzasC+'" > + </a> '+
                        '<a title="No Surtir" class="ns" idOC="'+idOC+'" prod= "'+prod+'">N.S.</a>'
                    }
                document.getElementById("det+_"+ln).classList.add('hidden')
                document.getElementById("det-_"+ln).classList.remove('hidden')
                }
            },
            error:function(){
            }
        })
    })


    $("body").on("change",".asgLn", function(e){
            e.preventDefault();
            var ln =$(this).attr('idOC')
            var c = $(this).val()
            var org =$(this).attr('org')
            $.ajax({
                url:'index.wms.php',
                type:'post',
                dataType:'json', 
                data:{asgLn:1, ln, c}, 
                success:function(data){
                    if(data.status== 'ok'){
                        //$.alert("Se asigno la cantidad")
                    }else{
                        document.getElementById("asl"+ln).value=org
                    }
                }, 
                error:function(){
                        document.getElementById("asl"+ln).value=org
                }
            })
    })

    $("body").on("change", ".colores", function(e){
        e.preventDefault();
        var col = $(this).attr('col');
        var cant = $(this).val();
        //$.alert('Cambio el color' + col + ' a: ' + cant + ' piezas');
        //var asig=document.getElementById("colAsig")
        //asig.classList.remove("hidden")
        ////asig.innerHTML += ':' +cant
        //var cc = document.getElementById("cntCol")
        //cc.innerHTML = cant
    })
    
    $(function(){
        $("body").on("click", ".ns", function(e){
            e.preventDefault();
            var idOC = $(this).attr('idOC')
            var prod = $(this).attr('prod')
            $.confirm({
            columnClass: 'col-md-8',
            title: 'Motivo de no surtido',
            content: 'Por que el producto <b>'+ prod +'</b> no se surtira?' +
            '<form action="index.php" class="formName">' +
            '<div class="form-group">'+
            '<br/> <label>Motivo: </label><input type="text" size="100" class="mot">' +
            '</form>',
            buttons: {
            formSubmit: {
                text: 'Ok',
                keys:['enter'],
                btnClass: 'btn-blue',
                action: function () {
                        var motivo = this.$content.find('.mot').val();
                            $.ajax({
                                url:'index.wms.php',
                                type:'post',
                                dataType:'json',
                                data:{finA:1, ord:idOC, t:'lin', p:motivo},
                                success:function(data){
                                    alert(data.msg);
                                    location.reload(true)
                                }
                            });
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
                onContentReady: function () {
                    var jc = this;
                    this.$content.find('form').on('submit', function (e) {
                        e.preventDefault();
                        jc.$$formSubmit.trigger('click');
                    });
                }
        });
            
        })  

    $("body").on("click",".chgLin", function(e){
        e.preventDefault();
        var ln =$(this).attr('idOC')
        var c = $(this).attr('c')
        var p = 0;
        var art = $(this).attr('prod')
        var opc = ''
        //var c = $(this).val()
        //var org =$(this).attr('org')
            $.ajax({
                url: 'index.wms.php',
                type: 'post',
                dataType: 'json',
                data:{sincPres:art},
                success:function(data){
                    //$.alert('Se obtiene las presentaciones')
                        p=parseFloat(data.datos.length)
                        if(p>0){
                            for(const [key, value] of Object.entries(data.datos)){
                                for (const [k, val] of Object.entries(value)){
                                    if(k == 'presentacion'){var pst = val;}
                                    if(k == 'descripcion1'){var descr = val;}
                                }
                                opc += "<br/><br/>" +pst + ': ' + descr + ' :  <input type="text" placeholder="0" size="3" class="cant" value="0" press="' +pst + '">'
                            }
                            presentaciones(ln, c, art, data.datos, opc)
                        }else{
                            $.alert("NO hay presentaciones del producto")        
                        }
                },
                error:function(){
                    $.alert("NO hay presentaciones del producto")
                }
            })
        })
    })

    function presentaciones(ln, c, prod, datos, opc){
        //$.alert(opc)
        var total = 0 
        var dist = ''
            $.confirm({
                columnClass: 'col-md-8',
                title: 'Cambio de presentacion',
                content: 'Definir el color de los productos: '+
                '<form action="index.php" class="formName">' +
                '<div class="form-group">'+
                '<br/> <label>Producto: '+ prod + '</label>' +
                '<br/><br/> <label>Asignar Colores a '+ c +' piezas: </label>'+
                + "'" +
                    opc
                +"" +
                '<br/><br/><p class="hidden" id="colAsig">Asignado:  <label id="cntCol"></label></p>'+ 
                //'<br>La Cantidad deber ser la exacta para poder asignar los colores.'+
                '</form>',
                buttons: {
                formSubmit: {
                text: 'Asignar',
                btnClass: 'btn-blue',
                action: function () {
                        //var prodN = this.$content.find('.chgProd2').val();
                        $(".cant").each(function (){
                            var valor = $(this).val()
                            if($.isNumeric(valor)){
                                if(valor > 0){
                                    total += parseFloat($(this).val())
                                }else if(valor < 0){
                                    $.alert('Error en la asignacion, favor de revisar')
                                    return false
                                }
                            }else{
                                $.alert('Hay un valor invalido o en blanco, favor de revisar')
                                return false
                            }
                        })
                        //alert("Total : "+ total );
                        /* Asignacion parcial
                        if(total < parseFloat(c)){
                            $.alert('Debe de colocar el total de colores faltan ' + (c-total) + ' piezas por asignar color.');
                            return false;
                        }else 
                        */
                        if(total>c){
                            $.alert('Se estan asignando mas piezas de las necesarias favor de revisar ' );
                            return false;
                        }else{
                            $(".cant").each(function (){
                                dist += $(this).attr('press') + ':' + $(this).val() + '|'
                            })  
                            $.ajax({
                                url:'index.wms.php',
                                type:'post',
                                dataType:'json',
                                //data:{asigCol:1, ln, col, nP:prodN},
                                data:{asigCol:1, ln, col:dist},
                                success:function(data){
                                    alert(data.msg);
                                    //location.reload(true)
                                    if(data.status =='ok'){
                                    // actualizar el valor de los asignados
                                    // quitar el select 
                                    }
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
                    $(".chgProd2").autocomplete({
                        source: "index.wms.php?producto=1",
                        minLength: 2,
                        select: function(event, ui){
                        }
                    })
                }
            });

    }
    
    $("document").ready(function(){
        $(".asigProd").each(function(){
            var info = $(this).attr('id')
            var asig = parseInt($(this).attr('asig'))
            var label = ''
            console.log(info)
            if (asig > 0 ){
                $.ajax({
                    url:'index.wms.php',
                    type:'post', 
                    dataType:'json',
                    data:{pres:info},
                    success:function(data){
                        //console.log(data.inf)
                        for (const [key, value] of Object.entries(data.datos)){
                            for (const [k, val] of Object.entries(value)){
                                if(k == 'NUEVO'){ var nuevo = val;}
                                if(k == 'CANT'){ var cantidad = val;}
                                if(k == 'ID_ORDD'){ var ln = val;}
                            }
                            label += '<br/> <label class="sincInt" t="w" ln = "'+ln+'">Asignado ' + nuevo +  ': ' + cantidad + '</label>';
                        }
                        document.getElementById(info).innerHTML = label
                    }, 
                    error:function(){

                    }
                })
            }
        })
    })

</script>
