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
                    <form action="index.wms.php" method="post" enctype="multipart/form-data">
                        <input type="file" name="files[]" multiple="" onchange="makeFileList()" id="filesToUpload" accept=".xls, .csv, .txt, .xlsx">
                        <input type="hidden" name="upload_ordenes" value="upload_ordenes" />
                        <input type="hidden" name="files2upload" value="" />
                        <input type="submit" value="Cargar Orden" >
                    </form>
                    <ul id="fileList">
                        <li>No hay archivos seleccionados</li>        
                    </ul>
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
                                            <th> Ln <br/><input type="checkbox" name="selAll" class="selAll"></th>
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
                                            <th> Trabajar </th>
                                            <th> Eliminar </th>
                                        </tr>
                                    </thead>
                                  <tbody>
                                    <?php foreach ($ordenes as $ord): 
                                        $color='';
                                        if($ord->ID_STATUS == 3){
                                            $color="style='background-color:#ddf8ff'";
                                        }elseif($ord->ID_STATUS == 8){
                                            $color="style='background-color:#ffb7b2'";
                                        }
                                        ?>
                                       <tr class="odd gradeX color" <?php echo $color?>>
                                            <th><input type="checkbox" name="sel" value="<?php echo $ord->ARCHIVO?>" ids="<?php echo $ord->ID_ORD?>"></th>
                                            <td><?php echo $ord->CLIENTE?><br/>
                                                <?php if($ord->LOGS > 0 or $ord->LOGS_DET > 0){?>
                                                    <label title="Tiene los siguientes movimientos" class="logs" ido="<?php echo $ord->ID_ORD?>">Movimientos </label> 
                                                <?php }?>
                                            </td>
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

                                            <td><a href="..\\..\\Cargas Ordenes\\<?php echo $ord->ARCHIVO?>" download><?php echo $ord->ARCHIVO?></a></td>

                                            <td><?php echo $ord->PRIORIDAD?></td>
                                            <td><a class="envio"> Enviar Correo</a>
                                                <br/>
                                                <a href="index.wms.php?action=detOrden&orden=<?php echo $ord->ID_ORD?>&t=d" target="popup" onclick="window.open(this.href, this.target, 'width=1600,height=600'); return false;"> Detalles</a>
                                                <br/>
                                                <a href="index.wms.php?action=detOrden&orden=<?php echo $ord->ID_ORD?>&t=p" target="popup" onclick="window.open(this.href, this.target, 'width=1600,height=600'); return false;">Productos de la Orden</a>
                                                <br/>
                                                <a href="index.wms.php?action=detOrden&orden=<?php echo $ord->ID_ORD?>&t=s" target="popup" onclick="window.open(this.href, this.target, 'width=1600,height=600'); return false;">Surtir Orden</a>
                                                </td>
                                            <td><input type="button" value="Eliminar" class="btn-sm btn-danger del" oc="<?php echo $ord->ID_ORD?>"><br/>
                                                <a class="remp" ido="<?php echo $ord->ID_ORD?>" logs="<?php echo $ord->LOGS + $ord->LOGS_DET?>">Remplazar Archivo</a></td>
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

    $(".remp").click(function(){
        var m = $(this).attr('logs')
        var ord = $(this).attr('ido')
            $.confirm({
                columnClass: 'col-md-8',
                title: 'Reemplazar archivo de Orden de compra',
                content: 'Desea reemplazar el archivo con ' + m + ' movimientos?' +
                '<form action="upload_reemp.php" method="post" enctype="multipart/form-data" class="formName">' +
                '<div class="form-group">'+
                '<br/>Archivo nuevo: '+
                '<input type="file" name="fileToUpload" class="file" accept=".xls, .csv, .txt, .xlsx"><br/>'+
                '<input type="hidden" name="ido" value="'+ord+'">'+
                '<br/>Motivo del reemplazo:<br/>'+
                '<textarea class="mot" cols="80" rows="10" name="mot"></textarea>'+
                '<br/><br/>'+
                '</form>'
                ,
                buttons: {
                formSubmit: {
                text: 'Reemplazar y avisar',
                btnClass: 'btn-blue',
                action: function () {
                    var file = this.$content.find('.file').val();
                    var mot = this.$content.find('.mot').val()
                    var form = this.$content.find('.formName')
                    form.submit()        
                   }
                },
                cancelar: function () {
                },
                },
        });
    })

    $(".logs").mouseover(function(){
        var ido = $(this).attr('ido')
        var titulo = ''
        var t = $(this)
        $.ajax({
            url:'index.wms.php',
            type:'post',
            dataType:'json',
            data:{log:1, ido, d:2 },
            success:function(data){
                if(data.logs > 0){
                    for(const [key, value] of Object.entries(data.datos)){
                        for (const [k, val] of Object.entries(value)){
                            console.log(k + ' valor: ' + val);
                            if(k == 'SUBTIPO'){var sub = val;}
                            if(k == 'USUARIO'){var usr = val;}
                            if(k == 'FECHA'){var fecha = val;}
                            if(k == 'OBS'){var obs = val;}
                        }
                        //alert(sub)
                        titulo += sub + " -- " +usr + " el " + fecha + " - "+ obs +"\n"
                    }
                }
                t.prop('title', titulo)
            },
            error:function(){

            }
        });
    });

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

    $(".del").click(function(){
        var id = $(this).attr('oc')
        $.confirm({
            title: 'Eliminar la Orden de compra?',
            content: 'Solo se pueden eliminar Ordenes que no esten trabajadas!',
            buttons: {
                Aceptar: function () {
                    $.ajax({
                        url:'index.wms.php',
                        type:'post',
                        dataType:'json',
                        data:{delOc:1, id},
                        success:function(data){
                            if(data.status == 'ok'){
                                //$("#e_"+id).hide()
                                //document.getElementById(id).classList.add('hidden')
                                //document.getElementById("c_"+id).classList.add('hidden')
                                    $.alert(data.msg)
                            }else if(data.status== 'no'){
                                $.alert("Se encontraron movimientos o dependencias del componente")                                
                            }else if(data.status=='p'){
                                $.alert("El componente primario tiene asociaciones, hay que eliminar las asociaciones antes.")                                
                            }
                        },
                        error:function(){
                            $.alert('Ocurrio un error, favor de actualizar su pantalla he intentarlo nuevamente')
                        }
                    })
                },
                Cancelar: function () {
                    $.alert('No se realizo ninguna acción.');
                },
                //somethingElse: {
                //    text: 'Something else',
                //    btnClass: 'btn-blue',
                //    keys: ['enter', 'shift'],
                //    action: function(){
                //        $.alert('Something else?');
                //    }
                //}
            }
        });
    })

    $(".selAll").change(function(){
        $("input[name=sel]").prop('checked', $(this).prop("checked"));
    })

    $(".envio").click(function(){
        var selec = cheks();
        if(selec['lista'].length == 0){
            $.alert('Debe Seleccionar por lo menos un archivos.');
            return false;            
        }
        $.confirm({
            columnClass: 'col-md-8',
            title: 'Envio de correo de los archivos seleccionados',
            content: 'Se enviaran los documentos seleccionados.' + 
            '<form action="index.php" class="formName">' +
            '<div class="form-group">'+
            '<br/>Para: '+
            '<input type="email" multiple placeholder="Separar las direcciones con coma , " class="dir" size="100" maxlenght="150"><br/><br/>'+
            '<input type="checkbox" name="mail" value="alberto@selectsound.com.mx" correo="alberto@selectsound.com.mx"> Alberto Mizrahi: alberto@selectsound.com.mx<br/>'+
            '<input type="checkbox" name="mail" value="rafael@selectsound.com.mx" correo="rafael@selectsound.com.mx"> Rafael Mizrahi: rafael@selectsound.com.mx<br/>'+
            '<input type="checkbox" name="mail" value="esther@selectsound.com.mx" correo="esther@selectsound.com.mx"> Esther Gonzales: esther@selectsound.com.mx<br/>'+
            '<input type="checkbox" name="mail" value="inteligenciac@selectsound.com.mx" correo="intelicenciac@selectsound.com.mx"> Olga Perez: inteligenciac@selectsound.com.mx<br/>'+
            '<br/>Mensaje:<br/>'+
            '<textarea class="msg" cols="100" rows="15"></textarea>'+
            '<br/><br/>'+
            '</form>'+
            'Archivos que se Adjuntaran: <br/>' + selec['lista']
            ,
                buttons: {
                formSubmit: {
                text: 'Envio de correo',
                btnClass: 'btn-blue',
                action: function () {
                    var dir = this.$content.find('.dir').val();
                    var msg = this.$content.find('.msg').val();
                    var a = '';
                    var c = 0;
                    this.$content.find("input[name=mail]").each(function(index){ 
                       if($(this).is(':checked')){
                          c++;
                          a+= ','+$(this).val();
                       }
                    });
                    if(a.length >0){
                        a = a.substring(1)
                    }
                    dir += a 
                    if(dir==''){
                        $.alert('Debe de contener por lo menos una direccion');
                        return false;
                    }else{
                        //$.alert("Se envia el correo")
                        $.ajax({
                            url:'index.wms.php',
                            type:'post',
                            dataType:'json',
                            data:{envMail:1, dir, msg, files:selec['a'], ids:selec['ids']},
                            success:function(data){
                                alert(data.msg);
                                //location.reload(true)
                            }
                        });
                    }
                   }
                },
                cancelar: function () {
                },
                },
                onContentReady: function () {
                // bind to events
                var jc = this;
                //alert(jc);
                this.$content.find('form').on('submit', function (e) {
                    // if the user submits the form by pressing enter in the field.
                    e.preventDefault();
                    jc.$$formSubmit.trigger('click'); // reference the button and click it
                });
            }
        });
    })

    function cheks(){
        var a = '';
        var lista = '';
        var c = 0;
        var ids='';
        $("input[name=sel]").each(function (index) { 
           if($(this).is(':checked')){
              c++;
              lista+=  c + '.-' + $(this).val() + '<br/>';
              ids += ',' + $(this).attr('ids');
              a+= ','+$(this).val();
           }
        });
        return {lista, c, a, ids};
    }


        

</script>