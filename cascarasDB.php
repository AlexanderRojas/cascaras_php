<?php

class CascarasDB {
    
    protected $mysqli;
    const LOCALHOST = '127.0.0.1';
    const USER = 'root';
    const PASSWORD = '';
    const DATABASE = 'cascaras';
    /**
     * Constructor de clase
     */
    public function __construct() {
        mysqli_report(MYSQLI_REPORT_STRICT);           
        try{
            //conexión a base de datos
            $this->mysqli = new mysqli(self::LOCALHOST, self::USER, self::PASSWORD, self::DATABASE);
            mysqli_set_charset($this->mysqli,"utf8mb4");
            //echo 'connect success';
        }catch (Exception $e){
            //Si no se puede realizar la conexión. OJO: En la prueba no me funcionó.
            echo '<br>ERROR 500 - FALLÓ CONEXIÓN A BASE DE DATOS:<br>'.$e->getMessage();
            http_response_code(500);
            exit;
        }     
    } 

    public function listaCascaras(){        
        $result = $this->mysqli->query("SELECT * FROM cascaras;"); 
        $cascaras = $result->fetch_all(MYSQLI_ASSOC);          
        $result->close();
        //echo "Entra a Lista Cáscaras";
        //var_dump($cascaras); 
        return $cascaras;
    }

    public function selectCascaraId($id=0){      
        $stmt = $this->mysqli->prepare("SELECT * FROM cascaras WHERE id=? ; ");
        $stmt->bind_param('i', $id); 
        $stmt->execute();
        $result = $stmt->get_result();        
        $cascaras = $result->fetch_all(MYSQLI_ASSOC); 
        $stmt->close();
        //var_dump($cascaras); 
        return $cascaras;              
    }

    public function insertarCascara(
                $preg='No hay pregunta', 
                $resp1='No hay respuesta 1',
                $resp2='No hay respuesta 2',
                $resp3='No hay respuesta 3',
                $resp4='No hay respuesta 4'){
                
        $stmt = $this->mysqli->prepare("INSERT INTO cascaras (id, preg, resp1, resp2, resp3, resp4) 
        VALUES (NULL, ?, ?, ?, ?, ?);");
        $stmt->bind_param('sssss', $preg, $resp1, $resp2, $resp3, $resp4);
        $r = $stmt->execute(); 
        $stmt->close();
        //echo $r;
        return $r;
    }
    
    public function borrarCascara($id=8) {
        $stmt = $this->mysqli->prepare("DELETE FROM cascaras WHERE id = ? ; ");
        $stmt->bind_param('s', $id);
        $r = $stmt->execute(); 
        $stmt->close();
        return $r;
    }

    public function checkID($id){
        $stmt = $this->mysqli->prepare("SELECT * FROM cascaras WHERE ID=?");
        $stmt->bind_param("s", $id);
        if($stmt->execute()){
            $stmt->store_result();    
            if ($stmt->num_rows == 1){                
                return true;
            }
        }        
        return false;
    }

    public function modificarCascara($id, $newpreg, $newresp1, $newresp2, $newresp3, $newresp4) {
        if($this->checkID($id)){
            $stmt = $this->mysqli->prepare("UPDATE cascaras SET preg=?, resp1=?, resp2=?, resp3=?, resp4=? WHERE id = ? ; ");
            $stmt->bind_param('sssssi', $newpreg, $newresp1, $newresp2, $newresp3, $newresp4, $id);
            $r = $stmt->execute(); 
            $stmt->close();
            return $r;    
        }
        return false;
    }    


    
}        
