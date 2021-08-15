<?php
$pdo = new PDO('mysql:host=localhost;dbname=gecko;port=3306','jackson','zxcvbn');
$args = ['Martin'];

 // $test3 = str_replace("\n",",",print_r($test,true));

 //$almost = preg_replace('/(^Array|^\\(\n|^\\)\n|^\s*)/m','',$test3);

//  $quest = "SHOW TABLES;";
        
//  $result = $pdo -> query($quest);

//  $res = $result->fetchAll();

//  for($i= 0; $i < count($res); $i++){

//       $keys[] = $res[$i][0];
//       $Value[] = $res[$i][0];
//       $data = array_combine($keys,$Value);

//        };

//          if(array_key_exists($arg,$data)){

//              $argToLoad = $data[$arg];


// test <exo-8>


$pdo = new PDO('mysql:host=localhost;dbname=gecko;port=3306','jackson','zxcvbn');
$arg = 'users';

$tests  = my_show_db($pdo,$arg);

foreach($tests as $test){

    echo $test;
}

