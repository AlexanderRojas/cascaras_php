<?php
require_once "cascarasDB.php";  

class CascarasAPI {    
    public function API(){
        header('Content-Type: application/JSON');
    	$method = $_SERVER['REQUEST_METHOD'];
        
        switch ($method) {

            case 'OPTIONS':
                //echo "Con OPTIONS se Informa sobre el recurso"; 
                header('Allow: OPTIONS, HEAD, GET, POST, PUT, DELETE.');
                header('Cache-Control: max-age=120');
                header('eTag: 654321validar');
                header('X-PINGOTHER: Acá puede ir cualquier info');
                $this->response($code=200, $status="0k", $message="ver la codumentación. Ahí está todo.");
                break;
            case 'HEAD':
               header('X-LINK-0K: Este enlace está 0K. Listo para usar.');
               //echo "con HEAD enviamos revisión de links"; 
                //$this->optionsGasto();
                break;
            case 'GET':
                //echo "consulta recurso y colección";
                $this->getCascaras();
                break;     
            case 'POST':
                //echo "inserta";
                $this->saveCascara();
                break;                
            case 'PUT':
                //echo "actualiza";
               $this->updateCascara();
                break;
            case 'DELETE':
                //echo "elimina";
                $this->deleteCascara();
                break;
            default://metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }

    function response($code=200, $status="estado defecto", $message="Mensaje defecto") {
	    http_response_code($code);
        if( !empty($status) && !empty($message) ){
	        $response = array("status" => $status ,"message"=>$message);  
	        echo json_encode($response);    
	    }            
    }
    
    private function getCascaras(){
        if($_GET['action']=='cascaras'){         
            $db = new CascarasDB();
            if(isset($_GET['id'])){//muestra 1 solo registro si es que existiera ID                 
                $response = $db->selectCascaraId($_GET['id']);                
                if($response){echo json_encode($response);
                }else{$this->response(404, "error", "El recurso No existe");}       
                //var_dump($response); 
            }else { //muestra todos los registros                   
               $response = $db->listaCascaras();
               echo json_encode($response);
                   //var_dump($response);  
            }
        }
        
    } 
    
    function saveCascara(){
        if($_GET['action']=='cascaras'){   
            //Decodifica un string de JSON
            $obj = json_decode( file_get_contents('php://input') );   
            $objArr = (array)$obj;
            if (empty($objArr)){
                $this->response(422,"error","Nothing to add. Check json");                           
            }else if(isset($obj->preg)){
                $cascara = new CascarasDB();     
                $cascara->insertarCascara($obj->preg, $obj->resp1, $obj->resp2, $obj->resp3, $obj->resp4);
                $this->response(200,"success","new record added");                             
            }else{
                $this->response(422,"error","The property is not defined");
            }
        } else{$this->response(400);
        }  
    }

    function updateCascara() {
        if( isset($_GET['action']) && isset($_GET['id']) ){
            if($_GET['action']=='cascaras'){
                $obj = json_decode( file_get_contents('php://input') );   
                $objArr = (array)$obj;
                if (empty($objArr)){                        
                    $this->response(422,"error","Nothing to add. Check json");                        
                }else if(isset($obj->preg)){
                    $db = new CascarasDB();
                    $db->modificarCascara($_GET['id'], $obj->preg, $obj->resp1, $obj->resp2, $obj->resp3, $obj->resp4);
                    $this->response(200,"success","Record updated");                             
                }else{
                    $this->response(422,"error","The property is not defined");                        
                }     
                exit;
           }
        }
        $this->response(400);
    }

    function deleteCascara(){
        if( isset($_GET['action']) && isset($_GET['id']) ){
            if($_GET['action']=='cascaras'){                   
                $db = new CascarasDB();
                $db->borrarCascara($_GET['id']);
                $this->response(204);                   
                exit;
            }
        }
        $this->response(400);
    }





}