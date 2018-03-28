<?php

class Serie
{
    protected $idSerie;
    protected $titre;
    protected $auteur;
    protected $synopsis;
    protected $mangas;

    public function __construct($idSerie, $titre, $auteur, $synopsis, $mangas)
    {
        $this->idSerie = $idSerie;
        $this->titre = $titre;
        $this->auteur = $auteur;
        $this->synopsis = $synopsis;
        $this->mangas = $mangas;
    }

    public function getIdSerie(){
        return $this->idSerie;
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