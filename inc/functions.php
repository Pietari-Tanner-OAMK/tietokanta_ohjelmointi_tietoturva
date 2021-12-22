<?php

//Functio joka tarkastaa onko käyttäjä validi
function checkUser(PDO $db, $username, $password, $first_name){
    
    //Sanitointi
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $password = filter_var($password, FILTER_SANITIZE_STRING);
    $first_name = filter_var($first_name, FILTER_SANITIZE_STRING);

    try{
        $sql = "SELECT password FROM users WHERE username=?";
        $prepare = $db->prepare($sql);
        $prepare->execute(array($username));
        //Haetaan tulokser fetch functiolla.
        $rows = $prepare->fetchAll();

        //Etsitään salasanarivi ja jos palautetaan true arvo jos kaikki ok.
        foreach($rows as $row){
            $pw = $row["password"];
           
            //purkaa salasanan HASHin
            if(password_verify($password, $pw) ){ 
                return true;
            }
        }
        //Jos tiedot eivät tästää. palautetaan false
        return false;

    }catch(PDOException $e){
        echo '<br>'.$e->getMessage();
    }
}
//Kyselyn functio
function selectAsJson(object $db,string $sql): void {
    $query = $db->query($sql);
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    header('HTTP/1.1 200 OK');
    echo json_encode($result);
}
//Luo tietokantayhteyden
function openDbcon(){
    try{
        $db = new PDO('mysql:host=localhost;dbname=c0tapi01', 'root', '');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
        echo '<br>'.$e->getMessage();
    }

    return $db;
}
?>