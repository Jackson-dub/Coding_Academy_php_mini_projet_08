<?php

class UserManager{


    protected $localhost;
    protected $dbuser;
    protected $dbpass;
    protected $dbport;
    protected $dbname;
    protected $pdo;
    const ERROR_LOG_FILE = 'errors.log';
    protected $count = 5;

    function __construct($localhost,$dbuser,$dbpass,$dbport,$dbname){

                $this -> localhost = $localhost;
                $this -> dbuser = $dbuser;
                $this -> dbpass = $dbpass;
                $this -> dbport = $dbport;
                $this -> dbname = $dbname;

                try{

                    if($this->count > 0){

                        $this->pdo = new PDO('mysql:host='.$this->localhost.';dbname='.$this->dbname.';port='.$this->dbport,$this->dbuser,$this->dbpass);
                
                    }else{

                        throw new Exception("Invalid credentilas ".$this->count." remaining \r");
                    }
                    

                }catch(PDOException $e){

                    if(!file_exists(self::ERROR_LOG_FILE)){
                        file_put_contents(self::ERROR_LOG_FILE,'');
                    }
                    $message = file_get_contents(self::ERROR_LOG_FILE);
                    $message .= $e->getMessage();
                    error_log($message,0,self::ERROR_LOG_FILE);  
                
                }
                
            
            }


    function start(){

        try{

                        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                        $request = "SELECT name FROM users;";
                    
                        $result =  $this->pdo -> query($request);
                    
                        $res = $result->fetchAll(PDO::FETCH_ASSOC);

                        //print_r($res);

                        foreach($res as $field){

                            $keys[] = $field['name'];
                            $Name[] = $field['name'];
                            $parseFields = array_combine($keys,$Name);
                
                        }
                
                        if(in_array($this->dbuser,$parseFields)){
                    
                            $loginQuest = $this->pdo->prepare("SELECT * FROM users WHERE name = :user;");
                    
                            if($loginQuest-> execute(array(':user'=>$this->dbuser))){
                        
                                $response = $result->fetchAll(PDO::FETCH_ASSOC);

                                print_r($response);

                                }else{

                                    throw new Exception('test');
                                }                                   
                
                        }

                    
                // echo "Connection to DB succesful".PHP_EOL;
                    
            }catch(PDOException $e){
           
                $errorMessage = "Error connection to DB ";
   
                if(!file_exists(self::ERROR_LOG_FILE)){
                    file_put_contents(self::ERROR_LOG_FILE,'');
                }
                $message = file_get_contents(self::ERROR_LOG_FILE);
                $message .= $errorMessage.$e->getMessage();
                error_log($message,0,self::ERROR_LOG_FILE);
            }catch(Exception $e){

                if(!file_exists(self::ERROR_LOG_FILE)){
                    file_put_contents(self::ERROR_LOG_FILE,'');
                }
                $message = file_get_contents(self::ERROR_LOG_FILE);
                $message .= $e->getMessage();
                error_log($message,0,self::ERROR_LOG_FILE);

            }

    }    


    function connect(){

        return $this;

    }


    function addUser($login,$password,$password_conf,$role){

        //,$password,$password_conf,$role
    
            $getFields = "SELECT login FROM users;";
    
            $fields = $this->pdo->query($getFields);
           
            $arrayFields = $fields->fetchAll(PDO::FETCH_ASSOC);

            //print_r($arrayFields);
    
            foreach($arrayFields as $field){
    
                $keys[] = $field['login'];
                $Name[] = $field['login'];
                $parseFields = array_combine($keys,$Name);
    
            }

            //print_r($parseFields);

            if(!in_array($login,$parseFields)){

            try{

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
            
                    if($this->pdo-> query($request)){
            
                        echo "User added to DB".PHP_EOL;
            
                    }else{
            
                        new PDOException("Error MySQL, user not added, more infos in ".ERROR_LOG_FILE);
            
                    }
     
     
            }catch(PdOException $e){

                if(!file_exists(self::ERROR_LOG_FILE)){
                    file_put_contents(self::ERROR_LOG_FILE,'');
                }
                $message = file_get_contents(self::ERROR_LOG_FILE);
                $message .= $e->getMessage().PHP_EOL;
                file_put_contents(self::ERROR_LOG_FILE,$message);
                echo  file_put_contents(self::ERROR_LOG_FILE,$message);
                error_log($message,0,self::ERROR_LOG_FILE);

            }catch(Exception $e){

                if(!file_exists(self::ERROR_LOG_FILE)){
                    file_put_contents(self::ERROR_LOG_FILE,'');
                }
                $message = file_get_contents(self::ERROR_LOG_FILE);
                $message .= $e->getMessage().PHP_EOL;
                file_put_contents(self::ERROR_LOG_FILE,$message);
                echo  file_put_contents(self::ERROR_LOG_FILE,$message);
                error_log($message,0,self::ERROR_LOG_FILE);

            }
            
            
    }
}

    // END addUser

    function modifyUser($id,$key,$newvalue,$conf_value){


            try{

                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $quest = "SELECT name FROM users;";
            
                $result = $this->pdo -> query($quest);
            
                $res = $result->fetchAll();

                // for($i= 0; $i < count($res); $i++){

                //     $keys[] = $res[$i]['name'];
                //     $Name[] = $res[$i]['name'];
                //     $data = array_combine($keys,$Name);

                //     };

                    // $finalArray = [];


                    //     if(array_key_exists($arg,$data)){

                    //         $finalArray[]=$data[$arg];

                    //         if(count($finalArray) > 0){

                
                    //             $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                    //             foreach($finalArray as $argToChange){
                
                    //                 $request = $this->pdo->prepare("UPDATE users SET name= CONCAT(UPPER(LEFT(name,1)),SUBSTRING(name,2),'42') WHERE name= :arg;");
                        
                    //             $request->execute(array('arg'=>$argToChange,
                                                        
                    //             ));
                
                    //             }
                                
                
                    //         }

                    //     }else{

                    //         throw new PDOexception("User not found");
            
                    //     }

                    // }
    
        }catch(PDOException $e){
        
            echo "PDO ERROR: ".$e->getMessage().PHP_EOL;
        
        }finally{

            echo "Good luck with the user DB! \n";
        }


    }


    // END MODIFY USER


        function dump($filter = null,$value = null ,$exact = null){

            try{

                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                echo "Connection to DB succesful".PHP_EOL;

        
                $getFields = "SHOW COLUMNS FROM users;";

                $fields = $this->pdo -> query($getFields);
                
                $arrayFields = $fields->fetchAll(PDO::FETCH_ASSOC);

            
                foreach($arrayFields as $field){

                    $keys[] = $field['Field'];
                    $Name[] = $field['Field'];
                    $parseFields = array_combine($keys,$Name);

                }

                if(!in_array('is_active',$parseFields)){

                        $alter = "ALTER TABLE users ADD COLUMN is_active BOOLEAN NOT NULL DEFAULT 1;";

                    if($this->pdo -> query($alter)){

                        echo "COLUMN 'is_active' CREATED";

                        };

                }

                if(!in_array('role',$parseFields)){

                    $alter = "ALTER TABLE users ADD COLUMN role VARCHAR(255) NOT NULL; DEFAULT INVITE;";

                if($this->pdo-> query($alter)){

                    echo "COLUMN 'role' CREATED";

                    };

                }

                if(!in_array('login',$parseFields)){

                    $alter = "ALTER TABLE users ADD COLUMN login VARCHAR(20);";

                if($this->pdo -> query($alter)){

                    echo "COLUMN 'login' CREATED";

                    };

                }

                
                if($filter && $value && $exact){

                    

                        if($exact == 'true'){

                            $request = $this->pdo -> prepare("SELECT * FROM users WHERE ".$filter."= :value");

                        }else{

                            $request = $this->pdo -> prepare("SELECT * FROM users WHERE ".$filter." LIKE '%:value'");

                        }


                }else{

                    $request = $this->pdo-> prepare("SELECT * FROM users;");
                
                }

                if($request -> execute(array(':value' => $value))){

                $res = $request -> fetchAll(PDO::FETCH_ASSOC);

                }else{

                new PDOException("Error MySQL, more infos in ".self::ERROR_LOG_FILE);

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
                

                    if(!file_exists(self::ERROR_LOG_FILE)){
                        file_put_contents(self::ERROR_LOG_FILE,'');
                    }
                    $message = file_get_contents(self::ERROR_LOG_FILE);
                    $message .= $errorMessage.$e->getMessage().PHP_EOL;
                    file_put_contents(self::ERROR_LOG_FILE,$message);
                    echo  file_put_contents(self::ERROR_LOG_FILE,$message);
                    error_log($message,0,self::ERROR_LOG_FILE);

                    die();
                }catch(Exception $e){

                    if(!file_exists(self::ERROR_LOG_FILE)){
                        
                        file_put_contents(self::ERROR_LOG_FILE,'');
                    }

                    $RegularMessage = file_get_contents(self::ERROR_LOG_FILE);
                    $RegularMessage .= $e.PHP_EOL;
                    error_log($RegularMessage,0,self::ERROR_LOG_FILE).PHP_EOL;
                    file_put_contents(self::ERROR_LOG_FILE,$RegularMessage);
                    error_log($RegularMessage,0,self::ERROR_LOG_FILE);
                
                    die();

                }
                

        }


    // END OF DUMP

    function makeActive($id){

        try{
            
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            echo "Connection to DB succesful".PHP_EOL;

    
            $getFields = "SELECT id FROM users;";

            $fields = $this->pdo -> query($getFields);
            
            $arrayFields = $fields->fetchAll(PDO::FETCH_ASSOC);

            print_r($arrayFields);

        
            // foreach($arrayFields as $field){

            //     $keys[] = $field['Field'];
            //     $Name[] = $field['Field'];
            //     $parseFields = array_combine($keys,$Name);

            // }

            // if(!in_array('is_active',$parseFields)){

            //         $alter = "ALTER TABLE users ADD COLUMN is_active BOOLEAN NOT NULL DEFAULT 1;";

            //     if($connect -> query($alter)){

            //         echo "COLUMN 'is_active' CREATED";

            //         };

            // }

            // if(!in_array('role',$parseFields)){



    }catch(PDOException $e){}
}

    // END Makeactive

    function makeInactive($id){

            try{

                $connect = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';port='.DB_PORT,DB_USERNAME,DB_PASSWORD);
                
                $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                echo "Connection to DB succesful".PHP_EOL;

        
                $getFields = "SELECT id FROM users;";

                $fields = $connect -> query($getFields);
                
                $arrayFields = $fields->fetchAll(PDO::FETCH_ASSOC);

                print_r($arrayFields);

            
                // foreach($arrayFields as $field){

                //     $keys[] = $field['Field'];
                //     $Name[] = $field['Field'];
                //     $parseFields = array_combine($keys,$Name);

                // }

                // if(!in_array('is_active',$parseFields)){

                //         $alter = "ALTER TABLE users ADD COLUMN is_active BOOLEAN NOT NULL DEFAULT 1;";

                //     if($connect -> query($alter)){

                //         echo "COLUMN 'is_active' CREATED";

                //         };

                // }

                // if(!in_array('role',$parseFields)){


        }catch(PDOException $e){}

    }

    // END makeInactive

    function help(){

        $newClass = $this;

        return  get_class_methods($newClass);

    }

    //END help

    function logout(){


    }

    //END logout

    function quit(){
        return;
    }
  
}

$manager = new UserManager('localhost','jackson','zxcvbn','3306','gecko');
print_r($manager->connect()->start());
//$manager -> addUser('Bob','123aze','123aze','adm');
