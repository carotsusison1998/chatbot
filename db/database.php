<?php

class database{
    function connect(){
        $connect = new PDO("mysql:host=localhost; dbname=db_chatbot", "root", "");
        return $connect;
    }
}


?>