<?php

function my_change_user($pdo,...$args){

    try{

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $quest = "SELECT name FROM users;";
        
            $result = $pdo -> query($quest);
        
            $res = $result->fetchAll();

            for($i= 0; $i < count($res); $i++){

                $keys[] = $res[$i]['name'];
                $Name[] = $res[$i]['name'];
                $data = array_combine($keys,$Name);

                 };

                $finalArray = [];

                 foreach($args[0] as $arg){

                     if(array_key_exists($arg,$data)){

                        $finalArray[]=$data[$arg];

                        if(count($finalArray) > 0){

            
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
                            foreach($finalArray as $argToChange){
            
                                $request = $pdo->prepare("UPDATE users SET name= CONCAT(UPPER(LEFT(name,1)),SUBSTRING(name,2),'42') WHERE name= :arg;");
                    
                            $request->execute(array('arg'=>$argToChange,
                                                    
                            ));
            
                            }
                            
            
                        }

                     }else{

                        throw new PDOexception("User not found");
        
                    }

                 }
  
    }catch(PDOException $e){
    
        echo "PDO ERROR: ".$e->getMessage().PHP_EOL;
    
    }finally{

        echo "Good luck with the user DB! \n";
    }

}
