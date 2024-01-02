<?php
/*
        $server = "md377.wedos.net";
        $username = "a328711_evidenc";
        $password = "VURwVvue";
        $database = "d328711_evidenc";
*/
class DatabaseConnect
{
    private $server;
    private $username;
    private $password;
    private $database;
    private $connect;
    public function __construct($server, $username, $password, $database)
    {
        $this->server = $server;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
    }
    private function DBConnect()
    {
        $this->connect = mysqli_connect($this->server, $this->username, $this->password, $this->database);
        
        if (!$this->connect) {
            die("Login error");
        }
        
        mysqli_set_charset($this->connect, "utf8");
        return $this->connect;
    }
    public function GetConnect(){
        return $this->connect;
    }
}

?>