<?php
session_start();
$logged = $_SESSION['logged'];

if(!$logged){
  echo "Ingreso no autorizado";
  die();
}

//momento de conectarnos a db
$conn = mysqli_connect("localhost","admin_bdcontrol","122112","admin_bdcontrol");

if ($conn==false){
  echo "Hubo un problema al conectarse a María DB";
  die();
}

// Recepción de los datos enviados mediante POST desde el JS   
$cedula = (isset($_POST['cedula'])) ? $_POST['cedula'] : '';
$nombre = (isset($_POST['nombre'])) ? $_POST['nombre'] : '';
$apellido = (isset($_POST['apellido'])) ? $_POST['apellido'] : '';
$direccion = (isset($_POST['direccion'])) ? $_POST['direccion'] : '';
$telefono = (isset($_POST['telefono'])) ? $_POST['telefono'] : '';
$codcard = (isset($_POST['codcard'])) ? $_POST['codcard'] : '';
$opcion = (isset($_POST['opcion'])) ? $_POST['opcion'] : '';


switch($opcion){
  case 1: //agregar
    $buswork = $conn->query("SELECT * FROM `workers` WHERE cedula = '$cedula'");
    $compwork = $buswork->fetch_all(MYSQLI_ASSOC);
    //cuento cuantos elementos tiene $tabla,
    $counwork = count($compwork);

    if ($counwork != 1){
    $conn->query("INSERT INTO workers (cedula, nombre, apellido, direccion, telefono, codcard) VALUES('$cedula','$nombre','$apellido','$direccion','$telefono', '$codcard') ");
    
    $result = $conn->query("SELECT * FROM `workers` WHERE cedula = '$cedula'");
    $data = $result->fetch_all(MYSQLI_ASSOC);
    }
  break;
  case 2: //editar
    $conn->query("UPDATE workers SET cedula='$cedula', nombre='$nombre', apellido='$apellido', direccion='$direccion', telefono='$telefono', codcard='$codcard' WHERE cedula='$cedula' ");       
                
    $result = $conn->query("SELECT * FROM workers WHERE cedula='$cedula'");
    $data = $result->fetch_all(MYSQLI_ASSOC);
  break;
  case 3: //borrar
      $conn->query("DELETE FROM workers WHERE cedula='$cedula' ");
      
      $result = $conn->query("SELECT * FROM workers WHERE cedula='$cedula'");
      $data = $result->fetch_all(MYSQLI_ASSOC);                       
  break;
}



print json_encode($data, JSON_UNESCAPED_UNICODE); //enviar el array final en formato json a JS

