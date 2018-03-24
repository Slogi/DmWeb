<?php
/**
 * Created by PhpStorm.
 * User: jujub
 * Date: 23/03/2018
 * Time: 19:01
 */

require_once ("model/Serie.php");

class Controller
{
    protected $view;
    protected $mangadb;
    protected $seriedb;

    public function __construct(View $v, MangaStorage $mangadb, SerieStorage $seriedb) {
        $this->view = $v;
        $this->mangadb = $mangadb;
        $this->seriedb = $seriedb;
    }

    public function mangaPage($serieId, $tomeId) {

        $infoSerie = $this->seriedb->read($serieId);
        $infoManga = $this->mangadb->read($tomeId);
        //$manga = new Serie("titre", "auteur", "synopsis", "nbTomes", "mangas");
        //$manga = $this->mangadb->read($id);

        if ($infoManga === null || $infoSerie === null) {
            echo 'aaa';
            /* La couleur n'existe pas en BD */
            //$this->v->makeUnknownColorPage();
        } else {
            echo 'bbb';
            /* La couleur existe, on prépare la page */
            $this->view->makeMangaPage($infoSerie, $infoManga);
        }

    }

}