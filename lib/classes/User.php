<?php

class User {
    var $id;
    var $username;
    var $first_name;
    var $last_name;
    var $email;
    var $password;
    var $password2;
    var $token;
    var $notify;

    function __set($name, $value){
        $this->$name = $value;
    }

    function __get($key){
        if ($this->$key){
            return $this->$key;
        }else{
            return NULL;
        }
    }
}

?>