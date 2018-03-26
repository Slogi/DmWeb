<?php


class Compte
{
    protected $pseudo;
    protected $mdp;
    protected $statut;

    public function __construct($pseudo, $mdp)
    {
        $this->pseudo = $pseudo;
        $this->mdp = $mdp;
    }

}