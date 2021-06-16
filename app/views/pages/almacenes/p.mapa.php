<br/><br/>
<!--<style type="text/css">
        .marked {
        background-color: yellow;
        border: 3px red solid;
        }
</style>-->
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           <font size="5px"> Mapa del Almacen <?php echo $param?></font> 
                        </div>
                           <div class="panel-body">
                                <p><label>Para ver el contenido de la tarima colocar el cursor sobre la etiqueta.</label></p>
                                <p><label>Para ingresar por Linea dar click en la primer columna.</label></p>
                                <p><label>Para ingresar por Tarima dar click en la etiqueta.</label></p>
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <?php $t=0; for($i=0; $i <=$infoA1['tarimas'] ; $i++):?>
                                                <th><?php echo $t==0? 'Pasillo | Tarima':$t; $t++ ?></th>
                                            <?php endfor; ?>
                                        </tr>
                                    </thead>
                                  <tbody>
                                    <?php foreach ($infoA1['datos'] as $k): ?>         
                                        <?php if(!empty($k->LETRA)):?>
                                            <tr>
                                                <td class="exe compp" t="l" idc="<?php echo $k->ID_COMP?>" desc="<?php echo $k->ETIQUETA?>" tar=""><?php echo $k->ETIQUETA?></td>
                                                <?php foreach ($infoA1['sec'] as $sec):?>
                                                    <?php if($sec->COMPP == $k->ID_COMP):
                                                        $color = '';
                                                        if($sec->DISP == 'si'){
                                                            $color ="style='background-color:lightblue';";
                                                        }else{
                                                            $color ="style='background-color:#FFE0CA';";
                                                        }
                                                    ?>
                                                        <td title="" class="odd gradeX info exe" t="t" idc="<?php echo $sec->ID_COMP?>" desc="<?php echo $sec->ETI?>" <?php echo $color ?> dis="<?php echo $sec->DISP?>"> <?php echo $sec->ETI.'('.$sec->EXIS.')'?> </td>
                                                    <?php endif;?>
                                                <?php endforeach;?>
                                            </tr>               
                                        <?php endif;?>
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
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script type="text/javascript">  
    
    var alm = <?php echo "'".$param."'"?>;
    var titulo = ''; var tipo = ''; var tarDisp = 0;
    $(".info").mouseover(function(){
        var contenido = ''
        var comp = $(this)
        var comps = $(this).attr('idc')
        var dis = $(this).attr('dis')
        if(dis == 'si'){
            return false
        }
        $.ajax({
            url:'index.wms.php',
            type:'post',
            dataType:'json',
            data:{prods:comps},
            success:function(data){
                for(const [key, value] of Object.entries(data.datos)){
                    for(const [k, val ] of Object.entries(value)){
                        if(k == 'INTELISIS'){var prod = val}
                        if(k == 'DISPONIBLE'){var disp = val}
                        if(k == 'CANT'){var cant = val}
                        if(k == 'UNIDAD'){var uni = val}
                        if(k == 'PIEZAS'){var pzas = val}
                    }
                    contenido += cant + ' ' + uni  + ' ' + prod + ', piezas ' + pzas + ' \n'
                }
                comp.prop('title', contenido)
            },
            error:function(){
            }
        })
    })

    $(document).ready(function(){
        disp()
    })

    function disp(){
        $(".compp").each(function(){
            var idc = $(this).attr('idc')
            var lin = $(this)
            $.ajax({
                url:'index.wms.php',
                type:'post',
                dataType:'json',
                data:{dispLin:idc},
                success:function(data){
                    if(data.status == 'ok'){
                        lin.attr("tar", data.disp)
                    }
                },
                error:function(){

                }
            })
        })
    }

    $(".exe").click(function(){
        var tar =$(this)
        var idc = $(this).attr('idc')
        var desc = $(this).attr('desc')
        var t = $(this).attr('t')
        if(t == 'l'){
            tarDisp = $(this).attr('tar')
            titulo = 'Entrada al almacen por linea.'
            tipo = 'Linea'
            xtar = '<br/><br/>Cantidad por tarima <input type="text" size="5" class="ft">'+
                    '<br/><br/> Tarimas disponibles = ' + tarDisp;
            disp = 'si'
        }else{
            tarDisp = 1
            titulo = 'Entrada al almacen por tarima.'
            tipo = 'Tarima'
            xtar = '<input type="hidden" value="1" class="ft">'
            disp = $(this).attr('dis')
        }
        if(tarDisp <= 0 || disp =='no'){
            $.alert('No hay tarimas disponibles, favor de actualizar la pagina')
            return false 
        }
        //$.alert("Dio click en la tarima " + idc)
        $.confirm({
            columnClass: 'col-md-8',
            title:titulo,
            content:'Entrada al almacen' +
            '<br/> Se dara entrada a  la ' + tipo + ': '+ desc +
            '<br/> Almacen: ' + alm +
            '<br/> '+ tipo +':' + desc +
            '<br/>Seleccione el producto a ingresar: <input type="text" placeholder="Producto" class="prod chgProd" size="100">' + 
            '<br/><br/>Unidad: <select class="uni">'+
            <?php foreach($uni as $u):?>
               '<option value="<?php echo $u->ID_UNI?>" factor="<?php echo $u->FACTOR?>"><?php echo $u->FACTOR."-->".$u->DESC?></option>'+
            <?php endforeach;?>
            '</select>'+
            '<br/><br/>Cantidad: <input type="text" size="5" class="cant" >'+
             xtar +
            '<br/><br/> Piezas totales: <label class="pzas"></label>'
            ,
            buttons:{
                ingresar:{
                text:'Ingresar',
                keys:['enter'],
                btnClass:'btn-green',
                    action:function(){
                        var prod = this.$content.find('.prod').val()
                        var uni = this.$content.find('.uni').val()
                        var cant = this.$content.find('.cant').val()
                        var pzas = this.$content.find('.pzas').html()
                        var ft = this.$content.find('.ft').val()
                        if(!$.isNumeric(ft) || !$.isNumeric(cant)){
                            $.alert("Coloque un número valido")
                            return false
                        }
                        if( t=='l' && ((parseFloat(cant) / parseFloat(ft)) >= parseFloat(tarDisp)) ){
                            this.$content.find('.ft').focus()
                            $.alert("Se necesitan mas tarimas des las diponibles cantidad: " + cant  + " ft "  + ft  + 'Disp ' + tarDisp)
                            return false
                        }
                        if($.isNumeric(cant)){
                            $.ajax({
                                url:'index.wms.php',
                                type:'post',
                                dataType:'json',
                                data:{ingMap:idc, prod, cant, uni, pzas, ft, t},
                                success:function(data){
                                    if(data.status=='ok'){
                                        $.alert('Se ingresa el producto' + prod)

                                        ///cambiar color a rojo y actualizar la cantidad
                                        //tar.()
                                    }else{
                                        $.alert(data.msg)
                                    }
                                }, 
                                error:function(){
                                    $.alert('Ocurrio un error, favor de actualizar, si persiste comunicarse con sistemas 55 50553392')
                                }
                            })
                        }else{
                            $.alert('Seleccione una cantidad valida')
                            return false
                        }
                    }
                },
                cancelar:{
                text:'Cancelar',
                keys:['esc'],
                btnClass:'btn-red',
                action:function(){
                }
                },
            },
        });
    })
    $("body").on("click",".prod", function(e){
        e.preventDefault();
        //$.alert("cambio")
        $(".chgProd").autocomplete({
            source: "index.wms.php?producto=1",
            minLength: 2,
            select: function(event, ui){
            }
        })

        $(".cant").change(function(){
            var cant = $(this).val()
            var fact = $("option:selected", ".uni").attr('factor')
            var piezas = cant * fact;
            $(".pzas").html(piezas)
        })
    });

   // $(".tarimas:button").addClass( "marked" );

</script>