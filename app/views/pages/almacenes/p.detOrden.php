<br /><br />
<style type="text/css">
    .num {
        width: 5em;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div>Detalles de la Orden</div>
            <br/>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
            <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover" id="dataTables">
                                    <thead>
                                        <tr>
                                            <th> Clave </th>
                                            <th> Producto </th>
                                            <th> Piezas </th>
                                            <th> Cajas </th>
                                            <th> Color </th>
                                            <th> Cedis </th>
                                            <th> Piezas Surtidas </th>
                                            <th> Cajas Surtidas </th>
                                            <th> Estado </th>
                                            <th> Observaciones </th>
                                            <th> Finalizar </th>
                                            <th> Informar / Correo  </th>
                                        </tr>
                                    </thead>
                                  <tbody>
                                    <?php foreach ($orden as $ord): 
                                        $color='';
                                        //$color = '';if(trim($kp->STATUS) == 'Eliminado'){ $color="style='background-color:#f33737'";}
                                        ?>
                                       <tr class="odd gradeX color" <?php echo $color?>>
                                            <td><?php echo $ord->PROD?></td>
                                            <td><?php echo $ord->DESCR?></td>
                                            <td><?php echo $ord->PZAS?></td>
                                            <td><?php echo $ord->CAJAS?></td>
                                            <td><?php echo $ord->COLOR?></td>
                                            <td><?php echo $ord->CEDIS?></td>
                                            <td><?php echo $ord->PZAS_SUR?></td>
                                            <td><?php echo $ord->CAJAS_SUR?></td>
                                            <td><?php echo $ord->STATUS?></td>
                                            <td><?php echo $ord->OBS?></td>
                                            <td>
                                                <a href="index.wms.php?action=detOrden&orden=<?php echo $ord->ID_ORD?>" target="popup" onclick="window.open(this.href, this.target, 'width=800,height=600'); return false;"> Finalizar</a></td>
                                            <td><a >Informar</a></td>
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