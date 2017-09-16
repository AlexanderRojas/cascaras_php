<?php

class GastoDB {
    
    protected $mysqli;
    const LOCALHOST = '127.0.0.1';
    const USER = 'alexander';
    const PASSWORD = 'alexander';
    const DATABASE = 'gastos';

    
    /**
     * Constructor de clase
     */
    public function __construct() {           
        try{
            //conexión a base de datos
            $this->mysqli = new mysqli(self::LOCALHOST, self::USER, self::PASSWORD, self::DATABASE);
        }catch (mysqli_sql_exception $e){
            //Si no se puede realizar la conexión. OJO: En la prueba no me funcionó.
            http_response_code(500);
            exit;
        }     
    } 


    public function prueba(){
    	$resultado 	= $this->mysqli->query("SELECT * FROM gastos");
		$gastos 	= $resultado->fetch_assoc();
		 return $gastos;    
        //var_dump($fila);
	    }
    
    /**
     * obtiene un solo registro dado su ID
     * @param int $id identificador unico de registro
     * @return array con los registros obtenidos de la base de datos
     */
    public function selectGasto($id=0){      
        $stmt = $this->mysqli->prepare("SELECT * FROM gastos WHERE id=? ; ");
        $stmt->bind_param('i', $id); 
        $stmt->execute();
        $result = $stmt->get_result();        
        $gastos = $result->fetch_all(MYSQLI_ASSOC); 
        $stmt->close();
        return $gastos;              
    }
    
    /**
     * obtiene todos los registros de la tabla "gastos"
     * @return Array array con los registros obtenidos de la base de datos
     */
    public function selectGastos(){        
        $result = $this->mysqli->query("SELECT * FROM gastos; "); 
                 
        $gastos = $result->fetch_all(MYSQLI_ASSOC);          
        $result->close();
        return $gastos; 
     
    }
    
    /**
     * añade un nuevo registro en la tabla gastos
     * @param String $detalle
     * @param int $valor
     * @param Date $fecha
     * @return bool TRUE|FALSE 
     */
     public function insert($detalle='', $valor=0, $fecha="2017-2-3"){
        $stmt = $this->mysqli->prepare("INSERT INTO gastos(id, detalle, valor, fecha) VALUES (NULL, ?, ?, ?); ");
        $stmt->bind_param('sis', $detalle, $valor, $fecha);
        $r = $stmt->execute(); 
        $stmt->close();
        return $r;        
    }



    
    /**
     * elimina un registro dado el ID
     * @param int $id Identificador unico de registro
     * @return Bool TRUE|FALSE
     */
    public function delete($id=0) {
        $stmt = $this->mysqli->prepare("DELETE FROM gastos WHERE id = ? ; ");
        $stmt->bind_param('s', $id);
        $r = $stmt->execute(); 
        $stmt->close();
        return $r;
    }
    
    /**
     * Actualiza registro dado su ID
     * @param int $id Description
     */
    public function update($id, $newDetalle, $newValor, $newDate) {
        if($this->checkID($id)){
            $stmt = $this->mysqli->prepare("UPDATE gastos SET detalle=?, valor=?, fecha=? WHERE id = ? ; ");
            $stmt->bind_param('sisi', $newDetalle, $newValor, $newDate, $id);
            $r = $stmt->execute(); 
            $stmt->close();
            return $r;    
        }
        return false;
    }
    
    /**
     * verifica si un ID existe
     * @param int $id Identificador unico de registro
     * @return Bool TRUE|FALSE
     */
    public function checkID($id){
        $stmt = $this->mysqli->prepare("SELECT * FROM gastos WHERE ID=?");
        $stmt->bind_param("s", $id);
        if($stmt->execute()){
            $stmt->store_result();    
            if ($stmt->num_rows == 1){                
                return true;
            }
        }        
        return false;
    }
    
}
