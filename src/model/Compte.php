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

    public function __construct($pseudo, $nom, $prenom, $dateBirth, $genre)
    {
        $this->pseudo = $pseudo;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->dateBirth = $dateBirth;
        $this->genre = $genre;
    }

}