<?php

const ERROR_LOG_FILE = 'errors.log';
const DB_HOST = 'localhost';
const DB_USERNAME = 'jackson';
const DB_PASSWORD = 'zxcvbn';
const DB_PORT = '3306';
const DB_NAME = 'gecko';


if(isset($argv)){

    try{
         $totalArgs = count($argv);

            if($totalArgs < 5){

                throw new Exception("Bad params ! Usage: add_user.php login password password_conf role");

            }else{

                $password = $argv[2];
                $login = $argv[1];
                $password_conf = $argv[3];
                $role = $argv[4];
        
                connect_db($login,$password,$password_conf,$role);

            }
    
    }catch(Exception $e){

        if(!file_exists(ERROR_LOG_FILE)){
            file_put_contents(ERROR_LOG_FILE,'');
        }
        $message = file_get_contents(ERROR_LOG_FILE);
        $message .= $e->getMessage().PHP_EOL;
        echo  file_put_contents(ERROR_LOG_FILE,$message);
        error_log($message,0,ERROR_LOG_FILE);

        die();
    }
  
}

function connect_db($login,$password,$password_conf,$role){



    try{

        $connect = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';port='.DB_PORT,DB_USERNAME,DB_PASSWORD);
        
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $getFields = "SHOW COLUMNS FROM users;";

        $fields = $connect -> query($getFields);
       
        $arrayFields = $fields->fetchAll(PDO::FETCH_ASSOC);

        foreach($arrayFields as $field){

            $keys[] = $field['Field'];
            $Name[] = $field['Field'];
            $parseFields = array_combine($keys,$Name);

        }

        if(!in_array('role',$parseFields)){

           $alter = "ALTER TABLE users ADD COLUMN role VARCHAR(255) NOT NULL; DEFAULT INVITE;";

       if($connect -> query($alter)){

           echo "COLUMN 'role' CREATED";

           };

       }

        if(!in_array('login',$parseFields)){

            $alter = "ALTER TABLE users ADD COLUMN login VARCHAR(20);";

        if($connect -> query($alter)){

            echo "COLUMN 'login' CREATED";

            };

        }


        
        if($password == $password_conf){

        $pass = hash('sha256',$password_conf);

        }else{

            throw new Exception("Passwords don't match");
        }

        $Urole = strtoupper($role);


        if($Urole == 'ADM' || $Urole == 'GLOBAL' || $Urole == 'INVITE'){

                $role = $Urole;

        }else{

            throw new Exception("Bad role: ADM|GLOBAL|INVITE");

        };

        //NE pas oublier de rentrer les valeurs par défaut lors de la création de l'utilisateur (email,name,created_at,is_admin)

        $request = "INSERT INTO users(name,login,password,role,email,created_at,is_admin) VALUES('$login','$login','$pass','$role','test@php.com',NOW(),1);";

        if($connect -> query($request)){

            echo "User added to DB".PHP_EOL;

        }else{

            new PDOException("Error MySQL, user not added, more infos in ".ERROR_LOG_FILE);

        }

             echo "Connection to DB succesful".PHP_EOL;

        }catch(PDOException $e){
        
            $errorMessage = "Error connection to DB ".PHP_EOL;
           

            if(!file_exists(ERROR_LOG_FILE)){
                file_put_contents(ERROR_LOG_FILE,'');
            }
            $message = file_get_contents(ERROR_LOG_FILE);
            $message .= $errorMessage.$e->getMessage().PHP_EOL;
            file_put_contents(ERROR_LOG_FILE,$message);
            echo  file_put_contents(ERROR_LOG_FILE,$message);
            error_log($message,0,ERROR_LOG_FILE);

            die();
        }catch(Exception $e){

            if(!file_exists(ERROR_LOG_FILE)){
                
                file_put_contents(ERROR_LOG_FILE,'');
            }

            $RegularMessage = file_get_contents(ERROR_LOG_FILE);
            $RegularMessage .= $e.PHP_EOL;
            error_log($RegularMessage,0,ERROR_LOG_FILE).PHP_EOL;
            file_put_contents(ERROR_LOG_FILE,$RegularMessage);
            error_log($RegularMessage,0,ERROR_LOG_FILE);
           
            die();

        }
        

}




