<?php


class Compte
{
    protected $pseudo;
    protected $mdp;
    protected $nom;
    protected $prenom;
    protected $dateBirth;
    protected $genre;
    protected $statut;

    public function __construct($pseudo, $mdp, $nom, $prenom, $dateBirth, $genre)
    {
        $this->pseudo = $pseudo;
        $this->mdp = $mdp;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->dateBirth = $dateBirth;
        $this->genre = $genre;
    }

    public function getPseudo(){
        return $this->pseudo;
    }

    public function getMdp(){
        return $this->mdp;
    }

    public function getNom(){
        return $this->nom;
    }

    public function getPrenom(){
        return $this->prenom;
    }

    public function getDateBirth(){
        return $this->dateBirth;
    }

    public function getGenre(){
        return $this->genre;
    }
}