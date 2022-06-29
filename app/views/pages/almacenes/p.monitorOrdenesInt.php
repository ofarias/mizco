<br /><br />
<style type="text/css">
    .num {
        width: 5em;
    }
</style>
<div class="row">
    <div class="col-lg-12">
            <div tyle="color: blue;"> 
                    <?php $lt=$_SESSION['user']->NUMERO_LETRAS; if($lt==1){?>
                <p>
                    <div class="col-lg-12">
                        <div class="col-lg-6">
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
                        </div>
                        <div class="col-lg-6">
                           <a class="correos"> Correos Predeterminados.</a>
                        </div>
                    </div>
                </p>
                    <?php }?>
                    <br/>
                <p>Ver: <select class="status">
                    <?php foreach ($docs as $k){?>
                        <option value="<?php echo $k['mov']?>"><?php echo $k['mov'].'('.$k['cant'].')'?></option>
                    <?php } ?>
                </select>
                Fecha inicial:&nbsp;&nbsp;<input type="date" class="ini" value="<?php echo date('d/m/Y')?>" > Fecha Final:&nbsp;&nbsp;<input type="date" class="fin" value="<?php echo date('d/m/Y')?>" >&nbsp;&nbsp;<button class="btn-sm btn-info filtro">Ir</button>
            </p>
            </div>
            <br/>
</div>

<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script type="text/javascript">

    //var pred = ''//




    $(".filtro").click(function(){
        var ini = $(".ini").val()
        var fin = $(".fin").val()
        var sta = $(".status").val()
        window.open("index.wms.php?action=wms_menu&opc=o:"+ini+":"+fin+":"+sta, "_self")
    })

   

        

</script>