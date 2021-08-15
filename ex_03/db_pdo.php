<?php

const ERROR_LOG_FILE = '/db_pdo.php';


function connect_db($host,$username,$password,$port,$db){

    try{

        $connect = new PDO('mysql:host='.$host.';dbname='.$db.';port='.$port,$username,$password);
        
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $request = "SELECT * FROM movies;";
        
        $result = $connect -> query($request);
        
        $res = $result->fetchAll();
        
        return $res;
        
        }catch(PDOException $e){
        
            echo "PDO ERROR: ".$e->getMessage()." storage in ".ERROR_LOG_FILE.PHP_EOL;
        
        }
        

}





