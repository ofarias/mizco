<br /><br />
<style type="text/css">
    .num {
        width: 5em;
    }
    .ui-dialog {
        background: #b6ff00;
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
                                            <th title="Asignacion Total de la linea"> A/T </th>
                                            <th> Orden</th>
                                            <th> Clave <br><font color="purple">Clave SKU</font></th>
                                            <th> Producto </th>
                                            <th> Piezas Totales <!--<br/> Existencias AL-PT --></th>
                                            <th> Color </th>
                                            <th> Cedis </th>
                                            <th> Piezas <br/>Asignadas </th>
                                            <th> <font color='blue'>SKU</font> <br/><font color="green"> ITEM</font></th>
                                            <th> Finalizar </th>
                                            
                                        </tr>
                                    </thead>
                                  <tbody>
                                    <?php $ln=0; foreach ($orden as $ord): 
                                        $color='';$ln++;$status= '';
                                        switch ($ord->STATUS) {
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
                                                    prod="<?php echo $ord->PROD?>" 
                                                    class="asg" 
                                                    t="a" 
                                                    value="<?php echo $ord->PZAS?>"
                                                    ln="<?php echo $ln?>" 
                                                    c="<?php echo $ord->PZAS?>" 
                                                    s="<?php echo $ord->ASIG?>" 
                                                    lin = "<?php echo $ln?>"
                                                    >
                                                <?php } ?>

                                            </td>
                                            <td><?php echo $ord->ORDEN?></td>
                                            
                                            <td><text id="new_<?php echo $ord->PROD?>"><?php echo $ord->PROD?></text>
                                                <br/>
                                                <a title="Actualizar" class="actProd"  prod="<?php echo $ord->PROD?>" prodn="<?php echo $ord->PROD_SKU?>"><font color="purple" > <?php echo $ord->PROD_SKU ?></font> </a>
                                                <?php if($ord->PZAS <> $ord->ASIG){?>
                                                <input type="text" id="rem_<?php echo $ord->PROD?>" class="chgProd hidden" placeholder="Remplazar" prod="<?php echo $ord->PROD?>">
                                                <br/>

                                                <a title="Reemplazar el producto" class="reemp" p="<?php echo $ord->PROD?>">Remplazar</a>
                                                <?php }?>

                                            </td>
                                            <td id="det_<?php echo $ln?>"><b><text id="newD_<?php echo $ord->PROD?>"><?php echo $ord->DESCR?></text></b>
                                                <label class="det" 
                                                    prod="<?php echo $ord->PROD?>" 
                                                    ln="<?php echo $ln?>" 
                                                    id="det+_<?php echo $ln?>">+</label>
                                                <label class="detm hidden" ln="<?php echo $ln?>" id="det-_<?php echo $ln?>">-</label>


                                            </td>
                                            <td align="right" ><b><?php echo number_format($ord->PZAS)?></b>&nbsp;&nbsp;&nbsp;
                                                <!--<?php if( ($ord->PZAS - $ord->ASIG) > 0){?>
                                                <br/><input type="text" placeholder="Asignar" id="asig_<?php echo $ln?>" >&nbsp;&nbsp;<input type="button" class="btn-sm btn-success asg" value="&#x23f5" id="<?php echo $ord->PROD?>" ln="<?php echo $ln?>" c="<?php echo $ord->PZAS?>" s="<?php echo $ord->ASIG?>" t="a">
                                                <?php } ?>-->
                                            </td>
                                            <td><input type="text" name="" value="<?php echo $ord->COLOR?>" placeholder="Seleccione Color"></td>
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
                                                    id="<?php echo $ord->PROD?>" 
                                                    ln="<?php echo $ln?>" 
                                                    c="<?php echo $ord->PZAS?>" 
                                                    s="<?php echo $ord->ASIG?>" 
                                                    t="q">
                                                <?php }?>

                                            </td>
                                            <td><?php echo '<font color="blue">'.$ord->UPC.'<br/></font> <br/><font color="green">'.$ord->ITEM.'</font>'?></td>
                                            <td>
                                                <a href="index.wms.php?action=detOrden&orden=<?php echo $ord->ID_ORD?>" target="popup" onclick="window.open(this.href, this.target, 'width=800,height=600'); return false;"> Finalizar</a></td>
                                            
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

    $(".reemp").click(function(){
        var p = $(this).attr('p')
        document.getElementById("rem_"+p).classList.remove('hidden')
    })

    $(".asg").click(function(){    
        var t = $(this).attr('t'); var msg = '';var lin= $(this).attr('lin')
        if(t == 'a'){
            var prod = $(this).attr('prod')
            var pza = $(this).val()
        }else{
            var ln = $(this).attr('ln') 
            var pza = $("#asig_"+ln).val() // valor a trabajar
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
            content: "Se asignaran " + pza + " piezas del producto " + prod + " de la Orden " + ord + "<br/> "+ msg + "<p><font size='1.5 px'><b>Puede usar Enter para Si y ESC para No</b></font></p>", 
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
                                'id="'+ prod +'<?php echo $ord->PROD?>"' +
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
                        '&nbsp;&nbsp; <input type="text" placeholder="Cantidad" size="6" class="asgLn" idOC="'+idOC+'" value="'+asigC+'" id="asl'+idOC+'" org="'+asigC+'" >'+
                        '&nbsp;&nbsp; <a class="chgLin" idOC="'+idOC+'" c="'+pzasC+'" > + </a>'
                    }
                document.getElementById("det+_"+ln).classList.add('hidden')
                document.getElementById("det-_"+ln).classList.remove('hidden')
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
        $("body").on("click",".chgLin", function(e){
        e.preventDefault();
        var ln =$(this).attr('idOC')
        var c = $(this).attr('c')
        //var c = $(this).val()
        //var org =$(this).attr('org')
        $.confirm({
            columnClass: 'col-md-8',
            title: 'Titulo',
            content: 'Desea Cambiar el producto de cedis' + ln + ' por el producto '+
            '<form action="index.php" class="formName">' +
            '<div class="form-group">'+
            '<br/> <label>Producto: </label><input type="text" size="25" class="chgProd2">' +
            '<br>Si no requiere cambio dejar en blanco.'+
            '<br/><br/> <label>Asignar Colores a '+c+' piezas: </label>'+
            '<br/><br/><p class="hidden" id="colAsig">Asignado:  <label id="cntCol"></label></p>'+ 
            '<br>La Cantidad deber ser la exacta para poder asignar los colores.'+
            '<br/><br/> <font color="blue">Azul</font>: &nbsp;&nbsp;&nbsp; <input type="text" placeholder="Cantidad" class="colores az" col="azul">'+
            '<br/><br/> Blanco: <input type="text" placeholder="Cantidad"  class="colores bl" col="blanco">'+
            '<br/><br/> Negro:&nbsp; <input type="text" placeholder="Cantidad"  class="colores ng" col="negro">'+
            '<br/><br/> <font color="#FD95FB ">Rosa:</font>&nbsp; <input type="text" placeholder="Cantidad"  class="colores ro" col="rosa">'+
            '<br/><br/> <font color="red">Rojo</font>:&nbsp;&nbsp;&nbsp; <input type="text" placeholder="Cantidad" class="colores rj" col="rojo">'+
            '<br/><br/> <font color="gray">Gris</font>:&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" placeholder="Cantidad" class="colores gr" col="gris">'+
            '<br/><br/> <font color="#009F0E">Verde</font>:&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" placeholder="Cantidad" class="colores vd" col="verde">'+
            '</form>',
            buttons: {
            formSubmit: {
            text: 'Asignar',
            btnClass: 'btn-blue',
            action: function () {
                    var prodN = this.$content.find('.chgProd2').val();
                    var az = parseFloat(this.$content.find('.az').val());
                    var bl = parseFloat(this.$content.find('.bl').val());
                    var ng = parseFloat(this.$content.find('.ng').val());
                    var ro = parseFloat(this.$content.find('.ro').val());
                    var rj = parseFloat(this.$content.find('.rj').val());
                    var gr = parseFloat(this.$content.find('.gr').val());
                    var vd = parseFloat(this.$content.find('.vd').val());
                    if(typeof az === "undefined" || isNaN(az)){az = 0;}
                    if(typeof bl === "undefined" || isNaN(bl)){bl = 0;}
                    if(typeof ng === "undefined" || isNaN(ng)){ng = 0;}
                    if(typeof ro === "undefined" || isNaN(ro)){ro = 0;}
                    if(typeof rj === "undefined" || isNaN(rj)){rj = 0;}
                    if(typeof gr === "undefined" || isNaN(gr)){gr = 0;}
                    if(typeof vd === "undefined" || isNaN(vd)){vd = 0;}
                    var t = az+bl+ng+ro+rj+gr+vd;
                    if(t<c){
                        $.alert('Debe de colocar el total de colores faltan ' + (c-t) + ' piezas por asignar color.');
                        return false;
                    }else if(t>c){
                        $.alert('Se estan asignando mas piezas de las necesarias favor de revisar ' );
                        return false;
                    }else{
                        $.ajax({
                            url:'index.wms.php',
                            type:'post',
                            dataType:'json',
                            data:{asigCol:1, ln, az, bl, ng, ro, rj, gr, vd, prodN},
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
            $(".chgProd2").autocomplete({
                source: "index.wms.php?producto=1",
                minLength: 2,
                select: function(event, ui){
                }
            })
        }
        });
        })
    })

    
</script>
