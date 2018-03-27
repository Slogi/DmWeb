<?php

require_once("model/CompteStorage.php");


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
                    return new Compte($row['pseudo'], $row['nom'], $row['prenom'], $row['dateBirth'], $row['genre']);
                }
            }
            return null;
        }
        else
        {
            return null;
        }
    }
}