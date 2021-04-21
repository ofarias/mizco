<br />
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default" id="">
            <div class="panel-heading">
                Datos del cliente.
            </div>
            <div class="panel-body">
                <?php foreach($datacli as $data):?>
                <div class="form-group col-md-8">
                    <label> Cliente: <?php echo $data->NOMBRE?> (<?php echo $data->RFC?>) Cliente SAE: (<?php echo $data->CLAVE?>)</label> <br>   
                    <label> <?php echo $data->DIRECCION?> . Tel: <?php echo (empty($data->TELEFONO))? 'Sin Informacion':$data->TELEFONO;?></label>   <br>
                    <label> Email :  <?php echo $data->EMAILPRED;?> -- Vendedor Asignado: <?php echo (empty($data->VENDEDOR))? 'Sin Definir':$data->VENDEDOR?> Descuento: <?php echo $data->DESCUENTO?> -- Lista de Precios : <?php echo $data->LISTA_PREC?></label><br/>
                    <label>  Dias de Credito: &nbsp;&nbsp;<b> <?php echo $data->DIASCRED?></b></label> 
                </div>
                <div class="form-group col-md-4"> 
                    <button id="contactos" type="button" class="btn btn-default">Contactos <i class="fa fa-users"></i></button>
                </div>
            </div>  
                <?php endforeach;?>
            <div class="panel-heading">
                Cartera y linea de credito al <?php echo date("d-m-Y")?>.
            </div>
                <table width="100%" cellspacing="0" cellpadding="0" border="1">
                    <?php foreach ($saldo as $data):
                        $lc= $data->LINEA_CRED;
                        $pedidos = $data->PEDIDOS;
                        $facturas = $data->FACTURAS;
                        $lcc = $pedidos + $facturas;
                        $sd = $lc - $lcc;
                        $ac = $data->ACREEDORES;
                        $sv = $data->SALDO_VENCIDO;
                        $sc = $data->SALDO_CORRIENTE;
                        $st = ($sv + $sc) - $ac;
                    ?>
                    <tr>
                        <td width="47%"><label>&nbsp;&nbsp;&nbsp; Linea de Crédito: $ <?php echo number_format($data->LINEA_CRED,2);?></label></td>
                        <td width="6%"></td>
                        <td width="47%"><label style="color:#DF3A01">&nbsp;&nbsp; Saldo Vencido: $ <?php echo number_format($data->SALDO_VENCIDO,2)?></label></td>
                    </tr>
                    <tr>
                        <td><label>&nbsp;&nbsp;&nbsp; Pedidos en Transito: $ <?php echo number_format($data->PEDIDOS,2,".",",");?> </label></td>
                        <td width="6%"></td>
                        <td><label style = "color:#0080FF">&nbsp;&nbsp; Saldo sin Vencer: $ <?php echo number_format($data->SALDO_CORRIENTE,2)?> </label><br></td>
                    </tr>
                    <tr>
                        <td> <label style="color:green" >&nbsp;&nbsp;&nbsp; Pedidos Facturados: $ <?php echo number_format($data->FACTURAS,2)?></label></td>
                        <td width="6%"></td>
                        <td> <label style="color:#01DFA5">&nbsp;&nbsp; Acreedores: $ <?php echo number_format($data->ACREEDORES,2) ?></label></td>
                    </tr>
                    <tr>
                        <td><label style="color:#FF8000">&nbsp;&nbsp;&nbsp; Linea de Credito Comprometida: $ <?php echo number_format($lcc,2)?> </label></td>
                        <td width="6%"></td>
                        <td><label <?php echo ($st > $lc )? 'style="color:red"':'style="color:#5858FA"'?>>&nbsp;&nbsp; Saldo Total: $ <?php echo number_format($st,2)?></label></td>
                    </tr>
                      <tr>
                        <td><label <?php echo ($sd < 0)? 'style="color:red"':'style:"color:blue"'?>>&nbsp;&nbsp;&nbsp;Saldo Disponible: $ <?php echo number_format($sd,2);?></label></td>
                        <td width="6%"></td>
                        <td><label>&nbsp;&nbsp;Promedio ponderado de dias de pago:</label></td>
                    </tr>
                <?php endforeach; ?>
                </table>
        </div>
    </div>
</div>
<div>
<form action="index.php" method="post">
<?php foreach($datacli as $data): 
?>
<input type="hidden" value="<?php echo $data->CLAVE;?>" name="cveclie" />
<!--<button name="FacturaPago" type = "submit" value ="enviar" class="btn btn-success">Aplicar Facturas a Pago</button>-->
<!--<button name="PagoFactura" type = "submit" value ="enviar" class="btn btn-info"> Aplicar Pagos a Facturas </button>-->
<button name=<?php echo ($historico =='Si')? '"DetalleCliente"':'"SaldosxDocumentoH"'?> type="submit" value = "enviar" 
    <?php echo ($historico=='Si')? 'class="btn btn-danger"':"class='btn btn-warning'"?>> 
    <?php echo ($historico == 'Si')? 'Ver Facturas Pendientes':'Ver Historico Cliente'?> </button>
<?php endforeach; ?> 
</form>  
</div>
<br />
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default" id="">
            <div class="panel-heading">
                Movimientos al <?php echo date("d-m-Y h:i:s A")?>.
            </div>

            <div class="panel-body">
                 <div class="table-responsive">                            
                    <table class="table table-striped table-bordered table-hover" id="">
                        <thead>
                            <tr>
                                <th>Doc/Pedido</th>
                                <th>Fecha/ Hora</th>
                                <th>Documentos</th>
                                <th>Importe</th>
                                <th>Pagos</th>
                                <th>Notas de Credito</th>
                                <th>Dias</th>
                                <th>Saldo Sin Vencer</th>
                                <th>Saldo Vencido</th>
                                <th>ContraRecibo</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($exec as $data):?>
                            <tr>
                                <td><?php echo $data->CVE_DOC;?></td>
                                <td><?php echo substr($data->FECHAELAB,0,10)?></td>
                                <td>
                                     <button name="comprobanteFletera" class="btn btn-warning"> <i class="fa fa-file-text-o"></i></button>
                                     <button name="pdf" class="btn btn-info"> <i class="fa fa-file-pdf-o"></i></button>
                                     <button name="xml" class="btn btn-primary"> <i class="fa fa-file-excel-o"></i></button>
                                </td>
                                <td align="right"><?php echo '$ '.number_format($data->IMPORTE,2);?></td>
                                <td align="right" style="color:#DF013A"> <?php echo '$ '.number_format($data->APLICADO,2);?></td>
                                <td align="right" style="color:#DF013A"><?php echo '$ '.number_format($data->IMPORTE_NC,2)?></td>
                                <td align="center"> <?php echo $data->DIAS?> </td>
                                <td align="right" style="color:blue">$ <?php echo ($data->DIAS >= 0)? number_format($data->SALDOFINAL,2):"0.00"?></td>
                                <td align="right" <?php echo ($data->SALDOFINAL > 3)? 'style="color:red"':'style="color:#0101DF"'?>><b>$ <?php echo ($data->DIAS < 0 )? number_format($data->SALDOFINAL,2):"0.00";?></b></td>
                                <td><?php echo $data->CONTRARECIBO_CR;?></td>
                            </tr>
                                <td><?php echo $data->PEDIDO?></td>
                                <td><?php echo substr($data->FECHAELAB, 11,9)?></td>
                                <td></td>
                                <td></td>
                                <td style="color:#DF013A"><?php echo $data->INFO_PAGO?></td>
                                <td style="color:#DF013A"><?php echo $data->NC_APLICADAS?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                    


                        <?php endforeach; ?>
                        </tbody>
                    </table>

                    <label>Subtotales: </label>
                    <!-- /.table-responsive -->
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById("contactos").addEventListener("click",function(){
        var id = document.getElementById("clave");
        var link = "index.php?action=ContactosCliente&cliente="+id.value;
        myWindow = window.open(link, "", "width=550, height=600");
    });


}

</script>