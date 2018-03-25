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

    public function mangaPage($userPseudo, $serieId, $tomeId) {

        //$infoUser = $this->seriedb->read($serieId);

        $infoSerie = $this->seriedb->read($serieId);
        $infoManga = $this->mangadb->read($serieId,$tomeId);
        //$manga = new Serie("titre", "auteur", "synopsis", "nbTomes", "mangas");
        //$manga = $this->mangadb->read($id);

        if ($infoManga === null || $infoSerie === null) {

            /* La couleur n'existe pas en BD */
            //$this->v->makeUnknownColorPage();
        } else {

            /* La couleur existe, on prépare la page */
            $this->view->makeMangaPage($userPseudo, $infoSerie, $infoManga);
        }

    }

    public function seriePage($userPseudo, $serieId) {
        $infoSerie = $this->seriedb->read($serieId);
        if ($infoSerie === null) {
            //ERREUR SERIE PAS EN BDD
        }
        else $this->view->makeSeriePage($userPseudo, $infoSerie);
    }

    public function userPage($userPseudo) {
        //echo $userPseudo;
        $infoUser = $this->seriedb->readAllUser($userPseudo);
        //var_dump($infoUser);

        if ($infoUser === null) {
            echo 'infoUser = null';
            //ERREUR SERIE PAS EN BDD
        }
        else {
            //var_dump($infoUser) ;
            $this->view->makeUserPage($userPseudo, $infoUser);

        }





    }

}