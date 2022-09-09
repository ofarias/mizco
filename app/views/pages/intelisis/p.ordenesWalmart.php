<br /><br />
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                                 Seleccionar Archivo:
                        </div>
                           <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover" id="dataTables">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Archivo</th>
                                            <th>Descargar</th>
                                            <th>Traer</th>
                                        </tr>
                                    </thead>
                                  <tbody>
                                       <tr>  
                                       <form action = "index.php" method="post" >
                                            <td><input type="text" name="fechaedo" class = "fecha" value="<?php echo $fechaedo?>" required= "required"></td>
                                            <td><input type="number" step ="any" name="monto" required="required"></td>
                                            <td>
                                                <button name="guardaTransPago" value="enviar" type ="submit" class="btn btn-success"> Ver </button>
                                            </td>
                                        </form>
                                        </tr> 
                                 </tbody>
                                 </table>
                      </div>
            </div>
        </div>
    </div>
</div>
<br />
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                                 Ordenes Walmart
                        </div>
                           <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover" id="dataTables">
                                    <thead>
                                        <tr>
                                            <th>Ln</th>
                                            <th>Fecha Pedido <br/> Intelisis <br/><font color=" #0197a3"> Orden</font></th>
                                            <th><font color="green">Cliente </font><br/>/ Sucursal</th>
                                            <th>Determinante Walmart <br/> Determinante Mizco</th>
                                            <th>Mov<br/>Mov ID</th>
                                            <th>Estatus<br/> Intelisis</th>
                                            <th>Monto</th>
                                            <th>Articulos</th>
                                            <th>Validar</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                  <tbody>
                                   <?php $ln=0; foreach($ordenes as $ord):
                                            $ln++;
                                            $color=""; 
                                    ?>
                                       <tr>  
                                            <td><?php echo $ln ?></td>
                                            <td><?php echo substr($ord->FECHAEMISION,0,10)?><br/><font color=" #0197a3"><?php echo $ord->ORDENCOMPRA?></font></td>
                                            <td align="center"><font color="green"><?php echo $ord->CLIENTE;?></font><br/><a class="enviarA" cte="<?php echo $ord->CLIENTE?>" comp="<?php echo $ord->DETERMINANTE.'-->'.$ord->COMPRADOR.'-->'.$ord->SUB_DETERMINANTE?>" idwms ="<?php echo $ord->ID_INT_F?>" comprador="<?php echo $ord->COMPRADOR?>"> <?php echo $ord->ENVIARA?></a></td>
                                            <td><?php echo $ord->DETERMINANTE.' <font color="purple">'.$ord->SUB_DETERMINANTE.'</font><br/> <font color="blue">'.$ord->DET_INTELISIS.'</font>'?> Lista: <font color="orange"><?php echo $ord->LISTAPRECIOSESP?></font></td>
                                            <td title="<?php echo $ord->ID_INT_F?> / <?php echo $ord->ID_INT?>"><?php echo $ord->MOV;?><br/><?php echo $ord->MOVID?></td>
                                            <td><?php echo $ord->ESTATUS?></td>
                                            <td align="right"><?php echo '$ '. number_format($ord->MONTO,2)?></td>
                                            <td align="center"><a class="art" idwms="<?php echo $ord->ID_INT_F?>"><?php echo $ord->ARTICULOS?></a></td>
                                            <td align="center">
                                                <?php if(empty($ord->MOVID)){?>
                                                    <a class="valInt" idwms="<?php echo $ord->ID_INT_F?>" >validar</a>
                                                <?php }?>
                                            </td>
                                            <td><?php echo ''?></td>
                                        <?php endforeach;?>
                                            </form>
                                        </tr> 
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
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script type="text/javascript">
    
    $(".valInt").click(function(){
        var id = $(this).attr("idwms")
        $.ajax({
            url:'index.order.php',
            type:'post',
            dataType:'json',
            data:{valInt:id},
            success:function(data){
                if(data.status== 'ok'){
                    alert(data.mensaje)
                }else if(data.status=='no'){
                    alert(data.mensaje)
                }
            },
            error:function(){

            }
        })
    })

    $("body").on("click",".enviarA",function(e){
        e.preventDefault();        
        var id=$(this).attr('cte')
        var info = ''
        var comp = $(this).attr('comp')
        var idwms = $(this).attr('idwms')
        var comprador =  $(this).attr('comprador')
        $.ajax({
            url:'index.order.php',
            type:'post',
            dataType:'json',
            data:{enviarA:id},
            success:function(data){
                if(data.ln > 0 ){
                        var lin = 0
                    for(const [key, value] of Object.entries(data.datos)){
                        lin++
                        for(const[k,val] of Object.entries(value)){
                            if(k == '0'){var lpe = val}
                            if(k == '1'){var iddet = val}
                            if(k == '2'){var nom = val}
                            if(k == '3'){var dir = val}
                            if(k == '4'){var obs = val}
                            if(k == '5'){var col = val}
                            if(k == '6'){var del = val}
                            if(k == '7'){var pob = val}
                            if(k == '8'){var est = val}
                            if(k == '9'){var pais = val}
                            if(k == '10'){var cp = val}
                            if(k == '11'){var con = val}
                            if(k == '12'){var cad = val}
                            if(k == '13'){var dirnum = val}
                        }

                        info += '<b>'+ nom +'</b> :'
                        + '<font color ="blue"> ' + obs + '</font> '  
                        + dir + ' ' + dirnum + ' Col: ' + col + ' Del: ' + del + ' Pob: ' + pob 
                        + ' Est: ' + est + ' Pais: ' + pais + ' CP: ' + cp + ' Condicion: ' + con 
                        + ' Cadena: ' +  cad  + '  <input type="button" value="Asignar" class="idDet" idDet="'+iddet+'" idwms="'+idwms+'" cte="'+id+'" comp="'+comprador+'"> <br/>'  
                    }
                    $.confirm({
                        columnClass: 'col-md-12',
                        title:'Asignacion de la determinante <br/><br/>' +  comp,
                        content:  '' + 
                            '<form action="index.order.php" type="post" name="asigEnviarA" class="formName">'+
                            '<div class="form-group">'+
                            '<label> Asignar Sucursal: </label> <br/>'+
                            info +
                            '</div>' +
                            '</form>'
                        ,
                        buttons:{
                            formSubmit:{
                                text:'Cerrar',
                                btnClass:'btn-green',
                                keys:["esc"],
                                action:function(){

                                }
                            }
                        }

                    })
                }
            },
            error:function(){

            }
        })
    })

    $("body").on("click",".idDet", function(e){
        e.preventDefault();
        var det = $(this).attr("iddet") 
        var ord = $(this).attr("idwms")
        var cte = $(this).attr("cte")
        var comp = $(this).attr("comp")
        $.alert("Se Asigna el idwms "+ ord +" la determinante " +  det )
        $.ajax({
            url:'index.order.php',
            type:'post',
            dataType:'json',
            data:{asigDet:ord, det, cte, comp},
            success:function(data){
                alert("Se ha asignado correctamente")
                location.reload()
            },
            error:function(){

            }
        })
    })


    $(".art").click(function(){
        var idwms = $(this).attr('idwms')
        $.ajax({
            url:'index.order.php', 
            type:'post',
            dataType:'json',
            data:{articulos:idwms},
            success:function(data){
                    for(const [key, value] of Object.entries(data.datos)){
                        lin++
                        for(const[k,val] of Object.entries(value)){
                            if(k == 'Articulo'){var lpe = val}
                            if(k == 'Cantidad'){var lpe = val}
                            if(k == 'Precio'){var lpe = val}
                            if(k == 'Unidad'){var lpe = val}
                            if(k == 'Factor'){var lpe = val}
                            if(k == 'Lista'){var lpe = val}
                            if(k == 'Val'){var validacion = val}
                        }
                    }
                    /*$.confirm({
                        title:'Articulos',
                    })*/
            }, 
            error:function(){

            }
        })

        
    })

</script>

