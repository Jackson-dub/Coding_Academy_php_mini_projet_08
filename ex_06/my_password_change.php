<?php

function my_password_change($pdo,$mail,$pass){

    try{

            if(!$pass){

                throw new Exception();

            }else{

                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $quest = "SELECT email FROM users;";
            
                $result = $pdo -> query($quest);
            
                $res = $result->fetchAll();

                $Mails = [];

                foreach($res as $email){

                    $Mails[] = $email['email'];

                };

                if(in_array($mail,$Mails)){

                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $newPass = password_hash($pass,PASSWORD_DEFAULT);
                
                    $request = $pdo->prepare("UPDATE users SET password=:newPass WHERE email=:mail;");
        
                $request->execute(array('newPass'=>$newPass,
                                        'mail'=> $mail
                ));

                }else{

                    throw new Exception();
                }


            }
            
        }catch(PDOException $e){
        
            echo "PDO ERROR: ".$e->getMessage();
        
        }


}
