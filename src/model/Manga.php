<?php
/**
 * Created by PhpStorm.
 * User: Slogix
 * Date: 22/03/2018
 * Time: 17:54
 */

class Manga
{
    protected $couverture;
    protected $numTome;
    protected $resume;

    public function __construct($couverture, $numTome, $resume)
    {
        $this->couverture = $couverture;
        $this->numTome = $numTome;
        $this->resume = $resume;
    }

    public function getCouverture() {
        return $this->couverture;
    }

    public function getNumTome(){
        return $this->numTome;
    }

    public function getResume(){
        return $this->resume;
    }
}