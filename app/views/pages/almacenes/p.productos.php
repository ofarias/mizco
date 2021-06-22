<br /><br />
<style type="text/css">
    .num {
        width: 5em;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Productos en Intelisis <input type="button" value="Actualizar" class="btn-sm btn-primary actProdInt">
            </div>
            <div class="panel-body">
                <div class="table-responsive">                            
                    <table class="table table-striped table-bordered table-hover" id="dataTables-productos">
                        <thead>
                            <tr>
                                <th>Clave</th>
                                <th>Descripción</th>
                                <th>Presentación <br/>entrada</th>
                                <th>Largo <br/> cm</th>
                                <th>Ancho <br/> cm</th>
                                <th>Alto <br/> cm</th>
                                <th>Peso <br/>Volumétrico</th>
                                <th title="Piezas por Palet/Tarima.">Piezas <br/>x Palet</th>
                                <th title="Cantidad de Piezas de Origen por Caja.">Master</th>
                                <th>Tipo <br/>Intelisis</th>
                                <th>Estatus <br/>Intelisis</th>
                                <th>Guardar</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td colspan="8"></td>
                                <!--
                                <td><a target="_blank" href="index.php?action=imprimircatgastos" class="btn btn-info">Imprimir <i class="fa fa-print"></i></a></td>
                                <td><a href="index.php?action=nuevogasto" class="btn btn-info">Agregar <i class="fa fa-plus"></i></a></td>
                                -->
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php $i=0;foreach($info as $row): $i++;?>
                            <tr class="color" id="linc<?php echo $i?>">
                                <td><?php echo $row->ID_INT;?></td>
                                <td id="prod_<?php echo $i?>"><?php echo $row->DESC;?></font></td>
                                <td><?php echo $row->PZS_ORIG;?></td>
                                <td align="center"><input type="number" name="p_o" value="<?php echo $row->LARGO;?>" step="any" class="num lg marca" lin="<?php echo $i?>" id="lg<?php echo $i?>">
                                    <br/><font color="blue" ><?php echo $row->LARGO;?></font>
                                </td>
                                <td align="center"><input type="number" name="p_o" value="<?php echo $row->ANCHO;?>" step="any" class="num an marca" lin="<?php echo $i?>" id="an<?php echo $i?>"><br/><font color="blue" ><?php echo $row->ANCHO;?></font></td>
                                <td align="center"><input type="number" name="p_o" value="<?php echo $row->ALTO;?>" step="any" class="num al marca" lin="<?php echo $i?>" id="al<?php echo $i?>"><br/><font color="blue" ><?php echo $row->ALTO;?></font></td>
                                <td align="right"><?php echo number_format((($row->LARGO * $row->ANCHO * $row->ALTO)/166),5)?></td> 
                                <td align="center" title="Piezas por Palet/Tarima."><input type="number" name="p_o" value="<?php echo $row->PZS_PALET_O;?>" step="any" class="num p marca" lin="<?php echo $i?>" id="p<?php echo $i?>"><br/><font color="blue" ><?php echo $row->PZS_PALET_O;?></font></td>
                                <td align="center" title="Cantidad de Piezas de Origen por Caja."><input type="number" name="p_o" value="<?php echo $row->UNIDAD_ORIG;?>" step="any" class="num uo marca" lin="<?php echo $i?>" id="uo<?php echo $i?>"><br/><font color="blue" ><?php echo $row->UNIDAD_ORIG;?></font></td>
                                <td><?php echo $row->TIPO_INT?></td>
                                <td><?php echo $row->STATUS?></td>
                                <td>
                                    <input type="button" name="gd" value="Guardar" class="btn-sm save hidden" ln="<?php echo $i?>" id="bg<?php echo $i?>" cve="<?php echo $row->ID_PINT?>">
                                </td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
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

    $(".actProdInt").click(function(){
        $.alert('Actualiza producto en intelisis')
        window.open("index.wms.php?action=wms_menu&opc=pa", '_self')
    })

    $(".marca").change(function(){
        var val = $(this).val()
        var lin = $(this).attr('lin')
        var obj=document.getElementById("bg"+lin)
        obj.classList.remove('hidden')
        var linea = document.getElementById("linc"+lin)
        linea.style.background="#ff953d";
    })

    $(".save").click(function(){
        var lin = $(this).attr('ln');
        var prod = document.getElementById('prod_'+lin).innerHTML
        var cve = $(this).attr('cve');
        var an = document.getElementById('an'+lin).value
        var al = document.getElementById('al'+lin).value
        var lg = document.getElementById('lg'+lin).value
        var p = document.getElementById('p'+lin).value
        var uo = document.getElementById('uo'+lin).value
        var linea = document.getElementById("linc"+lin)
        var obj=document.getElementById("bg"+lin)
        /*$.confirm({
            content:function(){
                var self = this;
                return $.ajax({
                            url:'index.vms.php',
                            type:'post',
                            dataType:'json',
                            data:{actProd:cve, lg, an, al, p, uo} 
                        }).done(function(response){
                            self.setContent('Descripción')+response.desc;
                            self.setContectAppend('<br>version');
                            self.setTitle('Correcto')
                        }).fail(function(){
                            selt.setContent('No se pudo guardar favor de verificar los valores')
                        })
            }
        })*/
        if(confirm('Desea grabar los cambios?')){
            $.ajax({
                url:'index.wms.php',
                type:'post',
                dataType:'json',
                data:{actProd:cve, lg, an, al, p, uo}, 
                success:function(data){
                    alert(data.msg)
                    linea.style.background="#efffbb";
                    obj.classList.add('hidden')
                },
                error:function(){
                    alert('ocurrio un problema favor de intentar nuevamente')
                }
            })    
        }else{
            return false;
        }
        
        
    })
  
</script>