
<br>
<br>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default" id="">
            <div class="panel-heading">
                Detalle del Maestro: <?php echo $cve_maestro;?>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                 <div class="table-responsive">                            
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Sucursales</th>
                                <th>Limite Global</th>
                                <th>Saldo 2015</th>
                                <th>Saldo 2016</th>
                                <th>Saldo 2017</th>
                                <th>Acreedores </th>
                                <th>Total Deuda</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($saldoIMaestro as $sc):
                            ?>
                                    <tr>
                                        <td><?php echo $sc->NOMBRE?></td>
                                        <td><?php echo $sc->SUCURSALES?></td>
                                        <td align="right"><?php echo '$ '.number_format($sc->LIMITE_GLOBAL,2);?></td>                                    
                                        <td align="right"><?php echo '$ '.number_format($sc->SALDO_2015,2,".",",");?></td>
                                        <td align="right"><?php echo '$ '.number_format($sc->SALDO_2016,2,".",",");?></td>
                                        <td align="right"><?php echo '$ '.number_format($sc->SALDO_2017,2);?></td>
                                        <td align="right"><?php echo '$ '.number_format($sc->ACREEDOR,2,".",",");?></td>
                                        <td align="right"><?php echo '$ '.number_format( ($sc->SALDO_2015 + $sc->SALDO_2016 + $sc->SALDO_2017) - $sc->ACREEDOR,2);?></td>
                                    <form action="index.php" method="post">
                                        <input type="hidden" name="cliente" value="<?php echo $sc->CC;?>"/>
                                        
                                    </form>
                                    </tr> 
                      
                            <?php endforeach;?>
                        </tbody>
                    </table>
                    <!-- /.table-responsive -->
                </div>
            </div>
        </div>
    </div>
</div>

<br />
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default" id="">
            <div class="panel-heading">
                Saldos por cliente del Maestro <?php echo $cve_maestro;?>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                 <div class="table-responsive">                            
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Días de credito</th>
                                <th>Linea de Credito</th>
                                <th>Saldo 2015</th>
                                <th>Saldo 2016</th>
                                <th>Saldo 2017</th>
                                <th>Acreedores </th>
                                <th>Total Deuda</th>
                                <th>Documentos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($saldoIndividual as $sc):
                                $linea=$sc->LINEA_CRED;

                                if($linea == 0){
                                    $linea= 'Ilimitada';
                                }else{
                                    $linea= number_format($linea,2);
                                }

                                $s15=$sc->S15;
                                $s16=$sc->S16;
                                $s17=$sc->S17;
                                $ac=$sc->ACREEDOR;
                                $total = ($s15+$s16+$s17) - $ac;

                            ?>
                                    <tr>
                                        <td><?php echo $sc->NOMBRE?></td>
                                        <td><?php echo $sc->PLAZO?></td>                                    
                                        <td><?php echo $linea;?></td>
                                        <td align="right"><?php echo '$ '.number_format($sc->S15,2,".",",");?></td>
                                        <td align="right"><?php echo '$ '.number_format($sc->S16,2,".",",");?></td>
                                        <td align="right"><?php echo '$ '.number_format($sc->S17,2);?></td>
                                        <td align="right"><?php echo '$ '.number_format($sc->ACREEDOR,2,".",",");?></td>
                                        <td align="right"><?php echo '$ '.number_format($total,2);?></td>
                                    <form action="index.php" method="post">
                                        <input type="hidden" name="cliente" value="<?php echo $sc->CC;?>"/>
                                        <td><button type='submit' name="DetalleCliente" class="btn btn-info">Detalle</button></td>
                                    </form>
                                    </tr> 
                      
                            <?php endforeach;?>
                        </tbody>
                    </table>
                    <!-- /.table-responsive -->
                </div>
            </div>
        </div>
    </div>
</div>


<script>/*
    function muestraOculta(clave){
        var fila = document.getElementById(clave);
        if(fila.style.display=="none"){
            fila.style.display="table-cell";
            document.getElementById("a"+clave).innerHTML="-";
        }else{
            fila.style.display="none";
            document.getElementById("a"+clave).innerHTML="+";
        }
    }*/
</script>