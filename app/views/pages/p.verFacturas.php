<br /><br />
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                                Revision de Facturacion.
                        </div>
                        <!-- /.panel-heading -->
                           <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <th>Seleccionar</th>
                                            <th>Factura / Remision</th>
                                            <th>Fecha Factura</th>
                                            <th>Cliente</th>
                                            <th>Pedido</th>
                                            <th>Importe</th>
                                            <!--<th>Seleccionar</th>-->
                                        </tr>
                                    </thead>
                                  <tbody>
                                        <?php
                                          $i=0;
                                          foreach ($facturas as $data): 
                                            $i++;
                                           $color= $data->SELECCION == '1'? "style='background-color: #BEF781;'":"";
                                        ?>
                                       <tr class="odd gradex" <?php echo $color?> id="<?php echo $i?>">
                                            <form action="index.php" method="post">
                                            <td><input type="checkbox" name="datos[]" docs="<?php echo $i?>, <?php echo $data->CVE_DOC?>, <?php echo $data->SELECCION?>" value="<?php echo $data->CVE_DOC?>" onclick="seleccion(<?php echo $i?>,this.value)" id="linea_<?php echo $i?>" <?php echo  $data->SELECCION == '1'? 'checked':'' ?> ></td>
                                            <td><?php echo $data->CVE_DOC;?></td>
                                            <td><?php echo $data->FECHAELAB;?></td>
                                            <td><?php echo $data->NOMBRE;?></td>
                                            <td><?php echo $data->CVE_PEDI;?></td>
                                            <td><?php echo '$ '.number_format($data->IMPORTE,2);?></td>
                                            <input type="hidden" name="docf" value="<?php echo $data->CVE_DOC;?>" />
                                            <input type="hidden" na&&"select" value="<?php echo $data->SELECCION?>">
                                           <!-- <td>
                                             <button name="selectFactura" type="submit" value="enviar" class= "btn btn-warning"> 
                                               <?php echo $data->SELECCION == 1? "Quitar":"Seleccionar"?><i class="fa fa-floppy-o"></i>
                                            </button>
                                            
                                             </td> -->
                                            </form>
                                        </tr> 
                                        <?php endforeach; ?>
                                 </tbody>
                                 </table>
                                 <br/>
                      </div>
                        <form action="index.php" method="post">
                            <input type="hidden" name="test"/>
                            <button value="enviar" name="GeneraReporteSalida" type="submit" class="btn btn-info"> Generar Reporte </button>
                       </form>
            </div>                 
        </div>
</div>


<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script src="http://bootboxjs.com/bootbox.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script>

  function test(){
    alert('intento1');
    var docu='';
    $("input:checkbox:checked").each(function() {
             var  docs = $(this).attr("docs");
             docu = docu + docs;
             
        });
  }
    
  function seleccion( i, doc ){
    if(document.getElementById('linea_'+i).checked){
      document.getElementById(i).style.color = '#EFFBFB';
      document.getElementById(i).style.backgroundColor= 'green';
      var select = 0;
    }else{
      var select = 1;
          document.getElementById(i).style.color = '#EFFBFB';
          document.getElementById(i).style.backgroundColor= '#EF6161';
    }
    $.ajax({
        type:'POST',
        url:'index.php',
        dataType:'json',
        data:{selectFactura:1, docf:doc, select:select},
        success: function (data){
            if(data.status == 'ok'){
              if(select == 0){ 
              }
            }else{
              alert('Ocurrio un error, favor de revisar la informacion');
            }
        }   
      });
  }

  function inicio(documento){
        document.getElementById('documento_inicio').value = documento;
        frm = document.getElementById("hora_inicio");
        frm.submit();
    }
</script>