<?php

require_once ("model/Serie.php");
require_once ("model/MangaStorageImpl.php");
require_once ("model/SerieStorage.php");

class SerieStorageImpl implements SerieStorage
{
    private $db;
    private $mangaSto;

    public function __construct($db, $mangaSto)
    {
        $this->db = $db;
        $this->mangaSto = $mangaSto;
    }

    public function create(Serie $s)
    {
        // TODO: Implement create() method.
    }

    /**
     * Retourne pour une série, tous ses mangas
     * @param $id
     * @return null|Serie
     */
    public function read($id)
    {
        $query = "SELECT idSerie, titre, auteur, synopsis 
                  FROM serie
                  where idSerie = $id";

        $stmt = $this->db->prepare($query);

        $stmt->execute();

        $num = $stmt->rowCount();

        if($num>0)
        {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $idSerie = $row['idSerie'];
            $titre = $row['titre'];
            $auteur = $row['auteur'];
            $synopsis = $row['synopsis'];
            $mangas = $this->mangaSto->readAll($idSerie);

            return new Serie( $idSerie,$titre, $auteur, $synopsis, $mangas);
        }
        else
        {
            return null;
        }
    }

    /**
     * Pour un utilisateur, retourne toutes ses series
     * @param $pseudo
     * @return array|null
     */
    public function readAllUser($pseudo)
    {
        $query = "SELECT l.idSerie, s.titre, s.auteur, s.synopsis
                  FROM listeserie l
                  join serie s on l.idSerie = s.idSerie
                  WHERE pseudo = '$pseudo'";

        $stmt = $this->db->prepare($query);

        $stmt->execute();

        $num = $stmt->rowCount();

        if($num>0)
        {
            $mangas = array();
            $series = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                $idSerie = $row['idSerie'];
                $titre = $row['titre'];
                $auteur = $row['auteur'];
                $synopsis = $row['synopsis'];

                array_push($series, new Serie($idSerie, $titre, $auteur, $synopsis, $mangas));
            }

            return $series;
        }
        else
        {
            return null;
        }
    }

    public function readAll()
    {
        $query = "SELECT DISTINCT pseudo 
                  FROM listeserie";

        $stmt = $this->db->prepare($query);

        $stmt->execute();

        $num = $stmt->rowCount();

        if($num>0)
        {
            $pseudoSeries = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                $series = array();
                $pseudo = $row['pseudo'];
                echo $pseudo;
                $series = $this->readAllUser($pseudo);
                $pseudoSeries[$pseudo] = $series;
            }

            return $pseudoSeries;
        }
        else
        {
            return null;
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