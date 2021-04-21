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
          <button type="submit" value = "enviar" name="verAuxSaldosCxc" class="btn btn-success">Ejecutar</button>
          </form>
    </div>
</div>
<br />

<?php if($fechaini != ''){?>

<!--    <a href="index.php?action=verES2&fechaini=<?php echo $fechaini?>&fechafin=<?php echo $fechafin?>&impresion=si" target="blank" class="btn btn-info">Imprimir</a> -->

<br/>


<font size ="5" face="verdana" color="purple"> Por favor seleccione el reporte deseado:</font> <br/>
<input type="radio" name="reporte" value="Auxiliar" onclick="verReporte(this.value)" > Auxilir Saldos CxC
<input type="radio" name="reporte" value="VBrutas" onclick="verReporte(this.value)"> ABC Ventas Brutas
<input type="radio" name="reporte" value="VNetas" onclick="verReporte(this.value)"> ABC Ventas Netas
<input type="radio" name="reporte" value="ABCsaldos" onclick="verReporte(this.value)"> ABC Saldos
<input type="radio" name="reporte" value="EdoVentas" onclick="verReporte(this.value)"> Estado de Ventas
<?php }?>

<div class="hide" id="Auxi" >
<?php if($es != 1 ){?>
<br />
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           REPORTE DE AUXILIAR DE SALDOS CXC DEL <?php echo $fechaini.' al '.$fechafin?>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <th>CLAVE SAE</th>
                                            <th>NOMBRE</th>
                                            <th>SALDO INICIAL</th>
                                            <th>CARGOS</th>
                                            <th>ABONOS</th>
                                            <th>SALDO FINAL</th>
                                            <th>PORCENTAJE</th> 
                                        </tr>
                                    </thead> 
                                                         
                                  <tbody>
                                        <?php 
                                            $saldoTotalFinal = 0;
                                        foreach ($es as $data): 
                                            if(empty($data->CARGOS_INICIALES)){
                                              $CI = 0;
                                            }else{
                                              $CI = $data->CARGOS_INICIALES;
                                            }
                                            if(empty($data->PAGOS_INICIALES)){
                                              $PI = 0;
                                            }else{
                                              $PI = $data->PAGOS_INICIALES;
                                            }
                                            if(empty($data->CARGOS)){
                                              $C = 0;
                                            }else{
                                              $C = $data->CARGOS;
                                            }

                                            if(empty($data->PAGOS)){
                                              $P = 0;
                                            }else{
                                              $P = $data->PAGOS;
                                            }
                                              $saldo_inicial = $CI - $PI;
                                              $saldo_final = $saldo_inicial + $C - $P;
                                              $saldoTotalFinal = $saldoTotalFinal + $saldo_final;
                                              
                                              if($saldo_final<>0){
                                                $porc = ($saldo_final/$saldo)*100;
                                              }else{
                                                $porc = 0;
                                              }
                                            
                                            ?>
                                      <?php if($saldo_final <> 0){?>    
                                        <tr>
                                         <!--<tr class="odd gradeX" style='background-color:yellow;' >-->
                                            <td align="right"><?php echo $data->CLAVE;?></td>
                                            <td align="right"><?php echo $data->NOMBRE;?></td>
                                            <td align="right"><?php echo '$ '.number_format($saldo_inicial,2);?> <?php echo 'cargos iniciales: '.number_format($CI,2).' pagos iniciales: '.$PI;?> </td>
                                            <td align="right"><?php echo '$ '.number_format($data->CARGOS,2);?></td>
                                            <td align="right"><?php echo '$ '.number_format($data->PAGOS,2) ;?></td>
                                            <td align="right"><?php echo '$ '.number_format($saldo_final,2);?></td>
                                            <td align="right"><?php echo number_format($porc,2).' %'?></td>     
                                        </tr>
                                        <?php }?>
                                        <?php endforeach; ?>
                                 </tbody>
                                 <tfoot>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td>Total de Cartera</td>
                                   <td align="right"><font color = "red"><?php echo '$ '.number_format($saldoTotalFinal,2)?></font></td>
                                 </tfoot>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                      </div>
            </div>
        </div>
</div>
</div>
<?php }?>

<div class="hide" id="vb">
<?php if($es != 1 ){?>
<br />
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           ABC DE VENTAS BRUTAS DEL <?php echo $fechaini.' al '.$fechafin?>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <th>CLAVE SAE</th>
                                            <th>NOMBRE</th>
                                            <th>VENTAS</th>
                                            <th>PORCENTAJE</th>
                                          </tr>
                                    </thead> 
                                                         
                                  <tbody>
                                        <?php 
                                          $facturas= 0;
                                          $saldoTotalFinal = 0;
                                          $totpor =0; 
                                          $NCS = 0;
                                          $ANP = 0;
                                        foreach($ventasBrutas as $data){
                                           $facturas = $facturas + $data->FACTURAS;
                                           $NCS = $NCS + $data->IMPTNC;
                                           $ANP = $ANP + $data->ABONOSNOPAGOS;           
                                          
                                        }
                                        ?>
                                        <?php 
                                        foreach ($ventasBrutas as $data):
                                                
                                           ?>
                                      <?php if($data->FACTURAS > 0){
                                        $porc = ($data->FACTURAS / $facturas) * 100;
                                            $totpor = $totpor + $porc;
                                        ?>    
                                        <tr>
                                         <!--<tr class="odd gradeX" style='background-color:yellow;' >-->
                                            <td align="right"><?php echo $data->CLAVE;?></td>
                                            <td align="right"><?php echo $data->NOMBRE;?></td>
                                            <td align="right"><?php echo '$ '.number_format($data->FACTURAS,2);?></td>
                                            <td align="right"><?php echo number_format($porc,3).' %'?></td>     
                                        </tr>
                                        <?php }?>
                                        <?php endforeach; ?>
                                 </tbody>
                                 <tfoot>
                                 <td></td>
                                 <td>Total de Cartera</td>
                                 <td align="right"><font color = "red"><?php echo '$ '.number_format($facturas,2)?></font></td>
                                 <td align="right"><font color ="blue"><?php echo '% '.number_format($totpor,3)?></td>
                                 </tfoot>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                      </div>
            </div>
        </div>
</div>
</div>
<?php }?>

<div class="hide" id="vn">
<?php if($es != 1 ){?>
<br />
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           ABC DE VENTAS NETAS DEL <?php echo $fechaini.' al '.$fechafin?>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <th>CLAVE SAE</th>
                                            <th>NOMBRE</th>
                                            <th>VENTAS</th>
                                            <th>PORCENTAJE</th>
                                          </tr>
                                    </thead> 
                                                         
                                  <tbody>
                                        <?php 
                                          $facturasVN= 0;
                                          $saldoTotalFinalVN = 0;
                                          $totporVN =0; 
                                          $NCSVN = 0;
                                          $ANPVN = 0;
                                        foreach($ventasBrutas as $data){
                                           $facturasVN = $facturasVN + $data->FACTURAS;
                                           $NCSVN = $NCSVN + $data->IMPTNC;
                                           $ANPVN = $ANPVN + $data->ABONOSNOPAGOS;           
                                          
                                        }
                                        ?>
                                        <?php 
                                        foreach ($ventasBrutas as $data):
                                                
                                           ?>
                                      <?php if($data->FACTURAS > 0){
                                        $porcVN = ($data->FACTURAS / $facturasVN) * 100;
                                            $totporVN = $totporVN + $porcVN;
                                        ?>    
                                        <tr>
                                         <!--<tr class="odd gradeX" style='background-color:yellow;' >-->
                                            <td align="right"><?php echo $data->CLAVE;?></td>
                                            <td align="right"><?php echo $data->NOMBRE;?></td>
                                            <td align="right"><?php echo '$ '.number_format($data->FACTURAS -  $data->IMPTNC - $data->ABONOSNOPAGOS,2);?></td>
                                            <td align="right"><?php echo number_format($porcVN,3).' %'?></td>     
                                        </tr>
                                        <?php }?>
                                        <?php endforeach; ?>
                                 </tbody>
                                 <tfoot>
                                 <td></td>
                                 <td>Total de Cartera</td>
                                 <td align="right"><font color = "red"><?php echo '$ '.number_format($facturasVN - $NCSVN - $ANPVN,2)?></font></td>
                                 <td align="right"><font color ="blue"><?php echo '% '.number_format($totporVN,3)?></td>
                                 </tfoot>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                      </div>
            </div>
        </div>
</div>
</div>
<?php }?>

<div class="hide" id="vo">
<?php if($es != 1 ){?>
<br />
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           ABC DE SALDOS,  DEL <?php echo $fechaini.' al '.$fechafin?>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <th>CLAVE SAE</th>
                                            <th>NOMBRE</th>
                                            <th>SALDO INICIAL</th>
                                            <th>CARGOS</th>
                                            <th>ABONOS</th>
                                            <th>SALDO FINAL</th>
                                            <th>PORCENTAJE</th> 
                                        </tr>
                                    </thead> 
                                                         
                                  <tbody>
                                        <?php 
                                            $saldoTotalFinal = 0;
                                        foreach ($es as $data): 
                                            if(empty($data->CARGOS_INICIALES)){
                                              $CI = 0;
                                            }else{
                                              $CI = $data->CARGOS_INICIALES;
                                            }
                                            if(empty($data->PAGOS_INICIALES)){
                                              $PI = 0;
                                            }else{
                                              $PI = $data->PAGOS_INICIALES;
                                            }
                                            if(empty($data->CARGOS)){
                                              $C = 0;
                                            }else{
                                              $C = $data->CARGOS;
                                            }
                                            if(empty($data->PAGOS)){
                                              $P = 0;
                                            }else{
                                              $P = $data->PAGOS;
                                            }
                                              $saldo_inicial = $CI - $PI;
                                              $saldo_final = $saldo_inicial + $C - $P;
                                              $saldoTotalFinal = $saldoTotalFinal + $saldo_final;
                                              
                                              if($saldo_final<>0){
                                                $porc = ($saldo_final/$saldo)*100;
                                              }else{
                                                $porc = 0;
                                              }
                                            
                                            ?>
                                      <?php if($saldo_final <> 0){?>    
                                        <tr>
                                         <!--<tr class="odd gradeX" style='background-color:yellow;' >-->
                                            <td align="right"><?php echo $data->CLAVE;?></td>
                                            <td align="right"><?php echo $data->NOMBRE;?></td>
                                            <td align="right"><?php echo '$ '.number_format($saldo_inicial,2);?></td>
                                            <td align="right"><?php echo '$ '.number_format($data->CARGOS,2);?></td>
                                            <td align="right"><?php echo '$ '.number_format($data->PAGOS,2);?></td>
                                            <td align="right"><?php echo '$ '.number_format($saldo_final,2);?></td>
                                            <td align="right"><?php echo number_format($porc,2).' %'?></td>     
                                        </tr>
                                        <?php }?>
                                        <?php endforeach; ?>
                                 </tbody>
                                 <tfoot>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td>Total de Cartera</td>
                                   <td align="right"><font color = "red"><?php echo '$ '.number_format($saldoTotalFinal,2)?></font></td>
                                 </tfoot>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
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


  function verReporte(r){

    if(r == 'Auxiliar'){
      r2 = 'Auxiliar de Saldos CxC';
      document.getElementById('vb').classList.add('hide');
      document.getElementById('vn').classList.add('hide');
      document.getElementById('vo').classList.add('hide');
      document.getElementById('Auxi').classList.remove('hide');
    }else if(r == 'VBrutas'){
      r2='Ventas Brutas';
      document.getElementById('vb').classList.remove('hide');
      document.getElementById('vn').classList.add('hide');
      document.getElementById('vo').classList.add('hide');
      document.getElementById('Auxi').classList.add('hide');
    }else if(r == 'VNetas'){
      r2='Ventas Netas';
      document.getElementById('vb').classList.add('hide');
      document.getElementById('vn').classList.remove('hide');
      document.getElementById('vo').classList.add('hide');
      document.getElementById('Auxi').classList.add('hide');
    }else if(r == 'VOtro'){
      r2='Ventas Otros';
      document.getElementById('vb').classList.add('hide');
      document.getElementById('vn').classList.add('hide');
      document.getElementById('vo').classList.remove('hide');
      document.getElementById('Auxi').classList.add('hide');
    }

    alert('Se Muestra el Reporte '+ r2);
  }
    
  $(document).ready(function() {
    $("#date1").datepicker({dateFormat: 'dd.mm.yy'});
  });

  $(document).ready(function() {
    $("#date2").datepicker({dateFormat: 'dd.mm.yy'});
  });



</script>
