<?php

class Serie implements Serializable
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

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        // TODO: Implement serialize() method.
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        // TODO: Implement unserialize() method.
    }
}