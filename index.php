<?php
$path=$_GET['action'];

switch ($path){
    
case 'cascaras_php'://Directamente a la API 
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
