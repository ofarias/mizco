<br /><br />
<style type="text/css">
    .num {
        width: 5em;
    }
</style>
<div class="row">
    <div class="col-lg-12">
            <div tyle="color: blue;"> 
                <p>
                    <label>Carga el el Layout para la carga de Ordenes de compra en excel.</label>
                    <form action="upload_orden.php" method="post" enctype="multipart/form-data">
                        <input type="file" name="fileToUpload" id="fileToUpload" accept=".xls, .csv, .txt, .xlsx">
                        <input type="submit" value="Cargar Orden" >
                    </form>
                </p>
            </div>
            <br/>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
            <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover" id="dataTables">
                                    <thead>
                                        <tr>
                                            <th> Cliente </th>
                                            <th> Orden </th>
                                            <th> Fecha de Carga <br/> <font color="blue">Final</font> </th>
                                            <th> Cedis </th>
                                            <th> Productos </th>
                                            <th> Piezas </th>
                                            <th> Estado </th>
                                            <th> Fecha Asigna <br/> <font color="brown">Final</font></th>
                                            <th> Fecha Almacen <br/><font color="green">Final</font></th>
                                            

                                            <th> Usuario </th>
                                            <th> Archivo </th>
                                            <th> Prioridad </th>
                                            <th> Detalle <br/> Correo </th>
                                            <th> Eliminar </th>
                                        </tr>
                                    </thead>
                                  <tbody>
                                    <?php foreach ($ordenes as $ord): 
                                        $color='';
                                        //$color = '';if(trim($kp->STATUS) == 'Eliminado'){ $color="style='background-color:#f33737'";}
                                        ?>
                                       <tr class="odd gradeX color" <?php echo $color?>>
                                            <td><?php echo $ord->CLIENTE?></td>
                                            <td title="<?php echo $ord->ORDEN?>"><?php echo substr($ord->ORDEN, 0, 20) ?></td>
                                            <td><?php echo $ord->FECHA_CARGA?>
                                            <br/><font color="blue"><?php echo $ord->FECHA_CARGA_F?></font></td>
                                            <td><?php echo $ord->CEDIS?></td>
                                            <td align="right"><?php echo $ord->PRODUCTOS?></td>
                                            <td align="right"><?php echo number_format($ord->PIEZAS,0)?></td>
                                            <td><?php echo $ord->STATUS?></td>
                                            
                                            <td><?php echo $ord->FECHA_ASIGNA?>
                                            <br/><font color="brown"><?php echo $ord->FECHA_ASIGNA_F?></font></td>

                                            <td><?php echo $ord->FECHA_ALMACEN?><br/><font color="green"><?php echo $ord->FECHA_ALMACEN_F?></font></td>
                                            <td><?php echo $ord->USUARIO?></td>
                                            <td><?php echo substr($ord->ARCHIVO,30)?></td>

                                            <td><?php echo $ord->PRIORIDAD?></td>
                                            <td><a href="index.wms.php?action=detOrden&orden=<?php echo $ord->ID_ORD?>" target="popup" onclick="window.open(this.href, this.target, 'width=1600,height=600'); return false;"> Detalles</a><br/><a href=""> Enviar Correo</a></td>
                                            <td><input type="button" value="Eliminar" class="btn-sm btn-danger"></td>
                                        </tr>
                                    <?php endforeach ?>               
                                    </tbody>
                                </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script type="text/javascript">

    $(".report").click(function(){
        var t = $(this).val()
        var out = $("#"+t).val()
        $.ajax({
            url:'index.wms.php',
            type:'post',
            dataType:'json',
            data:{report:1, t, out},
            success:function(data){
                if(data.status == 'ok'){
                    if(out == 'x'){
                        $.alert('Descarga del archivo de Excel')
                        window.open( data.completa , 'download')
                    }
                    if(out == 'p'){
                        $.alert('Abrir la pagina en una ventana nueva')
                        window.open(data.completa, '_blank')
                    }
                    if(out == 'b'){
                        $.alert('Descargar el archivo en excel y Abrir la pagina en una ventana nueva')   
                        window.open( data.completa , 'download')
                    }
                }
            },
            error:function(){
                    $.alert('Ocurrio un error')   
            }
        })
    })
    
</script>