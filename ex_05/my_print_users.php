<?php


function my_print_users($pdo,...$args){

    foreach($args as $arg){

        if(gettype($arg) !== 'integer'){

            throw new Exception("Invalid id given");

            die();

        }
            
    };
    
            try{
                
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $request = "SELECT * FROM users;";
                
                $result = $pdo -> query($request);
                
                $res = $result->fetchAll();

                $Id = [];
                $Name = [];
                
                 for($i= 0; $i < count($res); $i++){

                $Id[] = $res[$i]['id'];
                $Name[] = $res[$i]['name'];
                $data = array_combine($Id,$Name);

                 };

                $finalArray = [];

                 foreach($args as $arg){
                     if(array_key_exists($arg,$data)){

                        $finalArray[]=$data[$arg];

                     }
                 }

                if(count($finalArray) > 0){

                    foreach($finalArray as $name){
                        echo $name."\n";
                    };
                    return (true);

                }else{

                    return (false);
                }
                
                }catch(PDOException $e){
                
                    echo "PDO ERROR: ".$e->getMessage().PHP_EOL;
                
                }

}

