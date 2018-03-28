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

    public function makeMangaPage($userPseudo, Serie $s, Manga $m) {
        $sTitre = self::htmlesc($s->getTitre());
        $sAuteur = self::htmlesc($s->getAuteur());
        $sSynopsis = self::htmlesc($s->getSynopsis());
        $mNumTome = self::htmlesc($m->getNumTome());
        $mResume = self::htmlesc($m->getResume());
        $mDateParu = self::htmlesc($m->getDateParu());

        include("templateManga.php");
    }

    public function makeSeriePage($userPseudo, Serie $s) {
        $sTitre = self::htmlesc($s->getTitre());
        $listeMangas = $s->getMangas();
        //var_dump($listeMangas);
        $content = $this->content;

        if($listeMangas !== null ){
            foreach ($listeMangas as $m) {
                $content .= $this->listeMangas($userPseudo, $m, $s);
            }
            include("templateSerie.php");
        }
        else  include("templateSerieSansMangas.php");
    }

    public function makeUserPage($userPseudo, $infoUser) {
        $content = $this->content;
        foreach ($infoUser as $serie){
            $content .= $this->listeSeries($userPseudo, $serie);
        }
        include("templateUser.php");
    }

    public function makeAllUsersWithSeriesPage($allUsersWithSeries){
        $content = $this->content;
        foreach ($allUsersWithSeries as $user => $series){

            echo '<ul>';
            echo '<li>';
            echo '<a href="'.$this->router->userPage($user).'">';
            echo '<h3>' . $user . '</h3>';
            echo '</a>';
            echo '<ul>';

            foreach ($series as $serie){
                $titreSerie = self::htmlesc($serie->getTitre());;
                $serieId = self::htmlesc($serie->getIdSerie());;
                echo '<li>';
                echo '<a href="'.$this->router->seriePage($user, $serieId).'">';
                echo '<h4>'.$titreSerie. '</h4>';
                echo '</a>';
                echo '</li>';
            }

            echo '</ul>';
            echo '</li>';
            echo '</ul>';
        }
    }

    public function makeUnknownActionPage() {
        include("template404.php");
    }

    public function makeSerieCreationPage(SerieBuilder $builder) {
        echo "makeSerieCreationPage";
        $this->title = "Ajouter votre série";
        //$s = '<form action="" method="POST">'."\n";
        $s = '<form action="'.$this->router->saveCreatedSerie().'" method="POST">'."\n";
        $s .= self::getFormFields($builder);
        $s .= "<button>Créer</button>\n";
        $s .= "</form>\n";
        $this->content = $s;

    }

    public function makeInscriptionPage(CompteBuilder $builder) {
        echo "makeInscriptionPage";
        $this->title = "Inscrivez-vous";
        $s = '<form action="'.$this->router->saveCreatedCompte().'" method="POST">'."\n";
        $s .= self::getFormInscrit($builder);
        $s .= "<button>Créer</button>\n";
        $s .= "</form>\n";
        $this->content = $s;

    }
    protected function getFormInscrit( CompteBuilder $builder){

        $pseudoCompteRef = $builder->getPseudoRef();
        $mdpCompteRef = $builder->getMdpRef();
        $nomCompteRef = $builder->getNomRef();
        $prenomCompteRef = $builder->getPrenomRef();
        $dateBirthCompteRef = $builder->getDateBirthRef();
        $genreCompteRef = $builder->getGenreRef();

        $s = "<h2>Inscrivez-vous</h2>";
        $s .="<label>Nom<input name=\"nomCompte\" type=\"text\" value=\"";
        $s .= self::htmlesc($builder->getData($nomCompteRef)).'">';
        $err = $builder->getErrors($nomCompteRef);
        if ($err !== null)
            $s .= ' <span>'.$err.'</span>';
        $s .= "</label><label>Prenom<input name=\"prenomCompte\" type=\"text\" value=\"";
        $s .= self::htmlesc($builder->getData($prenomCompteRef)) .'">';
        $err = $builder->getErrors($nomCompteRef);
        if ($err !== null)
            $s .= ' <span>'.$err.'</span>';

        $s .="</label><label>Date de Naissance<input name=\"dateBirthCompte\" type=\"date\" value=\"";
        $s .= self::htmlesc($builder->getData($dateBirthCompteRef)) .'">';
        $err = $builder->getErrors($nomCompteRef);
        if ($err !== null)
            $s .= ' <span>'.$err.'</span>';

        $s .="</label><label>Homme<input type=\"radio\" checked=\"checked\" id=\"homme\" name=\"genreCompte\" value=\"homme\">";
        $s .="</label><label>Femme<input type=\"radio\" id=\"femme\" name=\"genreCompte\" value=\"femme\">";
        $s .="</label><label>Autre<input type=\"radio\" name=\"genreCompte\" id=\"autre\" value=\"autre\">";
        $s .="</label><label>Pseudo<input name=\"pseudoCompte\" type=\"text\" value=\"";
        $s .= self::htmlesc($builder->getData($pseudoCompteRef)) .'">';
        $err = $builder->getErrors($nomCompteRef);
        if ($err !== null)
            $s .= ' <span>'.$err.'</span>';
        $s .="</label><label>Mot de passe<input name=\"mdpCompte\" type=\"password\" value=\"";
        $s .= self::htmlesc($builder->getData($mdpCompteRef)) .'">';
        $err = $builder->getErrors($nomCompteRef);
        if ($err !== null)
            $s .= ' <span>'.$err.'</span>';

        $s .="</label>";
        return $s;

    }
    public function makeConnexionForm(){

        $this->title = "Connectez-vous";
        $s = '<form action="'.$this->router->saveConnexion().'" method="POST">'."\n";
        $s .= self::getFormConn();
        $s .= "<button>Créer</button>\n";
        $s .="<p class=\"inscription\">Vous n'avez pas de compte, <a href=\"#\">inscrivez-vous</a></p>";
        $s .= "</form>\n";
        $this->content = $s;
    }
    protected function getFormConn(){

        $s = "<label for=\"pseudo\">Pseudo</label>";
        $s .="<input id=\"pseudo\" name=\"pseudo\" type=\"text\">";
        $s .="<label for=\"mdp\">Mot de passe</label>";
        $s .="<input id=\"mdp\" name=\"mdp\" type=\"password\">";
        return $s;
    }

    protected function getFormFields(SerieBuilder $builder) {
        echo "formulaire";
        $titreSerieRef = $builder->getTitreRef();
        $s = "";
        $s .= '<p><label>Titre de la Serie : <input type="text" name="'.$titreSerieRef.'" value="';
        $s .= self::htmlesc($builder->getData($titreSerieRef));
        //echo self::htmlesc($builder->getData($titreSerieRef));
        $s .= "\" />";
        $err = $builder->getErrors($titreSerieRef);
        if ($err !== null)
            $s .= ' <span>'.$err.'</span>';
        $s .="</label></p>\n";

        $auteurSerieRef = $builder->getAuteurRef();
        $s .= '<p><label>Auteur de la série : <input type="text" name="'.$auteurSerieRef.'" value="';
        $s .= self::htmlesc($builder->getData($auteurSerieRef));
        //echo self::htmlesc($builder->getData($auteurSerieRef));
        $s .= "\" />";
        $err = $builder->getErrors($auteurSerieRef);
        if ($err !== null)
            $s .= ' <span>'.$err.'</span>';
        $s .= '</label></p>'."\n";

        $resumeSerieRef = $builder->getResumeRef();
        $s .= '<p><label>Résumé de la série : <input type="text" name="'.$resumeSerieRef.'" value="';
        $s .= self::htmlesc($builder->getData($resumeSerieRef));
        //echo self::htmlesc($builder->getData($resumeSerieRef));
        $s .= "\" />";
        $err = $builder->getErrors($resumeSerieRef);
        if ($err !== null)
            $s .= ' <span>'.$err.'</span>';
        $s .= '</label></p>'."\n";
        return $s;



    }

    protected function listeSeries($userPseudo, $serie) {
        //$userPseudo = self::htmlesc($infoUser->get());
        $serieId = self::htmlesc($serie->getIdSerie());
        //echo $serie->getTitre();

        $res = '<li><a href="'.$this->router->seriePage($userPseudo, $serieId).'" >';
        $res .= '<h3>'. $serie->getTitre().'</h3>';
        $res .= '</a></li>'."\n";
        return $res;
    }

    protected function listeMangas($userPseudo, $m, $s) {
        $sId = self::htmlesc($s->getIdSerie());
        $mNumTome = self::htmlesc($m->getNumTome());

        $res = '<li><a href="'.$this->router->mangaPage($userPseudo, $sId, $mNumTome).'" >';
        $res .= '<h3>'. $s->getTitre().' Tome '. $m->getNumTome().'</h3>';
        $res .= '</a></li>'."\n";
        return $res;
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



    public function render() {
        echo $this->title;
        echo $this->content;
        if ($this->title === null || $this->content === null) {
            //$this->makeUnexpectedErrorPage();
        }
        $title = $this->title;
        $content = $this->content;

        //include("templateTest.php");
    }
}