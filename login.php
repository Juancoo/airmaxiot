<?php
session_start();
$_SESSION['logged'] = false;

$msg="";
$email="";

if(isset($_POST['email']) && isset($_POST['password'])) {

  if ($_POST['email']==""){
    $msg.="Debe ingresar un email <br>";
  }else if ($_POST['password']=="") {
    $msg.="Debe ingresar la clave <br>";
  }else {
    $email = strip_tags($_POST['email']);
    $password= sha1(strip_tags($_POST['password']));

    //momento de conectarnos a db
    $conn = mysqli_connect("localhost","admin_bdcontrol","122112","admin_bdcontrol");


    if ($conn==false){
      echo "Hubo un problema al conectarse a María DB";
      die();
    }

    $result = $conn->query("SELECT * FROM `users` WHERE `email` = '".$email."' AND  `pass` = '".$password."' ");
    $users = $result->fetch_all(MYSQLI_ASSOC);


    //cuento cuantos elementos tiene $tabla,
    $count = count($users);

    if ($count == 1){

      //cargo datos del usuario en variables de sesión
      $_SESSION['user_id'] = $users[0]['iduser'];
      $_SESSION['user_email'] = $users[0]['email'];

      $msg .= "Exito!!!";
      $_SESSION['logged'] = true;

      //RECUPERAMOS LOS DISPOSITIVOS DE ESTE USUARIO
      //$result = $conn->query("SELECT * FROM `devices` WHERE `devices_user_id` = '".$users[0]['users_id']."'");
      //$devices = $result->fetch_all(MYSQLI_ASSOC);

      //guardamos los dispositivos en una variable de sesión
      //$_SESSION['devices'] = $devices;

      //echo "<pre>";
      //print_r($devices);
      //die();

      echo '<meta http-equiv="refresh" content="2; url=dashboard.php">';
    }else{
      $msg .= "Acceso denegado!!!";
      $_SESSION['logged'] = false;
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Control IoT</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-lg-6">

        <div class="card shadow-lg border-0 rounded-lg mt-5">
          
          <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
          <div class="card-body">
            <!-- Nested Row within Card Body -->
                
                  <form target="" method="post" name="form">
                    <div class="form-group">
                      <input name="email" type="email" class="form-control py-4" value="<?php echo $email ?>" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address..." required>
                    </div>
                    <div class="form-group">
                      <input name="password" type="password" class="form-control py-4" id="exampleInputPassword" placeholder="Password" required>
                    </div>
                    <div class="form-group d-flex justify-content-center">
                    <button type="submit" class="btn btn-success">Ingresar</button>
                    </div>
                  </form>
                  <div style="color:red" class="">
                   <?php echo $msg ?>
                  </div>
                  
                
              
            
          </div>
        </div>

      </div>

    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
