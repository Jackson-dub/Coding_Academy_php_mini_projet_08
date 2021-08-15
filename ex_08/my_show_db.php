<?php


function my_show_db($pdo,$arg){

    try{
      
        

        if(gettype($arg) !== "string" || !isset($pdo) || !isset($arg)){

            throw new Exception();

        };

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $quest = "SHOW TABLES;";
        
            $result = $pdo -> query($quest);
        
            $res = $result->fetchAll();

            for($i= 0; $i < count($res); $i++){

                 $keys[] = $res[$i][0];
                 $Value[] = $res[$i][0];
                 $data = array_combine($keys,$Value);

                  };

                    if(array_key_exists($arg,$data)){

                        $argToLoad = $data[$arg];

                            //$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
                                 $request = "SELECT * FROM $argToLoad;";
                    
                                 $answer = $pdo -> query($request);
        
                                 $arrayRes = $answer->fetchAll();


                                 foreach($arrayRes as $array){

                                    $interArray = array_unique($array);

                                    $string = "";

                                    foreach($interArray as $k => $value){

                                        $string .= ""."[".$k."]=>"."[".$value."]".", "; 
                            
                                    }

                                    $string = substr(trim($string),0,-1);

                                    yield $string.PHP_EOL;

                                 };

                        
                         }else{

                            throw new Exception();
                         }
  
    }catch(PDOexception $message){

        return NULL;

    }catch(Exception $e){

        return NULL;

    }

}
