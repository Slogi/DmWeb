<?php


class Manga
{

    protected $numTome;
    protected $resume;
    protected $dateParu;

    public function __construct( $numTome, $resume, $dateParu)
    {
        $this->numTome = $numTome;
        $this->resume = $resume;
        $this->dateParu = $dateParu;
    }

    public function getNumTome(){
        return $this->numTome;
    }

    public function getResume(){
        return $this->resume;
    }

    public function getDateParu(){
        return $this->dateParu;
    }

    public function setDefaultDateParu(){
        $this->dateParu = null;
    }


}