<?php

require_once("model/CompteStorage.php");
require_once("model/CompteBuilder.php");


class CompteStorageStub implements CompteStorage
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function checkAuth($pseudo, $password)
    {
        $query = "SELECT pseudo, mdp, nom, prenom, dateBirth, genre 
                  FROM compte";

        $stmt = $this->db->prepare($query);

        $stmt->execute();

        $num = $stmt->rowCount();

        if($num>0)
        {
            while($row = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                if ( $row['pseudo'] == $pseudo && password_verify($password, $row['mdp']))
                {
                    return new Compte($row['pseudo'], $row['mdp'], $row['nom'], $row['prenom'], $row['dateBirth'], $row['genre']);
                }
            }
            return null;
        }
        else
        {
            return null;
        }
    }

    public function create (Compte $c){

        $query = "INSERT INTO compte (pseudo, mdp, nom, prenom, dateBirth, genre)
                                  VALUES ( ? , ? , ? , ? , ? , ?)";
        $stmt = $this->db->prepare($query);

        $stmt->bindValue(1, $c->getPseudo() );
        $stmt->bindValue(2, $c->getMdp() );
        $stmt->bindValue(3, $c->getNom() );
        $stmt->bindValue(4, $c->getPrenom() );
        $stmt->bindValue(5, $c->getDateBirth() );
        $stmt->bindValue(6, $c->getGenre() );

        if ( $stmt->execute()=== true ){

            return $c->getPseudo();
        };

        return null;
    }

    public function read($pseudo){

        $query = "SELECT pseudo, mdp, nom, prenom, dateBirth, genre 
                  FROM compte
                  where pseudo = '$pseudo'";

        $stmt = $this->db->prepare($query);

        $stmt->execute();

        $num = $stmt->rowCount();

        if($num>0)
        {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $pseudo = $row['pseudo'];
            $mdp = $row['mdp'];
            $nom = $row['nom'];
            $prenom = $row['prenom'];
            $dateBirth = $row['dateBirth'];
            $genre = $row['genre'];

            return new Compte( $pseudo, $mdp, $nom, $prenom, $dateBirth, $genre);
        }
        else
        {
            return null;
        }
    }
}