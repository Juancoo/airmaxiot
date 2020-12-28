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
$iddevice = (isset($_POST['iddevice'])) ? $_POST['iddevice'] : '';
$idassign = (isset($_POST['idassign'])) ? $_POST['idassign'] : '';
$opcion = (isset($_POST['opcion'])) ? $_POST['opcion'] : '';

switch($opcion){
  case 1: //agregar
    $buswork = $conn->query("SELECT * FROM `assign` WHERE cedula = '$cedula' AND iddevice = '$iddevice'");
    $compwork = $buswork->fetch_all(MYSQLI_ASSOC);
    //cuento cuantos elementos tiene $tabla,
    $counwork = count($compwork);

    if ($counwork != 1){
    $conn->query("INSERT INTO assign (cedula, iddevice) VALUES('$cedula','$iddevice') "); 

    //$result = $conn->query("SELECT idassign, cedula, iddevice FROM assign ORDER BY idassign DESC LIMIT 1");
    $result = $conn->query("SELECT a.idassign, w.cedula, concat(w.nombre, ' ', w.apellido) as trabajador,d.iddevice, d.alias FROM assign a
    INNER JOIN workers w ON a.cedula = w.cedula
    INNER JOIN devices d ON a.iddevice = d.iddevice
    ORDER BY a.idassign DESC LIMIT 1");
    $data = $result->fetch_all(MYSQLI_ASSOC);
    }
  break;
  case 2: //borrar
    $conn->query("DELETE FROM assign WHERE idassign='$idassign' ");
    
    $result = $conn->query("SELECT * FROM assign WHERE idassign='$idassign'");
    $data = $result->fetch_all(MYSQLI_ASSOC);                       
break;
}
    print json_encode($data, JSON_UNESCAPED_UNICODE); //enviar el array final en formato json a JS
    
