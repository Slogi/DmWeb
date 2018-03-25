<?php
/**
 * Created by PhpStorm.
 * User: jujub
 * Date: 23/03/2018
 * Time: 18:12
 */
require_once("Router.php");

class View
{

    protected $router;
    protected $style;
    protected $title;
    protected $content;

    public function __construct(Router $router) {
        $this->router = $router;
        $this->style = "";
        $this->title = null;
        $this->content = null;
    }

    public function makeTestPage() {
        $this->title = "Test du titre";
        $this->content = "Test du content";
    }

    public function makeMangaPage(Serie $s, Manga $m) {
        $sTitre = self::htmlesc($s->getTitre());
        $sAuteur = self::htmlesc($s->getAuteur());
        $sSynopsis = self::htmlesc($s->getSynopsis());
        $mNumTome = self::htmlesc($m->getNumTome());
        $mResume = self::htmlesc($m->getResume());
        $mDateParu = self::htmlesc($m->getDateParu());

        include("templateManga.php");
    }

    public function makeSeriePage(Serie $s) {
        $sTitre = self::htmlesc($s->getTitre());
        $listeMangas = $s->getMangas();
        $content = $this->content;

        //$this->content .= "<ul>\n";
        foreach ($listeMangas as $m) {
            $content .= $this->listeMangas($m, $s);
        }
        //$this->content .= "</ul>\n";

        include("templateSerie.php");
    }

    public function makeUserPage($infoUser) {
        $content = $this->content;
        //var_dump($infoUser);
        //$content .= 'aaaaa';

        foreach ($infoUser as $serie){
            $content .= $this->listeSeries($serie);
            //echo $serie->getTitre();
            //echo $serie;

        }

        include("templateUser.php");

    }

    protected function listeSeries($serie) {
        //echo $serie->getTitre();
        $res = '<li><a href="" >';
        $res .= '<h3>'. $serie->getTitre().'</h3>';
        $res .= '</a></li>'."\n";
        return $res;
    }



    protected function listeMangas($m, $s) {

        $sId = self::htmlesc($s->getIdSerie());
        $mNumTome = self::htmlesc($m->getNumTome());

        $res = '<li><a href="'.$this->router->mangaPage($sId, $mNumTome).'" >';
        $res .= '<h3>'. $s->getTitre().' Tome '. $m->getNumTome().'</h3>';
        $res .= '</a></li>'."\n";
        return $res;
    }

    public function makeUnknownActionPage() {
        include("template404.php");
    }

    public function render() {
        if ($this->title === null || $this->content === null) {
            //$this->makeUnexpectedErrorPage();
        }
        $title = $this->title;
        $content = $this->content;

        include("templateTest.php");
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