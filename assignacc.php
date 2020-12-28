<?php 
session_start();
$logged = $_SESSION['logged'];

if(!$logged){
    echo '<meta http-equiv="refresh" content="0; url=login.php">';
  die();
}

$alias="";
$serie="";
$user_email = $_SESSION['user_email'];

//momento de conectarnos a db
$conn = mysqli_connect("localhost","admin_bdcontrol","122112","admin_bdcontrol");

if ($conn==false){
  echo "Hubo un problema al conectarse a María DB";
  die();
}

//$resulta = $conn->query("SELECT * FROM `assign` ");
$resulta = $conn->query("SELECT a.idassign, w.cedula, concat(w.nombre, ' ', w.apellido) as trabajador, d.alias FROM assign a
INNER JOIN workers w ON a.cedula = w.cedula
INNER JOIN devices d ON a.iddevice = d.iddevice");
$dathaa = $resulta->fetch_all(MYSQLI_ASSOC);
  
$resultd = $conn->query("SELECT * FROM `devices`");
$devices = $resultd->fetch_all(MYSQLI_ASSOC);

$resultw = $conn->query("SELECT * FROM `workers`");
$workers = $resultw->fetch_all(MYSQLI_ASSOC);

?>
<?php require_once "vistas/parte_superior.php"?>

<!--INICIO del cont principal-->
<div class="container">
    <h1>Asignar Acceso</h1>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">            
            <button id="btnNuevoAc" type="button" class="btn btn-success" data-toggle="modal">Nuevo</button>    
            </div>    
        </div>    
  </div>    
  <br>
  <div class="container">
        <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">        
                        <table id="tablaAssignc" class="table table-striped table-bordered table-condensed" style="width:100%">
                        <thead class="text-center">
                            <tr>
                                <th>ID</th>
                                <th>Cédula</th>
                                <th>Tabajador</th>
                                <th>Dispostivo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php                            
                            foreach($dathaa as $dat) {                                                        
                            ?>
                            <tr>
                                <td><?php echo $dat['idassign'] ?></td>
                                <td><?php echo $dat['cedula'] ?></td>
                                <td><?php echo $dat['trabajador'] ?></td>
                                <td><?php echo $dat['alias'] ?></td>
                                <td></td>
                            </tr>
                            <?php
                                }
                            ?>                             
                        </tbody>        
                       </table>                    
                    </div>
                </div>
        </div>  
    </div>    

    <!--Modal para CRUD-->
<div class="modal fade" id="modalCRUD" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>
        <form id="formAssignc">    
            <div class="modal-body">
            <div class="form-group">
                <label for="cedula" class="col-form-label">Cedula:</label>
                <select id="id_to_workers" name="id_to_workers" class="form-control select2" ui-jp="select2" ui-options="{theme: 'bootstrap'}">
                  <?php foreach ($workers as $wor ) { ?>
                    <option value="<?php echo  $wor['cedula']?>"><?php echo $wor['cedula'] ?></option>
                  <?php } ?>
                </select>
                </div>
                <div class="form-group">
                <label for="iddevice" class="col-form-label">Dispositivo:</label>
                <select id="id_to_device" name="id_to_device" class="form-control select2" ui-jp="select2" ui-options="{theme: 'bootstrap'}">
                  <?php foreach ($devices as $dev ) { ?>
                    <option value="<?php echo  $dev['iddevice']?>"><?php echo $dev['alias'] ?></option>
                  <?php } ?>
                </select>
                </div>    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                <button type="submit" id="btnGuardar" class="btn btn-dark">Guardar</button>
            </div>
        </form>    
        </div>
    </div>
</div>      


</div>
<!--FIN del cont principal-->

<?php require_once "vistas/parte_inferior.php"?>
<script type="text/javascript" src="mainac.js"></script>
 </body> 
</html>