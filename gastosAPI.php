<?php

require_once "gastosDB.php";  

class GastosAPI {    
    public function API(){
            header('Content-Type: application/JSON');
    	    $method = $_SERVER['REQUEST_METHOD'];
        
        switch ($method) {

            case 'OPTIONS'://Informa sobre el recurso 
               $this->optionsGasto();
                break;
            case 'OPTIONS'://Informa sobre el recurso 
               $this->optionsGasto();
                break;
            case 'GET'://consulta
                $this->getGastos();
                break;     
            case 'POST'://inserta
                $this->saveGasto();
                break;                
            case 'PUT'://actualiza
               $this->updateGasto();
                break;
            case 'DELETE'://elimina
                $this->deleteGasto();
                break;
            default://metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }

	/**
	 * Respuesta al cliente
	 * @param int $code Codigo de respuesta HTTP
	 * @param String $status indica el estado de la respuesta puede ser "success" o "error"
	 * @param String $message Descripcion de lo ocurrido
	 */
	 function response($code=200, $status="", $message="") {
	    http_response_code($code);
	    if( !empty($status) && !empty($message) ){
	        $response = array("status" => $status ,"message"=>$message);  
	        echo json_encode($response,JSON_PRETTY_PRINT);    
	    }            
	 }

    /**
     * función que da Información de la colección del recursos:
     */
     private function optionsGasto(){
         if($_GET['action']=='gastos'){         
                               
                $response = ["Allow" => "GET,HEAD,POST,OPTIONS,TRACE",
                             "server" => $_SERVER,
                ];
                
                            
                echo json_encode($response, JSON_PRETTY_PRINT);
                
                
                    //var_dump($response);  
            

            }else{

                $this->response(400);
         }       
     }       


    /**
     * función que segun el valor de "action" e "id":
     *  - mostrará una array con todos los registros de gastos
     *  - mostrará un solo registro 
     *  - mostrará un array vacio
     */
     private function getGastos(){
         if($_GET['action']=='gastos'){         
             $db = new GastoDB();
             if(isset($_GET['id'])){//muestra 1 solo registro si es que existiera ID                 
                 $response = $db->selectGasto($_GET['id']);                
                 echo json_encode($response, JSON_PRETTY_PRINT);
                 //var_dump($response); 
             }else{ //muestra todos los registros                   
                $response = $db->selectGastos();
                            
                echo json_encode($response, JSON_PRETTY_PRINT);
                    //var_dump($response);  
             }
         }else{

                $this->response(400);
         }       
     }       

    /**
    * metodo para guardar un nuevo registro de gasto en la base de datos
    */
     function saveGasto(){
     if($_GET['action']=='gastos'){   
         //Decodifica un string de JSON
         $obj = json_decode( file_get_contents('php://input') );   
         $objArr = (array)$obj;
         if (empty($objArr)){
             $this->response(422,"error","Nothing to add. Check json");                           
         }else if(isset($obj->detalle)){
             $gasto = new GastoDB();     
             $gasto->insert($obj->detalle, $obj->valor, $obj->fecha);
             $this->response(200,"success","new record added");                             
         }else{
             $this->response(422,"error","The property is not defined");
         }
     } else{$this->response(400);
     }  
 }

    /**
     * Actualiza un recurso
     */
    function updateGasto() {
        if( isset($_GET['action']) && isset($_GET['id']) ){
            if($_GET['action']=='gastos'){
                $obj = json_decode( file_get_contents('php://input') );   
                $objArr = (array)$obj;
                if (empty($objArr)){                        
                    $this->response(422,"error","Nothing to add. Check json");                        
                }else if(isset($obj->detalle)){
                    $db = new GastoDB();
                    $db->update($_GET['id'], $obj->detalle, $obj->valor, $obj->fecha);
                    $this->response(200,"success","Record updated");                             
                }else{
                    $this->response(422,"error","The property is not defined");                        
                }     
                exit;
           }
        }
        $this->response(400);
    }


    /**
     * elimina gasto
     */
    function deleteGasto(){
        if( isset($_GET['action']) && isset($_GET['id']) ){
            if($_GET['action']=='gastos'){                   
                $db = new GastoDB();
                $db->delete($_GET['id']);
                $this->response(204);                   
                exit;
            }
        }
        $this->response(400);
    }

    
}//end class


?>