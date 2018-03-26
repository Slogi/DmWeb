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

    public function __construct($pseudo, $mdp)
    {
        $this->pseudo = $pseudo;
        $this->mdp = $mdp;
    }

}