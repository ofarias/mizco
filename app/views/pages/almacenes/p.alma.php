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
                Almacenes
            </div>
            <div class="panel-body">
                <div class="table-responsive">                            
                    <table class="table table-striped table-bordered table-hover" id="dataTables-oc">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Ubicación</th>
                                <th>Volumen</th>
                                <th>Largo <br/> m</th>
                                <th>Ancho <br/> m</th>
                                <th>Alto <br/> m</th>
                                <th>Area</th>
                                <th>Lineas</th>
                                <th>Pasillos</th>
                                <th>Estado</th>
                                <th>Mapa</th>
                                <th colspan="2" style="text-align:center">Limpiar</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td colspan="10"></td>
                                <!--
                                <td><a target="_blank" href="index.php?action=imprimircatgastos" class="btn btn-info">Imprimir <i class="fa fa-print"></i></a></td>-->
                                <td><button class="btn-sm btn-info add" >Agregar &nbsp;<i class="fa fa-plus"></i></button></td>
                                
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php $i=0;foreach($alm as $r): $i++;?>
                            <tr class="color" id="linc<?php echo $i?>">
                                <td><?php echo $r->NOMBRE?></td>
                                <td><?php echo $r->UBICACION?></td>
                                <td><?php echo $r->VOLUMEN?></td>
                                <td><?php echo $r->LARGO?></td>
                                <td><?php echo $r->ANCHO?></td>
                                <td><?php echo $r->ALTO?></td>
                                <td><?php echo $r->AREA?></td>
                                <td><?php echo $r->PASILLOS?></td>
                                <td><?php echo $r->STATUS?></td>
                                <td></td>
                                <td><button class="mapa" al="<?php echo $r->ID?>">Mapa</button></td>
                                <td align="center" ><input type="button" value="Limpiar Movimientos" class="limpAlma" id="<?php echo $r->ID?>" nom="<?php echo $r->NOMBRE?>" tipo="movs"></td>
                                <td align="center" ><input type="button" value="Limpiar Ordenes" class="limpAlma" id="<?php echo $r->ID?>" nom="<?php echo $r->NOMBRE?>" tipo="ordenes"></td>
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

    $(".mapa").click(function(){
        var a = $(this).attr('al')
        window.open("index.wms.php?action=mapa&opc=''&param="+a, "_blank")
    })

    $(".limpAlma").click(function(){
        var alm = $(this).attr("id")
        var nom = $(this).attr("nom")
        var tipo = $(this).attr("tipo")
        var t1= ''
        var t2= ''
        if(tipo == 'movs'){
            t1 = 'Eliminacion de Movimientos del ' + nom
            t2 = 'Movimientos a eliminar del ' + nom
        }else{
            t1 = 'Eliminacion de Ordenes de compra.'
            t2 = 'Se eliminaran todas las ordenes de compra a la fecha de hoy, no hay forma de recuperar la información.'
        }
        $.confirm({
            columnClass: 'col-md-6 col-md-offset-3',
            title: t1,
            content: t2+
            "<br/> "+ 
            "<br/><br/><label> Al eliminar los movimientos no tendran forma de recuperarlo</label> "+
            "<br/><br/> <label> </label"
            ,
            buttons:{
                Aceptar:function(){
                    
                    $.ajax({
                        url:'index.wms.php',
                        type:'post', 
                        dataType:'json', 
                        data:{delMovs:alm, tipo},
                        success:function(data){
                            if(data.status == 'ok'){
                            }
                        },
                        error:function(){
                            alert('revise la informacion')
                        }
                    });
                },
                Cancelar:function(){
                     
                }
            }
        })
    })
</script>