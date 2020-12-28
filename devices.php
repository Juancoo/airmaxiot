<?php 
session_start();
$logged = $_SESSION['logged'];

if(!$logged){
    echo '<meta http-equiv="refresh" content="0; url=login.php">';
  die();
}
$user_id = $_SESSION['user_id'];
$user_email = $_SESSION['user_email'];

//momento de conectarnos a db
$conn = mysqli_connect("localhost","admin_bdcontrol","122112","admin_bdcontrol");

if ($conn==false){
  echo "Hubo un problema al conectarse a María DB";
  die();
}
$result = $conn->query("SELECT iddevice, alias, iduser FROM `devices`");
$data = $result->fetch_all(MYSQLI_ASSOC);
?>
<?php require_once "vistas/parte_superior.php"?>

<!--INICIO del cont principal-->
<div class="container">
    <h1>Dispositivos</h1>
    
  <div class="container">
        <div class="row">
            <div class="col-lg-12">            
            <button id="btnNuevo" type="button" class="btn btn-success" data-toggle="modal">Nuevo</button>    
            </div>    
        </div>    
  </div>    
  <br>  
<div class="container">
        <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">        
                        <table id="tablaDevices" class="table table-striped table-bordered table-condensed" style="width:100%">
                        <thead class="text-center">
                            <tr>
                                <th>Serie</th>
                                <th>Alias</th> 
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php                            
                            foreach($data as $dat) {                                                        
                            ?>
                            <tr>
                                <td><?php echo $dat['iddevice'] ?></td>
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
        <form id="formDevices">    
            <div class="modal-body">
                <div class="form-group">
                <label for="serie" class="col-form-label">Serie:</label>
                <input type="text" class="form-control" id="serie" required>
                </div>
                <div class="form-group">
                <label for="alias" class="col-form-label">Alias:</label>
                <input type="text" class="form-control" id="alias">
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
<script type="text/javascript" src="main2.js"></script>
    
 </body> 
</html>