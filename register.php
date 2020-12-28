<?php
session_start();
$logged = $_SESSION['logged'];

if(!$logged){
  echo '<meta http-equiv="refresh" content="0; url=login.php">';
  die();
}

//momento de conectarnos a db
$conn = mysqli_connect("localhost","admin_bdcontrol","122112","admin_bdcontrol");

if ($conn==false){
  echo "Hubo un problema al conectarse a María DB";
  die();
}

//declaramos variables vacias servirán también para repoblar el formulario
$email = "";
$password = "";
$password_r = "";
$msg = "";

if( isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password_r'])) {

  $email = strip_tags($_POST['email']);
  $password = strip_tags($_POST['password']);
  $password_r = strip_tags($_POST['password_r']);


  if ($password==$password_r){

    //aquí como todo estuvo OK, resta controlar que no exista previamente el mail ingresado en la tabla users.
    $result = $conn->query("SELECT * FROM `users` WHERE `email` = '".$email."' ");
    $users = $result->fetch_all(MYSQLI_ASSOC);

    //cuento cuantos elementos tiene $tabla,
    $count = count($users);

    //solo si no hay un usuario con mismo mail, procedemos a insertar fila con nuevo usuario
    if ($count == 0){
      $password = sha1($password); //encriptar clave con sha1
      $conn->query("INSERT INTO `users` (`email`, `pass`) VALUES ('".$email."', '".$password."');");
      $msg.="Usuario creado correctamente, ingrese haciendo  <a href='login.php'>clic aquí</a> <br>";
    }else{
      $msg.="El email ingresado ya existe <br>";
    }

  }else{
    $msg = "Las claves no coinciden";
  }

}else{
  $msg = "Complete el formulario";
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
          
          <div class="card-header"><h3 class="text-center font-weight-light my-4">Registrar</h3></div>
          <div class="card-body">
            <!-- Nested Row within Card Body -->
                
                  <form target="" method="post" name="form">
                    <div class="form-group">
                      <input name="email" type="email" class="form-control py-4" value="<?php echo $email ?>" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address..." required>
                    </div>
                    <div class="form-group">
                      <input name="password" type="password" class="form-control py-4" id="exampleInputPassword" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                      <input name="password_r" type="password" class="form-control py-4" id="exampleInputPassword_r" placeholder="Repeat Password" required>
                    </div>
                    <div class="form-group d-flex justify-content-center">
                    <button type="submit" class="btn btn-success">Registrar</button>
                    </div>
                  </form>
                  <div style="color:red" class="">
                   <?php echo $msg ?>
                  </div>
                  
               
          </div>
        </div>
        <br><br>
        <div class="p-3 mb-2 bg-light text-dark text-center">
                <a class="small" href="login.php">¿Ya tienes una cuenta? ¡Iniciar sesión!</a>
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
