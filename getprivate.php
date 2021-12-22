<?php

//Jos käyttäjä on kirjautuneena, hänelle näytetään omat tiedot.
session_start();
require('inc/headers.php');
require('inc/functions.php');

//Jos ei ole sessiota, Koodia ei suoriteta
if(!isset($_SESSION["user"])){
    exit;
}

//Tarkastetaan bearer ja token, jos ok niin koodi suoritetaan
$requestHeaders = apache_request_headers();

if(isset($requestHeaders['authorization'])){
    $auth_value = explode(' ', $requestHeaders['authorization']);
    if( $auth_value[0] === 'Bearer' ){
        $token = $auth_value[1];

        if($token === $_SESSION["token"]){
            echo "Sinun yksityiset tietosi:";
        }
        //Kirjautuneen username talteen
        $username = $_SESSION["user"];
        try{
            $db = openDbcon();
            //Lisätään tauluun myös kirjautuneen username jolla tiedot kohdistetaan kyseiselle henkilölle.
            selectAsJson($db, "select * from favorite_things where username='$username'");
            header('Content-Type: application/json');
            echo json_encode(array("token"=>$_SESSION['token']));

        }catch(Exception $e){
            echo json_encode(array("message"=>"No access!!") );
        }
    }
}

else {
    echo "Kirjaudu sisään";
}
?>