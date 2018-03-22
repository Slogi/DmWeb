<?php
/**
 * Created by PhpStorm.
 * User: Slogix
 * Date: 22/03/2018
 * Time: 18:01
 */

class Serie
{
    protected $titre;
    protected $auteur;
    protected $synopsis;
    protected $nbTomes;
    protected $mangas;

    public function __construct($titre, $auteur, $synopsis, $nbTomes, $mangas)
    {
        $this->titre = $titre;
        $this->auteur = $auteur;
        $this->synopsis = $synopsis;
        $this->nbTomes = $nbTomes;
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

    public function getNbTomes(){
        return $this->nbTomes;
    }

    public function getMangas(){
        return $this->mangas;
    }
}