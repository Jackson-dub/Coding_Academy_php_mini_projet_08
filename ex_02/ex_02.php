<?php
function my_password_hash($password){

    if(gettype($password) == 'string'){

        $salt = str_shuffle("Oauth".random_int(11,9999));
    $passArray = ["hash" => crypt($password,$salt), "salt" => $salt];

    }
    return $passArray;

}


function my_password_verify($passWordText,$salt,$hashedPassword){


        $pass = crypt($passWordText,$salt);
        $hash = $hashedPassword;

   if ($pass == $hash){
     return "true";
   }else{
    return "false";
   }
  
}
