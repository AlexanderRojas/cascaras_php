<?php
//header('HTTP/1.1 404 Not Found JR'); 
  //Reconocer el Método de Solicitud:
  // $method = $_SERVER['REQUEST_METHOD'];
  //echo $method;

//require_once "cascarasDB.php";   
  //  $cascarasDB = new CascarasDB();
    //$cascarasDB->listaCascaras();
    //$cascarasDB->selectCascaraId(1);
    //$cascarasDB->insertarCascara();
    //$cascarasDB->borrarCascara(9);
    //$cascarasDB->modificarCascara(10, 'Nueva Pregunta', 'Nueva Respuesta1', 'Nueva Respuesta2', 'Nueva Respuesta3', 'Nueva Respuesta4');
//require_once "options.php";
  //  $CascarasOptions = new CascarasOptions();
   // $CascarasOptions->InfoCascaras();

$path=$_GET['action'];
//$path_id=$_GET['id'];

switch ($path){
    
case 'cascaras'://Directamente a la API 
    require_once "cascarasAPI.php";
    $cascarasAPI = new CascarasAPI();
    $cascarasAPI->API();
    //echo "Directamente a la API";
    //$this->optionsGasto();
    break;
case 'doc'://Documentación y Ejemplo de APP
    echo "Documentación y Ejemplo"; 
    break;
default://Ruta No válida
echo 'Ruta No válida';
    break;
}         

/*
require_once "cascarasAPI.php";
$cascarasAPI = new CascarasAPI();
$cascarasAPI->API();
*/

