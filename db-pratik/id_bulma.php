<?php

require_once "config.php";
ini_set('display_errors', 1);
$sql = "SELECT id FROM radcheck WHERE id = :id";
        
if($stmt = $pdo->prepare($sql)){
    // Bind variables to the prepared statement as parameters
    $stmt->bindParam(":id", $param_id, PDO::PARAM_STR);
    
    // Set parameters
    $param_id = "4";
    
    // Attempt to execute the prepared statement
    if($stmt->execute()){
        if($stmt->rowCount() == 1){
            echo "id numarasi var.";
        } else{
            echo "Bu id numarasi yok";
        }
    }
}

?>