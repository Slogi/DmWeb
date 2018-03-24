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

    public function __construct(View $v) {
        $this->view = $v;
    }

    public function mangaPage($id) {

        $manga = new Serie("titre", "auteur", "synopsis", "nbTomes", "mangas");
        //$manga = $this->mangadb->read($id);

        if ($manga === null) {
            echo 'aaa';
            /* La couleur n'existe pas en BD */
            //$this->v->makeUnknownColorPage();
        } else {
            echo 'bbb';
            /* La couleur existe, on prépare la page */
            $this->view->makeMangaPage($id, $manga);
        }

    }

}