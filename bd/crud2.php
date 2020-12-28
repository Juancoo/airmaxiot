<?php
session_start();
$logged = $_SESSION['logged'];

if(!$logged){
  echo "Ingreso no autorizado";
  die();
}

$iduser = $_SESSION['user_id'];
//momento de conectarnos a db
$conn = mysqli_connect("localhost","admin_bdcontrol","122112","admin_bdcontrol");

if ($conn==false){
  echo "Hubo un problema al conectarse a María DB";
  die();
}

// Recepción de los datos enviados mediante POST desde el JS   

//$serie = (isset($_POST['serie'])) ? $_POST['serie'] : '';
$alias = (isset($_POST['alias'])) ? $_POST['alias'] : '';
$iddevice = (isset($_POST['iddevice'])) ? $_POST['iddevice'] : '';
$opcion = (isset($_POST['opcion'])) ? $_POST['opcion'] : '';

switch($opcion){
  case 1: //agregar
    $busdevice = $conn->query("SELECT * FROM `devices` WHERE iddevice = '$iddevice'");
    $compdevice = $busdevice->fetch_all(MYSQLI_ASSOC);
    //cuento cuantos elementos tiene $tabla,
    $countd = count($compdevice);

    if ($countd != 1){
      $conn->query("INSERT INTO devices (iddevice, alias, iduser) VALUES('$iddevice','$alias','$iduser') ");
        
    $result = $conn->query("SELECT iddevice, alias, iduser FROM devices ORDER BY iddevice DESC LIMIT 1");
    $data = $result->fetch_all(MYSQLI_ASSOC);
    }
  break;
  case 2: //editar
    $conn->query("UPDATE devices SET  alias='$alias' WHERE iddevice='$iddevice' ");       
                
    $result = $conn->query("SELECT iddevice, alias, iduser FROM devices WHERE iddevice='$iddevice'");
    $data = $result->fetch_all(MYSQLI_ASSOC);
    break;
  case 3: //borrar
    $conn->query("DELETE FROM devices WHERE iddevice='$iddevice' ");
    
    $result = $conn->query("SELECT iddevice, alias, iduser FROM devices WHERE iddevice='$iddevice'");
    $data = $result->fetch_all(MYSQLI_ASSOC);                       
    break;
}



print json_encode($data, JSON_UNESCAPED_UNICODE); //enviar el array final en formato json a JS

