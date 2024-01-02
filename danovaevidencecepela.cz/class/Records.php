<?php

class Records
{
    protected $connect;
    protected $userData;
    protected $userID;
    protected $pageTitle;
    protected $title;
    protected $head;
    protected $header;
    protected $alert;
    protected $auth;
    protected $dbHelper;

    public function __construct($connect, $pageTitle, $title)
    {
        $this->connect = $connect;
        $this->userData = $this->auth->Check();
        $this->userID = $this->userData['id'];
        $this->auth = new Authenticator($connect, $title);
        $this->head = new Head($title);
        $this->header = new Header($connect, $this->userData, $this->userID);
        $this->alert = new Alert();
        $this->dbHelper = new DatabaseHelper($connect, $this->userID);
        $this->pageTitle = $pageTitle;
    }
    public function GetHead()
    {
        return $this->head;
    }
    public function GetHeader()
    {
        return $this->header;
    }
    function secure($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    function getColor($type, $str1, $str2)
    {
        if ($type == $str1) {
            return 'green';
        } else if ($type == $str2) {
            return 'red';
        }
    }
    public function GenerateDocument($row, $row1, $row2, $str1, $str2)
    {
        $typeOfDocument = ["HP", "HV", "BP", "BV"];
        $uhrada = $row[$row1] == $str1 ? 0 : 2;
        $prijemvydaj = $row[$row2] == $str2 ? 0 : 1;
        $dateOfDocument = date("y", strtotime($row['datum']));
        $documentType = $typeOfDocument[$uhrada + $prijemvydaj];
        //$documentCounter[$documentType]++;
        return $dateOfDocument . $documentType . $row['doklad'] /* . $documentCounter[$documentType]*/;
    }
}