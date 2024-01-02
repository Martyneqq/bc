<?php

require_once 'class/Records.php';
class RecordsJournal extends Records
{
    protected $connect;
    protected $userData;
    protected $userID;
    protected $pageTitle;
    protected $head;
    protected $header;
    protected $title;
    protected $auth;
    protected $sumA = 0.0;
    protected $sumB = 0.0;
    protected $sumC = 0.0;
    protected $sumD = 0.0;
    public function __construct($connect, $pageTitle, $title)
    {
        $this->auth = new Authenticator($connect, $title);
        $this->userData = $this->auth->Check();
        $this->userID = $this->userData['id'];
        $this->head = new Head($title);
        $this->header = new Header($connect, $this->userData, $this->userID);
        $this->connect = $connect;
        $this->pageTitle = $pageTitle;
    }
}