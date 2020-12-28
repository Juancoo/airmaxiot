<?php 
session_start();
$logged = $_SESSION['logged'];

if(!$logged){
  echo '<meta http-equiv="refresh" content="0; url=login.php">';
  die();
}
$user_email = $_SESSION['user_email'];

//momento de conectarnos a db
$conn = mysqli_connect("localhost","admin_bdcontrol","122112","admin_bdcontrol");

if ($conn==false){
  echo "Hubo un problema al conectarse a María DB";
  die();
}
if( isset($_POST['card']) && isset($_POST['card'])) {

    $card = strip_tags($_POST['card']);
    $buscard = $conn->query("SELECT * FROM `cards` WHERE idcard = '$card'");
    $compcard = $buscard->fetch_all(MYSQLI_ASSOC);
    //cuento cuantos elementos tiene $tabla,
    $countc = count($compcard);

    if ($countc != 1){
    $conn->query("INSERT INTO `cards` (`idcard`) VALUES ('".$card."');");
    }
  }

$result = $conn->query("SELECT * FROM `cards`");
$data = $result->fetch_all(MYSQLI_ASSOC);
?>
<?php require_once "vistas/parte_superior.php"?>

<!--INICIO del cont principal-->
<div class="container">
 <h1>Tarjetas</h1>
 
    <div class="container">
    <form role="form" method="post" target="">
        <div class="form-group">
            
                <label for="exampleInputEmail1">Tarjeta</label></br>
                <input id="card" name="card" type="text" class="form-control" placeholder="Deslice la tarjeta.." required>    
             
        </div> 
        <button id="btnNuevoCard" type="submit" class="btn btn-success" data-toggle="modal">Registrar</button>    
    </form>  
    </div>    
  <br> 
  <div class="container">
        <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">        
                        <table id="tablaCards" class="table table-striped table-bordered table-condensed" style="width:100%">
                        <thead class="text-center">
                            <tr>
                                <th>Código Tarjeta</th>
                                <th>Asignacion</th> 
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php                            
                            foreach($data as $dat) {                                                        
                            ?>
                            <tr>
                                <td><?php echo $dat['idcard'] ?></td>
                                <td><?php
                                if($dat['assigned']==1)
                                 echo "true";
                                 if($dat['assigned']==0)
                                 echo "false"; ?></td>
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
      
</div>
<!--FIN del cont principal-->

<?php require_once "vistas/parte_inferior.php"?>
<script type="text/javascript" src="mainc.js"></script>

<script src="https://unpkg.com/mqtt@4.1.0/dist/mqtt.min.js "></script>
<script type="text/javascript">

function update_values(temp1){
    $("#card").val(temp1);
    
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