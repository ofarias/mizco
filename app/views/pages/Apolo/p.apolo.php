	<br />
    <?php $e= 0; foreach ($info as $k){
    $k->STATUS==0? $e++:0;
    }?>
    	<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Documentos Apolo Walmart
                            <br/><br/>
                            <?php if($e > 0){?>
                                <button class="btn-sm btn-warning exec" value="all">Enviar Recibidas</button>
                            <?php }?>
                            
                            <button class="btn-sm btn-info">Cargar CFDI y PDF </button>
                            <br/><br/>
                        </div>
                        <div class="panel-body">
                            
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-usuarios">
                                    <thead>
                                        <tr>
                                            <th>Archivo</th>
                                            <th>RFC <br/>Cliente</th>
                                            <th>RFC <br/>Emisor</th>
                                            <th>Numero <br/> Documento</th>
                                            <th>Folio</th>
                                            <th>Fecha de Carga</th>
                                            <th>Fecha <br/>Limite Factura</th>
                                            <th>UUID</th>
                                            <th>Status</th>
                                            <th>-------</th>
                                        </tr>
                                    </thead>                                   
                                  <tbody>
                                    	<?php $i =0; 
                                    	foreach ($info as $data):
                                        $i++; 
                                            switch ($data->STATUS) {
                                                case 0:
                                                    $status = 'Recibido';
                                                    break;
                                                case 1:
                                                    $status = 'Enviado';
                                                    break;
                                                case 2:
                                                    $status = 'Facturado';
                                                    break;
                                                case 3:
                                                    $status = 'Concluido';
                                                    break;
                                                default:
                                                    # code...
                                                    break;
                                            }
                                        ?>
                                        <tr class="odd gradeX">
                                            <td><a href="index.php?action=apolo&id=<?php echo $data->ID?>", target="popup"><?php echo $data->ARCHIVO;?></a> </td>
                                            <td><?php echo $data->RFC_CLIENTE;?></td>
                                            <td><?php echo $data->RFC_SELLER;?></td>
                                            <td><?php echo $data->NUM_DOC;?></td>
                                            <td><?php echo $data->FOLIO;?></td>
                                            <td><?php echo $data->FECHA?></td>
                                            <td><?php echo $data->FECHA_LIM?></td>         
                                            <td><?php echo $data->UUID?></td>         
                                            <td><?php echo $status;?></td>         
                                            <td><select id="<?php echo $data->ID?>_sel" name="selector">
                                                <option value="e">Enviar</option>
                                                <option value="c">Cargar</option>
                                                <option value="v">Verificar</option>
                                            </select>
                                            <button class="btn-sm btn-success exec" value="<?php echo $data->ID?>"></button>
                                        </td>
                                        </tr>
                                        <?php endforeach; ?>
                                 </tbody>
                                
                                </table>
                            </div>
			          </div>
			</div>
		</div>
</div>
<div id="dialog" title="Agregar Nuevo Componente">
  <form action="index.php" method="post">
	<input type="hidden" name="ccomp" />
	<input type="text" name="nombre" placeholder="Colocar Nombre Componente" /><br />
	<input type="text" name="duracion" placeholder="Duracion en Horas" /><br />
	<input type="text"  name="tipo" placeholder="Tipo" /><br />
	<button type="submit" class="btn btn-warning" >Agregar</button>
  </form>
</div>

<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script>

    $(".exec").click(function(){
        var lin = $(this).val();
        var sel = $("#"+lin+"_sel").val();
        var opc = '';
            $.ajax({
                url:'index.php',
                type:'post',
                dataType:'json',
                data:{correoApolo:1, id:lin, opc},
                success:function(data){
                    alert('Se ha Enviado el correo ...')
                    location.reload(true)
                }
            })
    })    
  
</script>