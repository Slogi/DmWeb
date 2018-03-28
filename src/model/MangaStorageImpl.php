<?php

require_once ("model/Manga.php");

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
        $query = "INSERT INTO manga (idSerie, numTome, resume, dateParu)
                                  VALUES ('".$idSerie."', ?, ?, ?)";

        $stmt = $this->db->prepare($query);

        $stmt->bindValue(1, $m->getNumTome() );
        $stmt->bindValue(2, $m->getResume() );
        $stmt->bindValue(3, $m->getDateParu() );

        if ( $stmt->execute()=== true ){

            return $m->getNumTome();
        };

        return null;
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

    public function update($idS, Manga $m)
    {

        $query = "UPDATE FROM manga
                  SET resume = ? , dateParu = ?
                  WHERE idSerie = $idS and numTome = $m->getNumTome()";

        $stmt = $this->db->prepare($query);

        $stmt->bindValue(1, $m->getResume() );
        $stmt->bindValue(2, $m->getDateParu() );

        if ( $stmt->execute()=== true ){

            return "Le manga a modifié";
        };

        return null;
    }

    public function delete($id, $numTome)
    {

        $query = "DELETE FROM manga
                    WHERE idSerie = $id and numTome = $numTome";

        $stmt = $this->db->prepare($query);

        if ( $stmt->execute()=== true ){

            return "Le manga a été supprimé";
        };

        return null;
    }
}