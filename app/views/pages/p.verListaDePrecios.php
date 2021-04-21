<br />
<div class="row">
    <br />
</div>
<div class="row">
    <div class="col-md-6">
        <form action="index.php" method="post">
          <div class="form-group">
              <select name="cliente" required="required">
                <option value="todos">Todos</option>
                <<?php foreach ($clientes as $data): ?>
                <option  value="<?php echo $data->CLAVE?>"> <?php echo '('.$data->CLAVE.') - '.$data->NOMBRE?> </option>  
                <?php endforeach ?>
                </select>
          </div>
          <button type="submit" value = "enviar" name="verListaDePrecios" class="btn btn-success">Ejecutar</button>
          </form>
    </div>
</div>
<br />

<?php if($cliente > 0){?>
<?php foreach ($cliente as $key): 
    $cl = $key->CLAVE;
  ?>
<?php endforeach ?>

<a href="index.php?action=imprimirListaPrecios&cl=<?php echo $cl?>&impresion=si" target="blank" class="btn btn-info">Imprimir</a>
<br/>
<br />
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">

                        <?php foreach ($cliente as $key){
                              $nombre = $key->NOMBRE;
                              }   
                        ?>
                           Lista de precios del cliente <?php echo $nombre?>. 
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <th>CLIENTE</th>
                                            <th>CLAVE ARTICULO</th>
                                            <th>CODIGO DE BARRAS</th>
                                            <th>SKU </th>
                                            <th>DESCRIPCION</th>
                                            <th>PRECIO </th>
                                            </tr>
                                    </thead> 
                                                         
                                  <tbody>
                                        <?php 
                                        foreach ($cliente as $data): 
                                           ?>
                                        <tr>
                                         <!--<tr class="odd gradeX" style='background-color:yellow;' >-->
                                            <td align="left"><?php echo $data->NOMBRE;?></td>
                                            <td align="center"><?php echo $data->CVE_ART;?></td>
                                            <td align="center"><?php echo $data->CODIGOBARRAS?></td>
                                            <td align="center"><?php echo $data->SKU?></td>
                                            <td align="left"><?php echo ($data->DESCRIPCION);?></td>
                                            <td align="center"><?php echo '$ '.number_format($data->PRECIO,2);?></td>
                                          </tr>
                                      
                                        <?php endforeach; ?>
                                 </tbody>
                                 <tfoot>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                
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

