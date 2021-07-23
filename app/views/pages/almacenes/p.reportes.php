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
                    <button class="btn report" value="pp">Posición de productos</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;<select id="pp">Seleccione
                                                    <option value="x">Excel</option>
                                                    <option value="p">PDF</option>
                                                    <!--<option value="b">Excel y PDF</option>
                                                    <option>Impresora</option>-->
                                                </select>
                </p>
                <p><label>Muestra en la posicion de cada uno de los productos en la bodega, con sus respectivas cantidades, su uso principal es la realizacion de revisiones (arqueos) para el control del almacen.</label></p>
            </div>
            <div>
                <p>
                    <button class="btn report" value="pc">Productos del componente</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;<select id="pc">Seleccione
                                                    <option value="x">Excel</option>
                                                    <option value="p">PDF</option>
                                                    <!--<option value="b">Excel y PDF</option>
                                                    <option>Impresora</option>-->
                                                </select>
                </p>
                <p><label>Muesta los productos que contiene cada uno de los componentes primarios y secundarios del almacen.</label></p>
            </div>
            <div>
                <p>
                    <button class="btn report" value="da">Disponibilidad del almacen</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;<select id="da">Seleccione
                                                    <option value="x">Excel</option>
                                                    <option value="p">PDF</option>
                                                    <!--<option value="b">Excel y PDF</option>
                                                    <option>Impresora</option>-->
                                                </select>                    
                </p>
                <p><label>Muestra las posiciones que tienen espacio en el almacen.</label></p>
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
        $.confirm({
            content: function () {
                var self = this;
                return $.ajax({
                            url:'index.wms.php',
                            type:'post',
                            dataType:'json',
                            data:{report:1, t, out},
                }).done(function (data) {
                    self.setContent('Se ha descargado el archivo');
                    //self.setContentAppend('Descarga de Reporte ');
                    self.setTitle('Reportes del almacen');
                        if(data.status == 'ok'){
                            if(out == 'x'){
                                //$.alert('Descarga del archivo de Excel')
                                window.open( data.completa , 'download')
                            }
                            if(out == 'p'){
                                //$.alert('Abrir la pagina en una ventana nueva')
                                window.open(data.completa, '_blank')
                            }
                            if(out == 'b'){
                                //$.alert('Descargar el archivo en excel y Abrir la pagina en una ventana nueva')   
                                window.open( data.completa , 'download')
                            }
                      }
                }).fail(function(){
                    self.setContent('Algo ocurrio y no pude procesarlo, intente nuevamente.');
                });
            }
        }); 
    })
    
</script>