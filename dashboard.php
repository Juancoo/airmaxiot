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
$result = $conn->query("SELECT iddevice, alias, iduser FROM `devices`");
$data = $result->fetch_all(MYSQLI_ASSOC);
?>
<?php require_once "vistas/parte_superior.php"?>

<!--INICIO del cont principal-->
<div class="container">
  <h1>Dashboard</h1>
  <br>
  <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Dispositivo (Temperatura)</div>
                      
                        <div class="clear">
                          <h4 class="m-0 text-gray-800"><b id="display_temp1">-- </b><span class="text-sm"> C</span></h4>
                         <small class="text-muted">Temperatura Ambiente</small>
                        </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-thermometer-quarter fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>   
  </div>
  <br><br>
  <div class="row">
  <div class="form-group">
  
    <div class="form-group" style="margin-left:25px">
          <select id="id_to_devices" name="id_to_devices" class="form-control select2" ui-jp="select2" ui-options="{theme: 'bootstrap'}">
                  <?php foreach ($data as $dev ) { ?>
                    <option value="<?php echo  $dev['iddevice']?>"><?php echo $dev['alias'] ?></option>
                  <?php } ?>
          </select>
    </div>
    <button onclick="command('open')" class="btn btn-success" style="margin-left:25px" data-toggle="modal">Abrir</button>
    <button onclick="command('close')" class="btn btn-danger" style="margin-left:25px" data-toggle="modal">Cerrar</button>
    
  </div>
  </div>

    
    
  

</div>
<!--FIN del cont principal-->

<?php require_once "vistas/parte_inferior.php"?>

<script src="https://unpkg.com/mqtt@4.1.0/dist/mqtt.min.js "></script>
<script type="text/javascript">

function update_values(temp1){
    $("#display_temp1").html(temp1);
    
  }
function process_msg(topic, message){
  // ej: "10,11,12"
  if (topic == "123456789/temp"){
    var msg = message.toString();
    var temp1 = msg;
    
    update_values(temp1);
   }
  }

  function command(action){
    var device_id = $("#id_to_devices").val();
    if(action == "open"){
      client.publish(device_id + '/command', 'open', (error) => {
      console.log(error || 'Mensaje enviado!!!')
    })
    }else{
      client.publish(device_id + '/command', 'close', (error) => {
      console.log(error || 'Mensaje enviado!!!')
    })
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

  client.subscribe('123456789/temp', { qos: 0 }, (error) => {
    if (!error) {
      console.log('Suscripcion exitosa!')
    }else{
      console.log('Suscripcion fallida')
    }
  })

  // publich(topic, payload, options/callback)
  //client.publish('fabrica', 'Esto es un exito', (error) => {
  //console.log(error || 'Mensaje enviado!!!')
  //})
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