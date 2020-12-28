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

$idcard = (isset($_POST['idcard'])) ? $_POST['idcard'] : '';
$opcion = (isset($_POST['opcion'])) ? $_POST['opcion'] : '';

switch($opcion){
    case 1: //borrar
        $conn->query("DELETE FROM cards WHERE idcard='$idcard' ");
        
        $result = $conn->query("SELECT * FROM cards WHERE idcard='$idcard'");
        $data = $result->fetch_all(MYSQLI_ASSOC);                       
    break;
}

print json_encode($data, JSON_UNESCAPED_UNICODE); //enviar el array final en formato json a JS