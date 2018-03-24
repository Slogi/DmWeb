<?php
/**
 * Created by PhpStorm.
 * User: Slogix
 * Date: 24/03/2018
 * Time: 12:15
 */
require_once ("model/MangaStorage.php");

class MangaStorageImpl implements MangaStorage
{

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function create(Manga $m, $idSerie)
    {
        // TODO: Implement create() method.
    }

    public function read($idS, $numTome)
    {
        $query = "SELECT idSerie, numTome, resume, dateParu 
                  FROM manga 
                  where idSerie = $idS and numTome = $numTome
                  order by idSerie";

        $stmt = $this->db->prepare($query);

        $stmt->execute();

        $num = $stmt->rowCount();

        if($num>0){

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return new Manga( $row['numTome'], $row['resume'], $row['dateParu']);
        }
        else {

            return null;

        }
    }

    public function readAll($idS){

        $query = "SELECT numTome, resume, dateParu 
                  FROM manga
                  where idSerie = $idS";

        $stmt = $this->db->prepare($query);

        $stmt->execute();

        $num = $stmt->rowCount();

        if($num>0){

            $mangas = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

                    array_push($mangas, new Manga(
                        $row['numTome'],
                        $row['resume'],
                        $row['dateParu']
                    ));

            }
            return $mangas;
        }
        else{

            return null;
        }

    }

    public function update($id, Manga $m)
    {
        // TODO: Implement update() method.
    }

    public function delete($id, $numTome)
    {
        // TODO: Implement delete() method.
    }
}

