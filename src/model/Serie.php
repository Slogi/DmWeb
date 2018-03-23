<?php

class Serie
{
    protected $titre;
    protected $auteur;
    protected $synopsis;
    protected $mangas;

    public function __construct($titre, $auteur, $synopsis, $mangas)
    {
        $this->titre = $titre;
        $this->auteur = $auteur;
        $this->synopsis = $synopsis;
        $this->mangas = $mangas;
    }

    public function getTitre(){
        return $this->titre;
    }

    public function getAuteur(){
        return $this->auteur;
    }

    public function getSynopsis(){
        return $this->synopsis;
    }

    public function getMangas(){
        return $this->mangas;
    }
}