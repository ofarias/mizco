<br /><br />
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                                Datos de la Unidad.
                        </div>
                         <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <?php foreach ($reporte as $data): 
                                    $status=$data->ESTATUS;
                                    $idr=$data->ID;
                                ?>
                                <form action="index.php" method="post" id ="form1">
                                    <input  type="text" class="form-control" name="vehiculo" placeholder="Vehiculo" required="required" value="<?php echo $data->VEHICULO?>" /><br />
                                    <input  type="text" class="form-control" name="placas" placeholder="Placas" required="required" value="<?php echo $data->PLACAS?>"/><br />
                                    <input  type="text" class="form-control" name="fecha" placeholder="Fecha" id="fecha" required="required" value="<?php echo $data->FECHA_REPORTE?>"/><br />
                                    <input  type="number" step="any" class="form-control" name="cajas" placeholder="Cajas" required="required" value="<?php echo $data->CAJAS?>" /><br />
                                    <input  type="text" class="form-control" name="operador" placeholder ="Operador" required="required" value= "<?php echo $data->OPERADOR?>"/><br/>
                                    <input  type="text" class="form-control" name="observaciones" placeholder="Observaciones" required="required" value="<?php echo $data->OBSERVACIONES?>"/>
                                    <br/>
                                    <input type="hidden" name="idr" value="<?php echo $data->ID?>"/>
                                    <button type="submit" value="enviar" name="cambiarReporte" class="btn btn-success" <?php echo $data->ESTATUS =='Cancelado'? "disabled='disabled'":""?>
                                        <?php echo ($status == "Cerrado")? "disabled='disabled'":""?> 
                                     > Cambiar </button> 
                                    <button type="submit" value="enviar" name="reimprimirReporte" class="btn btn-info"> Imprimir Reporte</button>
                                </form>
                                <?php endforeach ?>
                            </div>
                        </div>
                   </div>
                </div>
</div>
<br /><br />
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                                Revision de Facturacion.
                        </div>
                           <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <th>Cajas</th>
                                            <th>Factura / Remision</th>
                                            <th>Fecha Factura</th>
                                            <th>Cliente</th>
                                            <th>Pedido</th>
                                            <th>Observaciones</th>
                                            <th>Fechas</th>
                                            <th>Cerrar</th>
                                        </tr>
                                    </thead>
                                  <tbody>
                                        <?php

                                        $i= 0;
                                        $status=0;
                                        foreach ($facturas as $data): 
                                            $i ++;
                                            $idr = $data->EMBARQUE;
                                            $cajas = $data->CAJAS;
                                            $status = $status + $data->STATUS;

                                        ?>
                                       <tr class="odd gradex" >
                                            <td>
                                                <input type="hidden" name="idr" value ="<?php echo $idr?>" id="idr_<?php echo $i?>">
                                                <input type="hidden" name="docf" value="<?php echo $data->DOCUMENTO?>" id="docf_<?php echo $i?>">
                                                <input type="number" name="cajasxp" placeholder="cajas" value="<?php echo $data->CAJAS?>" id="cajas_<?php echo $i?>" onchange="cambiaCajas(this.value, <?php echo $i?>)">
                                            </td>
                                            <td><?php echo $data->DOCUMENTO;?></td>
                                            <td><?php echo $data->FECHA_ELABORACION;?></td>
                                            <td><?php echo $data->CLIENTE;?></td>
                                            <td><?php echo $data->PEDIDO;?></td>
                                            <form action="index.php" method="post">
                                            <td>
                                                <input type="text" name="obspar" required="required" placeholder="Observaciones" <?php echo $data->STATUS == 1? "disabled='disabled'":"" ?> 
                                                  <?php echo (empty($data->OBSERVACION))? "value=''":"value='$data->OBSERVACION'"?>  id="obs_<?php echo $i?>"> 
                                            </td>
                                            <td>
                                                <input type="text" name="nvafechaEntrega" class="fecha2" placeholder="seleccione fecha" required="required" 
                                                value='<?php echo ($data->STATUS == 1)? "$data->FECHAENT":"" ?>' 
                                                <?php echo ($data->STATUS == 1)? "disabled=disabled":"" ?>  id="fecha_<?php echo $i?>" >
                                            </td>
                                            
                                            <td>
                                                <input type="hidden" name="cajasxpartida" value = "<?php echo $cajas;?>" id="idr_<?php echo $i?>">
                                               
                                            </td>
                                        <?php endforeach; ?>
                                        </tr> 
                                        <input type="hidden" name="iterador" value="<?php echo $i?>" id="iterador">
                                 </tbody>
                                 </table>
                      </div>
            </div>

            <form action="index.php" method="post">
                <input type="hidden" name="idr" value="<?php echo $idr?>"/>
                <button value="enviar" type="submit" name="cancelaEmbarque" class="btn btn-danger" 
                <?php echo ($status == "Cancelado")? "disabled='disabled'":"" ?>
                <?php echo ($status == "Cerrado")? "disabled='disabled'":""?> 
                > <?php echo ($status == 'Cancelado')? "Cancelado":"Cancelar Embarque"?> </button>
            </form>
            <label> Al Cancelar el Embarque se liberaran las facturas para poder ser embarcadas nuevamente en otro Embarque.</label>
        </div>
        <div> <button type="submit" value="enviar" 
                                                 <?php echo ($status==$i)? "disabled='disabled'":"" ?>
                                                 <?php echo ($status==$i)? "class='btn btn-info'":"class='btn btn-success'" ?> 
                                                 name="guardaObsPar"  
                                                 onclick="cerrar()" 
                                                 id="boton"> 
                                                <?php echo ($status==$i)? "Cerrado":"Cerrar"?>   

              </button>
        </div>
 </div>

 <form>
    <input type="hidden" name="idr" >
 </form>

<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>

<script>

    $(document).ready(function() {
    $("#fecha").datepicker({dateFormat: 'dd.mm.yy'});
  } );

$(document).ready(function() {
    $(".fecha2").datepicker({dateFormat: 'dd.mm.yy'});
  } );

function cambiaCajas(cajas, i){
  
    var idr = document.getElementById('idr_' + i).value;
    var docf = document.getElementById('docf_' + i ).value;
      //alert('el IDR es: ' + idr + ' la nueva cantidad: ' +  cajas + ' la factura es : '+ docf);
    $.ajax({
        type:'POST',
        dataType:'json',
        url:'index.php',
        data:{guardaCaja:1, cajasxp:cajas, idr:idr, docf: docf},
        success:function(data){
        //    alert('Se cambio la cantidad');
        }
    });
}

function cerrar(){
    var i = document.getElementById('iterador').value;
    var datos = [];
    //alert('se cerro el reporte con exito' + i);
    var validacion = 0;
        for(var s = 1; s<=i; s++){
           var cajas = document.getElementById('cajas_'+ s).value;
           var observacion = document.getElementById('obs_'+s).value;
           var fecha = document.getElementById('fecha_'+s).value;
           var idr = document.getElementById('idr_'+s).value;
           var docf = document.getElementById('docf_'+s).value; 

           if(cajas == '0' || observacion == '' || fecha ==''){
            validacion = validacion + 1;
           }else{
            var dato=[cajas,observacion,fecha,idr, docf];
            datos.push(dato);
           }
        }
        
    if(validacion > 0){
        alert('Faltan '+ validacion + ' por completar, favor de revisar que toda la información, este completa');
    }else{
        $.ajax({
            type:'POST',
            dataType:'json',
            url:'index.php',
            data:{guardaObsPar:1, datos:datos},
            success:function(){
                alert('OK');
                document.getElementById('boton').classList.add('hide');
            }
        });
    }

}
 
</script>