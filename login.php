<?php
session_start();
require('inc/headers.php');
require('inc/functions.php');

//Tarkistetaan tuleeko palvelimelle basic login tiedot (authorization: Basic asfkjsafdjsajflkasj)
if(isset($_SERVER['PHP_AUTH_USER']) ){
    //Tarkistetaan käyttäjä tietokannasta
    if(checkUser(openDbCon(), $_SERVER['PHP_AUTH_USER'],$_SERVER["PHP_AUTH_PW"],$_SERVER["PHP_AUTH_PW"] )){
        $_SESSION["user"] = $_SERVER['PHP_AUTH_USER'];
        $_SESSION["token"] = bin2hex(openssl_random_pseudo_bytes(16));
        echo json_encode(array("info"=>"Kirjauduit sisään", "token"=>$_SESSION['token']));
        header('Content-Type: application/json');
        exit;
    }
}

//Väärät tunnukset antaa ilmoituksen
echo '{"info":"Väärä käyttäjätunnus tai salasana"}';
header('Content-Type: application/json');
header('HTTP/1.1 401');
exit;

?>