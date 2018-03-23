<?php

require_once ("./Manga.php");

interface MangaStorage
{
    public function create(Manga $m);

    public function read($id);

    public function readAll();

    public function update($id, Manga $m);

    public function delete($id);

    public function deleteAll();
}