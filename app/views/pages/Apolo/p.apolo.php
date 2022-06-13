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
                            
                           
                            <b>Carga multiple de archivos de facturas (XML y PDF).</b>
                            <form action="index.php" method="post" enctype="multipart/form-data">
                                <input type="file" id="filesToUpload" name="files[]" multiple="" onchange="makeFileList()" accept="text/xml, .pdf" />
                                <input type="hidden" name="FORM_ACTION_FACTURAS_UPLOAD" value="FORM_ACTION_FACTURAS_UPLOAD" />
                                <input type="hidden" name="files2upload" value="" />
                                <input type="submit" value="Inicar Carga"/>
                                <input type="hidden" value="F" name="tipo">
                            </form>
                            </p>
                                <ul id="fileList">
                                    <li>No hay archivos seleccionados</li>        
                                </ul>
                            </p>
                            <br/><br/>
                        </div>
                        <div class="panel-body">
                            
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-apolo">
                                    <thead>
                                        <tr>
                                            <th>Ln</th>
                                            <th>Archivo</th>
                                            <th>RFC <br/>Cliente</th>
                                            <th>RFC <br/>Emisor</th>
                                            <th>Numero <br/> Documento</th>
                                            <th>Folio</th>
                                            <th>Fecha de Solicitud</th>
                                            <th>Fecha <br/>Limite Factura</th>
                                            <th>UUID</th>
                                            <th>Status</th>
                                            <th>Pedido <br/> Intelisis</th>
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
                                            <td><?php echo $i?></td>
                                            <td><a href="index.php?action=apolo&id=<?php echo $data->ID?>", target="popup"><?php echo $data->ARCHIVO;?></a> </td>
                                            <td><?php echo $data->RFC_CLIENTE;?></td>
                                            <td><?php echo $data->RFC_SELLER;?></td>
                                            <td><?php echo $data->NUM_DOC;?></td>
                                            <td><?php echo $data->FOLIO;?></td>
                                            <td><?php echo $data->FECHA?></td>
                                            <td><?php echo $data->FECHA_LIM?></td>         
                                            <td><?php echo $data->UUID?><br/><font color="blue"><b><?php echo $data->FACTURA_INT?></b></font></td>         
                                            <td><?php echo $status;?></td>         
                                            <td align="center"><?php echo $data->PEDIDO_INT ==''? '':'Pedido Web '.$data->PEDIDO_INT?></td>
                                            <td><select id="<?php echo $data->ID?>_sel" name="selector">
                                                <option value="e">Enviar</option>
                                                <option value="c">Carga Factura</option>
                                                <!--<option value="v">Verificar</option>-->
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
        var opc = document.getElementById(lin+'_sel').value;
            if(opc == 'c'){
                var msg = 'Se ha cargado el archivo'
            }else{
                var msg = 'Se ha Enviado el correo ...'
            }
            $.ajax({
                url:'index.php',
                type:'post',
                dataType:'json',
                data:{correoApolo:1, id:lin, opc},
                success:function(data){
                    if(data.status == 'ok'){
                        alert(msg)
                        location.reload(true)    
                    }else{
                        alert(data.msg)
                    }
                    
                }
            })
    })    

    function makeFileList() {
            var input = document.getElementById("filesToUpload");
            var ul = document.getElementById("fileList");
            while (ul.hasChildNodes()) {
                    ul.removeChild(ul.firstChild);
            }
            for (var i = 0; i < input.files.length; i++) {
                    var li = document.createElement("li");
                    li.innerHTML = input.files[i].name;
                    ul.appendChild(li);
            }
            if(!ul.hasChildNodes()) {
                    var li = document.createElement("li");
                    li.innerHTML = 'No hay archivos selccionados.';
                    ul.appendChild(li);
            }
            document.getElementById("files2upload").value = input.files.length;
    }
  //Cuando la página esté cargada completamente
  $(document).ready(function(){
    //Cada 10 segundos (10000 milisegundos) se ejecutará la función refrescar
    //setTimeout(refrescar, 120000);
  });

  function refrescar(){
    //Actualiza la página
    location.reload();
  }
  
</script>