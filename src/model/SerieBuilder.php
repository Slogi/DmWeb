<?php
/**
 * Created by PhpStorm.
 * User: Slogix
 * Date: 23/03/2018
 * Time: 17:57
 */

class SerieBuilder{

    protected $data;
    protected $errors;

    public function __construct($data=null) {
        if($data === null){
            $data = array(
                "titreSerie" => "",
                "auteurSerie" => "",
                "resumeSerie" => ""
            );
        }
        $this->data = $data;
        $this->errors = array();
    }


    public function isValid() {
        $this->errors = array();

        if (!key_exists("titreSerie", $this->data) || $this->data["titreSerie"] === ""){
            $this->errors["titreSerie"] = "Vous devez entrer un titre";
        }
        else if (mb_strlen($this->data["titreSerie"], 'UTF-8') > 100)
            $this->errors["titreSerie"] = "Le titre doit faire moins de 100 caractères";

        if (!key_exists("auteurSerie", $this->data) || $this->data["auteurSerie"] === ""){
            $this->errors["auteurSerie"] = "Vous devez entrer un auteur";
        }
        else if (mb_strlen($this->data["auteurSerie"], 'UTF-8') > 100)
            $this->errors["auteurSerie"] = "Le nom de l'auteur doit faire moins de 100 caractères";

        if (mb_strlen($this->data["resumeSerie"], 'UTF-8') > 1000){
            $this->errors["auteurSerie"] = "Le résumé doit faire moins de 1000 caractères";
        }
        return count($this->errors) === 0;

    }

    public function createSerie() {
        if (!key_exists("titreSerie", $this->data) || !key_exists("auteurSerie", $this->data)){
            echo 'exception';
            //throw new Exception("Missing fields for serie creation");
        }
        return new Serie("0", $this->data["titreSerie"], $this->data["auteurSerie"], $this->data["resumeSerie"], null);
    }



    public function getTitreRef() {
        return "titreSerie";
    }

    public function getAuteurRef() {
        return "auteurSerie";
    }

    public function getResumeRef() {
        return "resumeSerie";
    }

    public function getData($ref) {
        return key_exists($ref, $this->data)? $this->data[$ref]: '';
    }

    public function getErrors($ref) {
        return key_exists($ref, $this->errors)? $this->errors[$ref]: null;
    }

    public static function htmlesc($str) {
        return htmlspecialchars($str,
            /* on échappe guillemets _et_ apostrophes : */
            ENT_QUOTES
            /* les séquences UTF-8 invalides sont
            * remplacées par le caractère �
            * au lieu de renvoyer la chaîne vide…) */
            | ENT_SUBSTITUTE
            /* on utilise les entités HTML5 (en particulier &apos;) */
            | ENT_HTML5,
            'UTF-8');
    }



}