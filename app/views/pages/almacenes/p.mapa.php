<br/><br/>

<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           <font size="5px"> Mapa del Almacen <?php echo $param?></font> 
                        </div>
                        <br/>
                            <br/><br/>
                            <div class="panel-body">
                            <div class="table-responsive">                            
                                <table class="table table-striped table-bordered table-hover" id="dataTables">
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
                                                <td><?php echo $k->ETIQUETA?></td>
                                                <?php foreach ($infoA1['sec'] as $sec):?>
                                                    <?php if($sec->COMPP == $k->ID_COMP):
                                                        $color = '';
                                                        if($sec->DISP == 'si'){
                                                            $color ="style='background-color:lightblue';";
                                                        }else{
                                                            $color ="style='background-color:#FFE0CA';";
                                                        }
                                                    ?>
                                                        <td title="" class="odd gradeX info" idc="<?php echo $sec->ID_COMP?>" <?php echo $color ?>><?php echo $sec->ETI.'('.$sec->EXIS.')'?></td>
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
    $(".info").mouseover(function(){
        var comp = $(this)
        comp.prop('title', 'Productos')
    })
</script>