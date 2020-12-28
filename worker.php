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

$resultw = $conn->query("SELECT * FROM `workers` ");
$dathaw = $resultw->fetch_all(MYSQLI_ASSOC);
  

?>
<?php require_once "vistas/parte_superior.php"?>

<!--INICIO del cont principal-->
<div class="container">
    <h1>Trabajadores</h1>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">            
            <button id="btnNuevoW" type="button" class="btn btn-success" data-toggle="modal">Nuevo</button>    
            </div>    
        </div>    
  </div>    
  <br>
  <div class="container">
        <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">        
                        <table id="tablaWorkers" class="table table-striped table-bordered table-condensed" style="width:100%">
                        <thead class="text-center">
                            <tr>
                                <th>Cédula</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Dirección</th>
                                <th>Teléfono</th>
                                <th>Tarjeta</th> 
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php                            
                            foreach($dathaw as $dat) {                                                        
                            ?>
                            <tr>
                                <td><?php echo $dat['cedula'] ?></td>
                                <td><?php echo $dat['nombre'] ?></td>
                                <td><?php echo $dat['apellido'] ?></td>
                                <td><?php echo $dat['direccion'] ?></td>
                                <td><?php echo $dat['telefono'] ?></td>
                                <td><?php echo $dat['codcard'] ?></td>
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
        <form id="formWorkers">    
            <div class="modal-body">
            <div class="form-group">
                <label for="cedula" class="col-form-label">Cedula:</label>
                <input type="text" class="form-control" id="cedula" maxlength="10">
                <label for="" id="lbcedula" style="display:none"></label>
                </div>
                <div class="form-group">
                <label for="nombre" class="col-form-label">Nombre:</label>
                <input type="text" class="form-control" id="nombre">
                <label for="" id="lbnombre" style="display:none"></label>
                </div>
                <div class="form-group">
                <label for="apellido" class="col-form-label">Apellido:</label>
                <input type="text" class="form-control" id="apellido">
                <label for="" id="lbapellido" style="display:none"></label>
                </div>
                <div class="form-group">
                <label for="direccion" class="col-form-label">Dirección:</label>
                <input type="text" class="form-control" id="direccion">
                <label for="" id="lbdireccion" style="display:none"></label>
                </div>
                <div class="form-group">
                <label for="telefono" class="col-form-label">Teléfono:</label>
                <input type="text" class="form-control" id="telefono">
                <label for="" id="lbtelefono" style="display:none"></label>
                </div>
                <div class="form-group">
                <label for="codcard" class="col-form-label">Tarjeta:</label>
                <input type="text" class="form-control" id="codcard" name="codcard" placeholder="Deslice la tarjeta..">
                <label for="" id="lbcodcard" style="display:none"></label>
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
<script type="text/javascript" src="mainw.js?v2"></script>

<script src="https://unpkg.com/mqtt@4.1.0/dist/mqtt.min.js "></script>
<script type="text/javascript">

function update_values(temp1){
    $("#codcard").val(temp1);
    
  }
function process_msg(topic, message){
  // ej: "10,11,12"
  if (topic == "123456789/access_query"){
    var msg = message.toString();
    var temp1 = msg;
    
    update_values(temp1);
   }
  }
  //conexion 
  const options = {
    connectTimeout: 4000,
    clientId: 'juan@ga',
    username: 'web_client',
    password: 'client',
    keepalive: 60,
    clean: true,
  }

  //webSocket conexion url
  const WebSocket_URL = 'wss://controlsiot.online:8094/mqtt'

const client = mqtt.connect(WebSocket_URL, options)

client.on('connect',() => {
  console.log('Mqtt conectado por WS! Exitoso!')

  client.subscribe('123456789/access_query', { qos: 0 }, (error) => {
    if (!error) {
      console.log('Suscripcion exitosa!')
    }else{
      console.log('Suscripcion fallida')
    }
  })
})

// handle message event
client.on('message', (topic, message) => {
      console.log('Mensaje recibido bajo topico: ', topic, ' -> ', message.toString())
      process_msg(topic, message);
    })

    client.on('reconnect', (error) => {
      console.log('Error al reconectar', error)
    })

    client.on('error', (error) => {
      console.log('Error de conexion', error)
    })

</script>


    
 </body> 
</html>