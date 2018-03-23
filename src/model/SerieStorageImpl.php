<?php

require_once ("model/Manga.php");
require_once ("model/Serie.php");
require_once ("model/SerieStorage.php");

class SerieStorageImpl implements SerieStorage
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function create(Serie $s)
    {
        // TODO: Implement create() method.
    }

    public function read($id)
    {
        $query = "SELECT s.idSerie, s.titre, s.auteur, s.synopsis, m.numTome, m.resume, m.dateParu 
                  FROM serie s 
                  join manga m on m.idSerie = s.idSerie
                  where s.idSerie = $id
                  order by idSerie";

        $stmt = $this->db->prepare($query);

        $stmt->execute();

        $num = $stmt->rowCount();

        if($num>0){
            $titre ="";
            $auteur ="";
            $synopsis ="";
            $idSerie = 0;
            $mangas = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

                if ( $idSerie === 0){

                    $titre = $row['titre'];
                    $auteur = $row['auteur'];
                    $synopsis = $row['synopsis'];
                    array_push($mangas, new Manga(
                        $row['numTome'],
                        $row['resume'],
                        $row['dateParu']
                    ));
                }
                else {

                    array_push($mangas, new Manga(
                        $row['numTome'],
                        $row['resume'],
                        $row['dateParu']
                    ));
                }

                $idSerie = $row['idSerie'];
            }
            return new Serie( $titre, $auteur, $synopsis, $mangas);
        }
        else{
            return null;
        }
    }

    public function readAll()
    {
        $query = "SELECT s.idSerie, s.titre, s.auteur, s.synopsis, m.numTome, m.resume, m.dateParu 
                  FROM serie s 
                  join manga m on m.idSerie = s.idSerie 
                  order by idSerie";

        $stmt = $this->db->prepare($query);

        $stmt->execute();

        $num = $stmt->rowCount();

        if($num>0){

            $titre ="";
            $auteur ="";
            $synopsis ="";
            $series = array();
            $idSerie = 0;
            $mangas = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

                if ( $idSerie === 0){

                    $titre = $row['titre'];
                    $auteur = $row['auteur'];
                    $synopsis = $row['synopsis'];
                    array_push($mangas, new Manga(
                        $row['numTome'],
                        $row['resume'],
                        $row['dateParu']
                    ));
                }
                elseif( $idSerie !== $row['idSerie']){

                    array_push( $series, new Serie( $titre, $auteur, $synopsis, $mangas));
                    $mangas = array();
                    $titre = $row['titre'];
                    $auteur = $row['auteur'];
                    $synopsis = $row['synopsis'];
                    array_push($mangas, new Manga(
                        $row['numTome'],
                        $row['resume'],
                        $row['dateParu']
                    ));
                }
                else{

                    array_push($mangas, new Manga(
                        $row['numTome'],
                        $row['resume'],
                        $row['dateParu']
                    ));
                }

                $idSerie = $row['idSerie'];
            }
            array_push( $series, new Serie( $titre, $auteur, $synopsis, $mangas));
            return $series;

        }
        else{
            return array();
        }

    }

    public function update($id, Serie $s)
    {
        // TODO: Implement update() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function deleteAll()
    {
        // TODO: Implement deleteAll() method.
    }

}