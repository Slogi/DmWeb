<?php

require_once ("model/Compte.php");

class CompteBuilder
{

    protected $data;
    protected $errors;

    public function __construct($data=null) {
        if($data === null){
            $data = array(
                "pseudoCompte" => "",
                "mdpCompte" => "",
                "nomCompte" => "",
                "prenomCompte" => "",
                "dateBirthCompte" => "",
                "genreCompte" => ""
            );
        }
        $this->data = $data;
        $this->errors = array();
    }

    public function isValid($comptedb) {


        if (mb_strlen($this->data["nomCompte"], 'UTF-8') > 50)
        {
            $this->errors["nomCompte"] = "Le nom doit faire moins de 50 caractères";

        }

        if (mb_strlen($this->data["prenomCompte"], 'UTF-8') > 50)
        {
            $this->errors["prenomCompte"] = "Le nom doit faire moins de 50 caractères";

        }

        if ($this->data["dateBirthCompte"] >= date("Y-m-d"))
        {
            $this->errors["dateBirthCompte"] = "Votre date de naissance ne peut-être aujourd'hui ni après";

        }
        if (!key_exists("pseudoCompte", $this->data) || $this->data["pseudoCompte"] === "")
        {
            $this->errors["pseudoCompte"] = "Vous devez entrer un pseudo";
        }
        else if ($comptedb->read(self::htmlesc($this->data["pseudoCompte"])) !== null)
        {
            $this->errors["pseudoCompte"] = "Votre pseudo est déjà pris par un autre utilisateur";
        }
        else if (mb_strlen($this->data["pseudoCompte"], 'UTF-8') > 30)
        {
            $this->errors["pseudoCompte"] = "Votre pseudo doit faire moins de 30 caractères";
        }

        if (!key_exists("mdpCompte", $this->data) || $this->data["mdpCompte"] === "")
        {
            $this->errors["mdpCompte"] = "Vous devez entrer un mdp";
        }
        else if (mb_strlen($this->data["mdpCompte"], 'UTF-8') > 50)
        {
            $this->errors["mdpCompte"] = "Votre nom doit faire moins de 50 caractères";

        }
        return count($this->errors) === 0;

    }

    public function createCompte() {
        if (!key_exists("pseudoCompte", $this->data) || !key_exists("mdpCompte", $this->data)){
            throw new Exception("Missing fields for compte creation");
        }
        return new Compte( $this->data["pseudoCompte"], password_hash($this->data["mdpCompte"], PASSWORD_BCRYPT),
            $this->data["nomCompte"], $this->data['prenomCompte'], $this->data['dateBirthCompte'], $this->data['genreCompte']);
    }

    public function getPseudoRef() {
        return "pseudoCompte";
    }

    public function getMdpRef() {
        return "mdpCompte";
    }

    public function getNomRef() {
        return "nomCompte";
    }

    public function getPrenomRef() {
        return "prenomCompte";
    }

    public function getDateBirthRef() {
        return "dateBirthCompte";
    }

    public function getGenreRef() {
        return "genreCompte";
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