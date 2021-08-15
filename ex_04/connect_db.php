<?php

const ERROR_LOG_FILE = 'errors.log';

$data = [];

if(isset($argv)){

    try{
         $totalArgs = count($argv);

            if($totalArgs < 6){

                throw new Exception("Bad params ! Usage: php connect_db host username password port db");

            }else{

                $host = $argv[1];
                $username = $argv[2];
                $password = $argv[3];
                $port = $argv[4];
                $db = $argv[5];
        
                connect_db($host,$username,$password,$port,$db);

            }
    
    }catch(Exception $e){

        echo "toto";

        if(!file_exists(ERROR_LOG_FILE)){
            file_put_contents(ERROR_LOG_FILE,'');
        }
        $message = file_get_contents(ERROR_LOG_FILE);
        $message .= $e->getMessage();
        error_log($message,0,ERROR_LOG_FILE);
    }
  
}

function connect_db($host,$username,$password,$port,$db){


    try{

        $connect = new PDO('mysql:host='.$host.';dbname='.$db.';port='.$port,$username,$password);
        
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $request = "SELECT * FROM movies;";
        
        $result = $connect -> query($request);
        
        $res = $result->fetchAll();
        
       echo "Connection to DB succesful".PHP_EOL;
        
        }catch(PDOException $e){
        
            $errorMessage = "Error connection to DB ";

            if(!file_exists(ERROR_LOG_FILE)){
                file_put_contents(ERROR_LOG_FILE,'');
            }
            $message = file_get_contents(ERROR_LOG_FILE);
            $message .= $errorMessage.$e->getMessage();
            error_log($message,0,ERROR_LOG_FILE);
        }
        

}




