<?php
/**
 * Created by PhpStorm.
 * User: Slogix
 * Date: 24/03/2018
 * Time: 12:14
 */

interface MangaStorage
{
    public function create(Manga $m, $idSerie);

    public function read($idS, $numTome);

    public function readAll($idS);

    public function update($idS, Manga $m);

    public function delete($idS, $numTome);

}