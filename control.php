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
$result = $conn->query("SELECT t.idtraffic,t.dateacc, w.cedula, concat(w.nombre, ' ', w.apellido) as trabajador, d.alias FROM assign a
INNER JOIN workers w ON a.cedula = w.cedula
INNER JOIN devices d ON a.iddevice = d.iddevice
INNER JOIN traffic t ON a.idassign = t.idassign");
$data = $result->fetch_all(MYSQLI_ASSOC);

?>
<?php require_once "vistas/parte_superior.php"?>

<!--INICIO del cont principal-->
<div class="container">
    <h1>Control de Acceso</h1>

    <div class="container">
        <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">        
                        <table id="tablaControl" class="table table-striped table-bordered table-condensed" style="width:100%">
                        <thead class="text-center">
                            <tr>
                                <th>Id</th>
                                <th>Fecha</th>
                                <th>Cédula</th> 
                                <th>Trabajador</th>
                                <th>Dispositivo</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                        <?php                            
                            foreach($data as $dat) {                                                        
                            ?>
                            <tr>
                                <td><?php echo $dat['idtraffic'] ?></td>
                                <td><?php echo $dat['dateacc'] ?></td>
                                <td><?php echo $dat['cedula'] ?></td>
                                <td><?php echo $dat['trabajador'] ?></td>
                                <td><?php echo $dat['alias'] ?></td>                                
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
</div>
<!--FIN del cont principal-->

<?php require_once "vistas/parte_inferior.php"?>
<script type="text/javascript" src="maint.js"></script>
    
 </body> 
</html>