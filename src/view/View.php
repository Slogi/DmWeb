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
        $sId = self::htmlesc($s->getIdSerie());
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
                echo $titreSerie;
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

    public function makeUnknownMangaPage() {
        $this->title = "Erreur";
        $this->content = "Le manga demandé n'existe pas.";
    }

    public function makeSerieCreationPage(SerieBuilder $builder) {
        $this->title = "Ajouter votre série";
        //$s = '<form action="" method="POST">'."\n";
        $s = '<form action="'.$this->router->saveCreatedSerie().'" method="POST">'."\n";
        $s .= self::getFormFieldsSerie($builder);
        $s .= "<button>Créer une série</button>\n";
        $s .= "</form>\n";
        $this->content = $s;

    }


    public function makeInscriptionPage(CompteBuilder $builder)
    {
        echo "makeInscriptionPage";
        $this->title = "Inscrivez-vous";
        $s = '<form action="' . $this->router->saveCreatedCompte() . '" method="POST">' . "\n";
        $s .= self::getFormInscrit($builder);
    }
    public function makeMangaCreationPage(MangaBuilder $builder, $idSerie) {
        $this->title = "Ajouter votre Manga";
        $s = '<form action="'.$this->router->saveCreatedManga().'" method="POST">'."\n";
        $s .= self::getFormFieldsManga($builder, $idSerie);
        $s .= "<button>Créer un manga</button>\n";

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

    protected function getFormFields(SerieBuilder $builder)
    {
        echo "formulaire";
    }


    protected function getFormFieldsManga(MangaBuilder $builder, $idSerie) {
        $numTomeRef = $builder->getNumTomeRef();
        $s = "";
        $s .= '<p><label>Numéro du tome : <input type="text" name="'.$numTomeRef.'" value="';
        $s .= self::htmlesc($builder->getData($numTomeRef));
        $s .= "\" />";
        $err = $builder->getErrors($numTomeRef);
        if ($err !== null)
            $s .= ' <span>'.$err.'</span>';
        $s .="</label></p>\n";

        $resumeRef = $builder->getResumeRef();
        $s .= '<label>Résumé : </label></br><textarea name="'.$resumeRef.'" rows="4" cols="50">'.self::htmlesc($builder->getData($resumeRef)).'</textarea></br></br>';
        //$s .= self::htmlesc($builder->getData($resumeRef));
        //echo self::htmlesc($builder->getData($titreSerieRef));

        $err = $builder->getErrors($resumeRef);
        if ($err !== null)
            $s .= ' <span>'.$err.'</span>';
        $s .="</label></p>\n";

        $dateParuRef = $builder->getDateParuRef();
        $s .= '<p><label>Date de parution : <input type="date" name="'.$dateParuRef.'" ';
        $err = $builder->getErrors($dateParuRef);
        if ($err !== null)
            $s .= ' <span>'.$err.'</span>';
        $s .="</label></p>\n";

        $s .= "<input type=\"hidden\" name=\"idSerie\" value=\"$idSerie\" />";
        return $s;


    }

    protected function getFormFieldsSerie(SerieBuilder $builder) {

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
        $s .= '<label>Résumé : </label></br><textarea name="'.$resumeSerieRef.'" rows="4" cols="50"></textarea></br></br>';
        $err = $builder->getErrors($resumeSerieRef);
        if ($err !== null)
            $s .= ' <span>'.$err.'</span>';
        $s .= ''."\n";

        return $s;
    }

    public function makeMangaDeletePage($userPseudo, $serieId, Manga $m) {
        $mNumTome = self::htmlesc($m->getNumTome());

        $this->title = "Suppression du tome $mNumTome";
        $this->content = "<p>Le tome « {$mNumTome} » va être supprimé.</p>\n";
        $this->content .= '<form action="'.$this->router->confirmMangaDelete($userPseudo, $serieId, $mNumTome).'" method="POST">'."\n";
        $this->content .= "<button>Confirmer</button>\n</form>\n";
    }

    public function makeMangaDeletedPage($userPseudo, $serieId) {
        $this->title = "Suppression effectuée";
        $this->content = "<p>Le tome a été correctement supprimé.</p>";
        $this->content .= '<a href="'.$this->router->seriePage($userPseudo, $serieId).'" >Revenir</a>';
    }

    public function makeMangaModifPage($userPseudo, $serieId, $mf, MangaBuilder $builder) {
        $this->title = "Modifier le tome";

        $this->content = '<form action="'.$this->router->updateModifiedManga($userPseudo, $serieId, $serieId).'" method="POST">'."\n";
        $this->content .= self::getFormFieldsManga($builder, $serieId);
        $this->content .= '<button>Modifier</button>'."\n";
        $this->content .= '</form>'."\n";
    }









    protected function listeSeries($userPseudo, $serie) {
        //$userPseudo = self::htmlesc($infoUser->get());
        $serieId = self::htmlesc($serie->getIdSerie());
        //echo $serie->getTitre();

        $res = '<li><a href="'.$this->router->seriePage($userPseudo, $serieId).'" >';
        $res .= $serie->getTitre();
        $res .= '</a></li>'."\n";
        return $res;
    }

    protected function listeMangas($userPseudo, $m, $s) {
        $sId = self::htmlesc($s->getIdSerie());
        $mNumTome = self::htmlesc($m->getNumTome());

        $res = '<li><a href="'.$this->router->mangaPage($userPseudo, $sId, $mNumTome).'" >';
        $res .= $s->getTitre().' Tome '. $m->getNumTome();
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