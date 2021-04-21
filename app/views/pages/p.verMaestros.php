<br />
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Maestros   
                            <a class="btn btn-success" href="index.php?action=nuevo_maestro" class="btn btn-success"> Crear Maestro <i class="fa fa-plus"></i></a>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Sucursales</th>
                                            <th>Cartera Revision</th>
                                            <th>Cartera Cobranza</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th>Editar</th>
                                        </tr>
                                    </thead>                                   
                                  <tbody>
                                        <?php 
                                        foreach ($maestros as $data): 
                                        ?>
                                        <tr class="odd gradeX" >
                                         <!--<tr class="odd gradeX" style='background-color:yellow;' >-->
                                            <td><?php echo $data->NOMBRE;?></td>
                                            <td><?php echo $data->SUCURSALES;?></td>
                                            <td><?php echo $data->CARTERA;?></td>
                                            <td><?php echo $data->CARTERA_REVISION;?></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>
                                            <form action="index.php" method="post">
                                                <input type="hidden" name="idm" value="<?php echo $data->ID?>" >
                                                <button name="editarMaestro" value="enviar" type="submit" class="btn btn-info"> Editar </button>
                                            </td>
                                             
                                        </tr>
                                        </form>
                                        <?php endforeach; ?>
                                 </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                      </div>
            </div>
        </div>
</div>
<br />
