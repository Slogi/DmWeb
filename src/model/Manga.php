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

    public function setResume($resume) {
        if (!self::isResumeValid($resume))
            throw new Exception("Invalid color name");
        $this->resume = $resume;
    }

    public function setDateParu($datePau) {
        /*if (!self::isDateParuValid($datePau))
            throw new Exception("Invalid hex");*/
        $this->datePau = $datePau;
    }

    public static function isResumeValid($resume) {
        return mb_strlen($resume, 'UTF-8') < 1000 || $resume === "";
    }

    /*
    public static function isDateParuValid($dateParu) {
        return mb_strlen($dateParu, 'UTF-8') < 1000 || $dateParu === "";
    }*/




}