<?php
require('inc/headers.php');
require('inc/functions.php');
//lisää uuden käyttäjän tietokantaan

    //Ottaa vastaan tiedot clientilta json muodossa
    $json = json_decode(file_get_contents('php://input'));
    //sanitointi
    $username = filter_var($json->username, FILTER_SANITIZE_STRING);
    $password = filter_var($json->password, FILTER_SANITIZE_STRING);
    $first_name = filter_var($json->first_name, FILTER_SANITIZE_STRING);
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    try {
        //Avataan tietokanta yhteys, valmistellaan ja lisätään käyttäjän tiedot tietokantaan
        $db = openDbcon();
        $sql = $db->prepare("INSERT INTO users VALUES(?,?,?,?)");
        $sql->execute(array($db->lastInsertId(),$username,$password_hash,$first_name));        
            echo "Tietosi tallennettiin, kiitos!";       
    } 
    catch (PDOException $e) {
        echo 'br' .$e->getMessage();
    }
?>