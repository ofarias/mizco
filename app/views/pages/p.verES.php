<br />
<div class="row">
    <br />
</div>
<div class="row">
    <div class="col-md-6">
        <form action="index.php" method="post">
          <div class="form-group">
            <input type="text" name="fechaini" class="form-control" placeholder="Fecha inicial" required="required"  id="date1"> <br/>
            <input type="text" name="fechafin" class="form-control" placeholder="Fecha Final" required="required" id="date2">

          </div>
          <button type="submit" value = "enviar" name="verES" class="btn btn-success">Ejecutar</button>
          </form>
    </div>
</div>
<br />

<?php if($fechaini != ''){?>
<a href="index.php?action=verES2&fechaini=<?php echo $fechaini?>&fechafin=<?php echo $fechafin?>&impresion=si" target="blank" class="btn btn-info">Imprimir</a>
<br/>
<?php }?>

<?php if($es != 1 ){?>
<br />
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           RESUMEN DE MOVIMIENTOS DEL <?php echo $fechaini.' al '.$fechafin?> SOLO DEL ALMACEN 1
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <th>CLAVE</th>
                                            <th>DESCRIPCION</th>
                                            <th>INICIAL</th>
                                            <th>ENTRADAS</th>
                                            <th>SALIDAS</th>
                                            <th>EXISTENCIAS FINALES</th>
                                        </tr>
                                    </thead> 
                                                         
                                  <tbody>
                                        <?php
                                          $ini = 0;
                                          $ent = 0;
                                          $sal = 0;
                                          $fin = 0;

                                        foreach ($es as $data): 
                                            //$finales = $data->INICIAL + $data->ENTRADAS - $data->SALIDAS;
                                            $ini += $data[3];
                                            $ent += $data[4];
                                            $sal += $data[5];  
                                            $fin += $data[6];
                                            ?>
                                          <?php if(($data[3] + $data[4]+$data[5]+$data[6]) > 0){  ?>
                                        <tr>
                                         <!--<tr class="odd gradeX" style='background-color:yellow;' >-->
                                            <td align="right"><?php echo $data[0];?></td>
                                            <td align="right"><?php echo $data[1];?></td>
                                            <td align="right"><?php echo number_format($data[3],0);?></td>
                                            <td align="right"><?php echo number_format($data[4],0);?></td>
                                            <td align="right"><?php echo number_format($data[5],0);?></td>
                                            <td align="right"><?php echo number_format($data[6],0);?></td>     
                                        </tr>
                                        <?php } ?>
                                        <?php endforeach; ?>
                                        <tr>
                                          <td></td>
                                          <td align="right">Totales</td>
                                          <td align="right"><?php echo number_format($ini)?></td>
                                          <td align="right"><?php echo number_format($ent)?></td>
                                          <td align="right"><?php echo number_format($sal)?></td>
                                          <td align="right"><?php echo number_format($fin)?></td>
                                        </tr>
                                 </tbody>
                                </table>
                            </div>
                            
                            <!-- /.table-responsive -->
                      </div>
            </div>
        </div>
</div>
<?php }else{ }?>


<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script type="text/javascript">
    
  $(document).ready(function() {
    $("#date1").datepicker({dateFormat: 'dd.mm.yy'});
  });


  $(document).ready(function() {
    $("#date2").datepicker({dateFormat: 'dd.mm.yy'});
  });


</script>
