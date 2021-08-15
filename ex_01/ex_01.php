<?php

function my_very_secure_hash($pass){

    if(gettype($pass) == 'string'){

        $hashedPass = md5($pass);
        return $hashedPass;
    }
}