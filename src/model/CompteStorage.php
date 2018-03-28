<?php

interface CompteStorage {

    public function checkAuth($pseudo, $password);

    public function create (Compte $c);

    public function read($pseudo);
}