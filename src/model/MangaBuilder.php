<?php
/**
 * Created by PhpStorm.
 * User: Slogix
 * Date: 23/03/2018
 * Time: 17:57
 */

class MangaBuilder{

    protected $data;
    protected $errors;

    public function __construct($data=null) {
        if($data === null){
            $data = array(
                "numTome" => "",
                "resume" => "",
                "dateParu" => ""
            );
        }
        $this->data = $data;
        $this->errors = array();
    }

    public function isValidManga() {
        $this->errors = array();

        //$pattern = "'/[0-9]/'";

        if (!key_exists("numTome", $this->data) || $this->data["numTome"] === ""){
            $this->errors["numTome"] = "Vous devez entrer un numéro de tome";
        }

        else if (!preg_match("/^[0-9]{1,3}+$/",  $this->data["numTome"])){


            $this->errors["numTome"] = "Nombres autorisés : 0 - 999 ". $this->data["numTome"] ;
        }


        if (mb_strlen($this->data["resume"], 'UTF-8') > 1000)
            $this->errors["resume"] = "Le résumé doit faire moins de 1000 caractères";

        // DATE ?
        return count($this->errors) === 0;
    }

    public function createManga() {
        if (!key_exists("numTome", $this->data)){
            echo 'exception';
            //throw new Exception("Missing fields for serie creation");
        }
        return new Manga($this->data["numTome"], $this->data["resume"], $this->data["dateParu"]);
    }

    public function getNumTomeRef() {
        return "numTome";
    }

    public function getResumeRef() {
        return "resume";
    }

    public function getDateParuRef() {
        return "dateParu";
    }

    public function getData($ref) {
        return key_exists($ref, $this->data)? $this->data[$ref]: '';
    }

    public function getErrors($ref) {
        return key_exists($ref, $this->errors)? $this->errors[$ref]: null;
    }

}