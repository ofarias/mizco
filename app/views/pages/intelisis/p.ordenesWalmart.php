<br /><br />
<?php 
    $nombre = ''; $status = ''; $n_s='';
    if(count($ordenes) > 0 ){
        foreach ($ordenes as $k) {
        $nombre = $k->ARCHIVO;
        $status = $k->F_STATUS;
        if($status == 0){
            $n_s = 'Pendiente';
        }elseif($status == 1 ){
            $n_s = 'Concluido';
        }elseif($status==9){
            $n_s = 'Cancelado';
        }else{
            $n_s = 'En Proceso';
        }

    }?>
<input type="hidden" class="file" value="<?php echo $param?>">
<?php }?>

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
                                            <th>Archivos</th>
                                           
                                            <th>Traer</th>
                                        </tr>
                                    </thead>
                                  <tbody>
                                       <tr>  
                                       <form action = "index.php" method="post" >
                                            <td>
                                                <select class="selector">
                                                    <option value="9999999">Seleccione el archivo a visualizar</option>
                                                    <?php foreach ($archivos as $archivo) {?>
                                                      <option value="<?php echo $archivo->ID_F?>" status="<?php echo $archivo->STATUS?>"><?php echo $archivo->ARCHIVO.' FECHA CARGA --> '.$archivo->FECHA.' Status: '.$archivo->STATUS?> </option>  
                                                    <?php }?>
                                                </select>
                                                &nbsp;&nbsp;&nbsp;
                                            </td>
                                           
                                                
                                            <td>
                                                <select class="filtroArchivo">
                                                    <option value="999999"> Archivos: </option>
                                                    <option value="0">Pendientes</option>
                                                    <option value="1">Concluidos</option>
                                                    <option value="9">Cancelados</option>
                                                    <option value=""> Todos</option>
                                                </select>
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
                                 Ordenes Walmart : <b><?php echo $nombre ?></b> ---> <font color="yellow" ><b><?php echo $n_s?></b></font> 
                                                <?php if($status === 0){?>
                                                    <button class="chgStatus btn-sm btn-danger" value="can">Cancelar</button>
                                                    &nbsp;&nbsp;&nbsp; 
                                                    <button class="chgStatus btn-sm btn-info" value="con">Concluir</button>
                                                <?php }?>
                                            --->
                                                <b><a href="../uploads/xls/remisiones/<?php echo $nombre?>", download> Descargar</a></b>
                                 <br/><br/>
                                 <a class="filtro" t="t"><font color="white">Todas</font></a>&nbsp;&nbsp;&nbsp;&nbsp;
                                 <a class="filtro" t="e"><font color="white">Sin Enviar</font></a>&nbsp;&nbsp;&nbsp;&nbsp;
                                 <a class="filtro" t="s"><font color="white">Enviadas</font></a>
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
                                            $tipo = empty($ord->MOVID)? "ord_s":"ord_e";
                                            
                                    ?>
                                       <tr class="<?php echo $tipo?> , ord" >  
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

    $(".selector").change(function(){
        var status = $('.selector option:selected' ).attr("status")
        var file = $(this).val()
        window.open("index.order.php?action=ordenesW&tipo=f&param="+ file, "_self")
    })

    $(".chgStatus").click(function(){
        var tipo = $(this).val()
        var file = $(".file").val()
        $.ajax({
            url:'index.order.php',
            type:'post',
            dataType:'json',
            data:{chgSta:tipo, file},
            success:function(data){
                location.reload()
            },
            error:function(){

            }
        })
    })

    $(".filtroArchivo").change(function(){
        var status = $(this).val()
        if(status < 999999){
            window.open("index.order.php?action=ordenesW&tipo=s&param="+status, "_self")
        }
    })

    $(".download").click(function (){
        window.open("../uploads/xls/remisiones/2022.08.26_08.09.20_LayOut%2009.08.2022%20FULL.xlsx", "download")
    })

    $(".filtro").click(function(){
        var tipo = $(this).attr("t")
        $(".ord").each(function(){
                $(this).removeClass("hidden")
            })
        if(tipo == 'e' || tipo == 's'){
            $(".ord_"+tipo).each(function(){
                $(this).addClass("hidden")
            })
        }

    })
    
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
        var info = ''
        var tipo = ' <font color="green"> se encuenta en la lista: </font>'
        var lin = 0
        $.ajax({
            url:'index.order.php', 
            type:'post',
            dataType:'json',
            data:{articulos:idwms},
            success:function(data){
                    for(const [key, value] of Object.entries(data.datos)){
                        lin++
                        for(const[k,val] of Object.entries(value)){
                            if(k == 'ARTICULO'){var art = val}
                            if(k == 'CANTIDAD'){var cant = val}
                            if(k == 'PRECIO'){var pre = val}
                            if(k == 'UNIDAD'){var uni = val}
                            if(k == 'COMPRADOR'){var comp = val}
                            if(k == 'LISTAORDEN'){var list = val}
                            if(k == 'VALIDACION'){var validacion = val}
                        }
                        if(validacion == 0){
                            tipo = '<font color = "red"> NO se encuenta en la lista: </font>'
                        }
                            info += '<p> '+ lin +': El articulo: '+ art + tipo + list + ' codigo: <font color="blue">'+ comp +'</font> </p>' 

                    }
                    $.confirm({
                        columnClass:'col-md-8',
                        title:'Articulos',
                        content:'' + info
                    })
            }, 
            error:function(){

            }
        })

        
    })

</script>

