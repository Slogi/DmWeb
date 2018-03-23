<?php

require_once ("./Serie.php");

interface SerieStorage
{
    public function create(Serie $s);

    public function read($id);

    public function readAll();

    public function update($id, Serie $s);

    public function delete($id);

    public function deleteAll();
}