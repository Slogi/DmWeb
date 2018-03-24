<?php

require_once("model/CompteStorage.php");


class CompteStorageStub implements CompteStorage
{
    public function __construct($db, $serieSto)
    {
        $this->serieSto = $serieSto;
        $this->db = $db;
    }

    public function checkAuth($pseudo, $password)
    {
        // TODO: Implement checkAuth() method.
    }

}