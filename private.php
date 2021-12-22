<?php

//Kirjautunut käyttäjä pystyy lisämään tietoja toiseen tauluun
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
        }
        //Otetaan input tiedot clientilta (ei käytössä tässä)
        $json = json_decode(file_get_contents('php://input'));
        //Kirjautuneen username talteen
        $username = $_SESSION["user"];
        //Sanitointi
        $favorite_color = filter_var($json->favorite_color, FILTER_SANITIZE_STRING);
        $favorite_food = filter_var($json->favorite_food, FILTER_SANITIZE_STRING);
        $favorite_drink = filter_var($json->favorite_drink, FILTER_SANITIZE_STRING);
        
        try {
            //Avataan tietokanta yhteys, valmistellaan ja lisätään kirjautuneen käyttäjän lisätietoja toiseen tauluun
            $db = openDbcon();
            $sql = $db->prepare("INSERT INTO favorite_things VALUES(?,?,?,?,?)");
            //Lisätään tauluun myös kirjautuneen $username jolla tiedot kohdistetaan kyseiselle henkilölle.
            $sql->execute(array($db->lastInsertId(),$username,$favorite_color,$favorite_food,$favorite_drink));        
                echo "Tietosi tallennettiin!";       
        } 
        catch (PDOException $e) {
            echo 'br' .$e->getMessage();
        }
    }else {
        echo "Kirjaudu sisään";
    }
}
?>
