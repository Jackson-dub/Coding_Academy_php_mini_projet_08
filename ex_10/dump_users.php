<?php

const ERROR_LOG_FILE = 'errors.log';
const DB_HOST = 'localhost';
const DB_USERNAME = 'jackson';
const DB_PASSWORD = 'zxcvbn';
const DB_PORT = '3306';
const DB_NAME = 'gecko';


    try{

       
         $totalArgs = count($argv);

            if( $totalArgs > 1 && $totalArgs < 4){

                throw new Exception("Bad params ! Usage: dump_users.php [filter value exact]");
        
            }else{

                if($totalArgs == 4){

                    if(strtolower($argv[1]) == "password"){

                        throw new Exception("Don't try to filter by the password, it's not possible");

                    }else{

                        $filter= $argv[1];
                        $value = $argv[2];
                        $exact = $argv[3];
                    }

                }elseif($totalArgs <= 1){

                    $filter= null;
                    $value = null;
                    $exact = null;

                }  
                
            dump_users($filter,$value,$exact);

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
  

function dump_users($filter = null,$value = null ,$exact = null){

    try{

        $connect = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';port='.DB_PORT,DB_USERNAME,DB_PASSWORD);
        
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        echo "Connection to DB succesful".PHP_EOL;

  
         $getFields = "SHOW COLUMNS FROM users;";

         $fields = $connect -> query($getFields);
        
         $arrayFields = $fields->fetchAll(PDO::FETCH_ASSOC);

      
         foreach($arrayFields as $field){

             $keys[] = $field['Field'];
             $Name[] = $field['Field'];
             $parseFields = array_combine($keys,$Name);

         }

         if(!in_array('is_active',$parseFields)){

                 $alter = "ALTER TABLE users ADD COLUMN is_active BOOLEAN NOT NULL DEFAULT 1;";

             if($connect -> query($alter)){

                 echo "COLUMN 'is_active' CREATED";

                 };

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

        
        if($filter && $value && $exact){

            

                if($exact == 'true'){

                    $request = $connect -> prepare("SELECT * FROM users WHERE ".$filter."= :value");

                }else{

                    $request = $connect -> prepare("SELECT * FROM users WHERE ".$filter." LIKE '%:value'");

                }


        }else{

             $request = $connect -> prepare("SELECT * FROM users;");
           
         }

        if($request -> execute(array(':value' => $value))){

        $res = $request -> fetchAll(PDO::FETCH_ASSOC);

        }else{

        new PDOException("Error MySQL, more infos in ".ERROR_LOG_FILE);

        }

            $numberElmt = count($res);

        if($numberElmt > 0){

            foreach($res as $array){

                if($array['is_active'] == 1){

                    $state = 'active';

                }else{

                    $state = 'inactive';
                }

                $string = "";

                        $string .= ""."[".$array['id']."] ".$array['login']." ".$array['role']." "."(".$state.")"; 

               echo $string.PHP_EOL;
               
            };

            

        }else{

            echo "No results matching your search \n";

        }


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




