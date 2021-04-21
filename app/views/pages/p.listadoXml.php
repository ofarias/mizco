<!--<meta http-equiv="Refresh" content="10000">-->
<br /><br />
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                XML Soriana
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="listadoxml" class="display" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>FACTURA</th>
                                <th>PROVEEDOR</th>
                                <th>IMPORTE</th>
                                <th>ENVIAR</th>
                                <th>NO ENVIAR</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($listado as $data):
                                ?>
                                <tr id="<?php echo $data->CVE_DOC; ?>" class="odd gradeX">
                                    <td><p><?php echo $data->CVE_DOC; ?></p></td>
                                    <td><?php echo $data->PROVEEDOR; ?></td>
                                    <td>$ <?php echo number_format($data->IMPORTE, 2, '.', ','); ?></td>
                                    <td><button onclick="enviaxml('<?php echo $data->CVE_DOC; ?>');"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button></td>
                                    <td><button class="btn btn-danger" onclick="noenviar('<?php echo $data->CVE_DOC; ?>')">No Enviar</button></td>
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
<div id="pop">
    <center><i class="fa fa-spinner fa-spin fa-5x" aria-hidden="true"></i></center>
</div>
<script type="text/javascript" language="JavaScript" src="app/views/bower_components/jquery/dist/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $('#pop').hide();
        $('#listadoxml').DataTable();
    });
                                        
        function enviaxml(id) {
            //alert(id);
            $.confirm({
                title: 'Envío de xml',
                content: '¿Seguro que deseas validar el xml del folio de factura ' + id + '?',
                buttons: {
                    Enviar: function () {
                        // here the key 'something' will be used as the text.
                        var url = "index.php"; // El script a dónde se realizará la petición.
                        $.ajax({
                            type: "POST",
                            url: url,
                            dataType: "json",
                            data: {compruebaXml: id}, // Adjuntar los campos del formulario enviado.
                            beforeSend: function () {
                                var popup = $('#pop');
                                popup.css({
                                    'position': 'absolute',
                                    'left': ($(window).width() / 2 - $(popup).width() / 2) + 'px',
                                    'top': ($(window).height() / 2 - $(popup).height() / 2) + 'px'
                                });
                                popup.show();
                            },
                            success: function (data)
                            {
                            console.log(data);
                            $('#pop').hide();
                                if (data.status == "OK") {
                                //después de que la valiadacion sea correcta, eliminamos el row
                                $('#' + id + '').remove();
                                //y avisamos que se validó
                                $.alert('Se validó correctamente.');
                            } else {
                                $.alert('Se ha enviado el correo favor de revisar en su bandeja de entrada o en el SPAM');
                            }

                        }
                    });
                },
            Cancelar: {
                //text: 'Something else &*', // Some Non-Alphanumeric characters
                //action: function () {
                //$.alert('You clicked on something else');
                //}
                    }
                }
            });
        }

        function noenvia(id){
           if(confirm('Desea eliminar el documento: ' + id+ ' de esta lista?, posteriormente no se podra volver a colocar')){
                $.ajax({
                    url:'index.php',
                    type:'post',
                    dataType:'json',
                    data:{noenviar:id},
                    success:function(data){
                        alert('Se desmarco la factura')
                        location.reload(true)
                    },
                    error:function(){
                        alert('Algo no funciono como se esperaba, favor de reportar a sistemas')
                    }
                });
           }
        }
</script>
