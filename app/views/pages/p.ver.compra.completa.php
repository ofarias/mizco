<br/>
<br/>
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Detalla de Recepcion .
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <th>No Documento</th>
                                            <th>Proveedor</th>
                                            <th>Fecha</th>
                                            <th>Importe</th>
                                            <th>Moneda</th>
                                            <th>Tipo de Cambio</th>
                                            <th>Documento Anterior</th>
                                            <th>Costear</th>
                                        </tr>
                                    </thead>                                   
                                  <tbody>
                                        <?php 
                                        foreach ($compras as $data): 
                                            $docr = $data->CVE_DOC;
                                            $cimp = $data->C_IMPUESTO;
                                        ?>
                                        <tr>
                                            <td><?php echo $data->CVE_DOC;?></td>
                                            <td><?php echo $data->NOMBRE;?></td>
                                            <td><?php echo $data->FECHAELAB;?></td>
                                            <td align="right"><?php echo '$ '.number_format($data->IMPORTE,2);?></td>
                                            <td><?php echo $data->NUM_MONED;?></td>
                                            <td><?php echo $data->TIPCAMB;?></td>
                                            <td><?php echo $data->DOC_ANT;?></td>
                                        </tr>
                                        
                                        <?php endforeach; ?>
                                 </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                      </div>
            </div>
        </div>
</div>
<br/>
<form action="index.php" method="post">
<!--<label> Costo FOB </label> <input type="number" step="any" name="fob" placeholder="FOB Moneda Nacional"> <button name="guardaFOB" type ="submit" value = "enviar" clas="btn btn-warning"> Guardar </button>
</form>-->
<br/>
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Captura de Costos Generales.
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                           <!--
                                            <th>Pedimento</th>
                                            -->
                                            <th>Impuestos</th>
                                            <th>Flete</th>
                                            <th>Aduana </th> 
                                            <th>Seguro</th>
                                            <th>Calcular</th>
                                        </tr>
                                    </thead>                                   
                                  <tbody>
                                        <tr>
                                            <form>

                                                
                                                    <input type="hidden" name="pedimento" placeholder="pedimento" value="">
                                                
                                                <td>
                                                    <input type="number" step="any" name="cimpuesto" placeholder="Impuestos">
                                                </td>
                                                <td>
                                                    <input type="number" step="any" name="cflete" placeholder="Flete">
                                                </td>
                                                <td>
                                                    <input type="number" step="any" name="caduana" placeholder="Agente Aduanal">
                                                </td>
                                                <td>
                                                    <input type="number" step="any" name="cseguro" placeholder="Seguro">
                                                </td>
                                                <td>
                                                    <input type="hidden" name="docr" value="<?php echo $docr;?>">
                                                    <button type="submit" value = "enviar" name = "calcularCosto" class="btn btn-warning" > Calcular</button>
                                                </td>
                                            </form>
                                        </tr>
                                 </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                      </div>
            </div>
        </div>
</div>

<?php foreach($compras as $key): 
 ?>
 <label> Pedimento: </label> <?php echo $key->PEDIMENTO?> <br/>
 <label> Impuesto: </label> <?php echo '$ '.number_format($key->C_IMPUESTO,2)?><br/>
 <label> Flete: </label> <?php echo '$ '.number_format($key->C_FLETE,2)?><br/>
 <label> Aduana: </label> <?php echo '$ '.number_format($key->C_ADUANA,2)?><br/>
 <label> Seguro: </label> <?php echo '$ '.number_format($key->C_SEGURO,2)?><br/>
 <label> Total de Insumos: </label> <?php echo '$ '.number_format(($key->C_IMPUESTO + $key->C_FLETE + $key->C_ADUANA + $key->C_SEGURO),2)?><br/>
 <label> El total de los insumos se prorratea entre <?php echo number_format($totalPiezas,0);?> </label> <br/>

<?php 
    endforeach; ?>

<br/>
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Partidas de la Recepcion .
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <th>Partida</th>
                                            <th>Clave <br/> Pedimento </th>
                                            <th>Descripcion</th>
                                            <th>Precio</th>
                                            <th>IVA</th>
                                            <th>Unidad Entrada</th>
                                            <th>Almacen</th>
                                            <th>Cantidad</th>
                                            <th>Costo FOB USD </th>
                                            <th>Total Flete</th>
                                            <th>Total Agente Aduanal</th>
                                            <th>Total Seguro</th>
                                            <th>Total Impuestos</th>
                                            <th>TC</th>
                                            <th>Total Costo x Pieza</th>
                                            <th>Guardar</th>
                                        </tr>
                                    </thead>                                   
                                  <tbody>
                                        <?php 
                                        foreach ($partidas as $data): 
                                        ?>
                                        <tr>
                                         <form action="index.php" method="post" id = "cfb">
                                            <td><?php echo $data->NUM_PAR;?></td>
                                            <td><?php echo $data->CVE_ART;?> <input name="pedimento" type="text" maxlength="30" value=<?php echo (empty($data->PED))? "":$data->PED ?> > </td>
                                            <td><?php echo $data->DESCR;?></td>
                                            <td align="right"><?php echo '$ '.number_format($data->COST,2);?></td>
                                            <td><?php echo $data->TOTIMP4;?></td>
                                            
                                            <td><?php echo $data->UNI_VENTA;?></td>
                                            <td><?php echo $data->NUM_ALM;?></td>
                                            <td><?php echo $data->CANT;?></td>
                                           
                                            <td>
                                                <input type="number" step="any" name="cfob"  placeholder="Costo Fob" value="<?php echo $data->COSTO_FOB?>">
                                            </td>
                                           <td><?php echo '$ '.number_format($data->COSTO_FLETE,2)?></td>
                                            <td><?php echo '$ '.number_format($data->COSTO_AGENTE,2)?></td>
                                            <td><?php echo '$ '.number_format($data->COSTO_SEGURO,2)?></td>
                                            <td><?php echo '$ '.number_format($data->COSTO_IMPUESTOS,2)?></td>
                                            <td>
                                                <input type="hidden" name="docr" value="<?php echo $docr?>">
                                                <input type="number" step= "any" name="tc" placeholder="T/C"  value="<?php echo $data->TC?>">
                                                <input type="hidden" name="par" value="<?php echo $data->NUM_PAR?>">
                                            </td>
                                            <td>
                                                <?php echo '$ '.number_format($data->COSTOFINAL,2)?>
                                            </td>
                                            <td>
                                                <button type ="submit" value = "enviar" name="costoFOB" id="cfb" >Guardar</button>
                                            </td>
                                            </form>
                                        </tr>
                                        
                                        <?php endforeach; ?>
                                 </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                      </div>
            </div>
        </div>
</div>
<FORM action="index.php" method="post"  id="fin">
   <input type="hidden" name="docr" value="<?php echo $docr?>">
   <button class="btn btn-warning" value="enviar" type ="submit" name="finalizaCosteo" > Finalizar</button> 
</FORM>