<?php


require_once ("model/Serie.php");
require_once ("model/Compte.php");
require_once ("model/SerieBuilder.php");
require_once ("model/CompteBuilder.php");
require_once ("model/MangaBuilder.php");


class Controller
{
    protected $view;
    protected $mangadb;
    protected $seriedb;
    protected $comptedb;

    public function __construct(View $v, MangaStorage $mangadb, SerieStorage $seriedb, CompteStorage $comptedb) {
        $this->view = $v;
        $this->mangadb = $mangadb;
        $this->seriedb = $seriedb;
        $this->comptedb = $comptedb;
    }

    public function mangaPage($userPseudo, $serieId, $tomeId) {
        $infoSerie = $this->seriedb->read($serieId);
        $infoManga = $this->mangadb->read($serieId,$tomeId);

        if ($userPseudo === null || $infoManga === null || $infoSerie === null) {
            $this->view->makeUnknownActionPage();

        } else {

            /* La couleur existe, on prépare la page */
            $this->view->makeMangaPage($userPseudo, $infoSerie, $infoManga);
        }

    }

    public function seriePage($userPseudo, $serieId) {
        $infoSerie = $this->seriedb->read($serieId);
        if ($userPseudo === null || $infoSerie === null) {
            $this->view->makeUnknownActionPage();
            //ERREUR SERIE PAS EN BDD
        }
        else $this->view->makeSeriePage($userPseudo, $infoSerie);
    }

    public function userPage($userPseudo) {
        //echo $userPseudo;
        $infoUser = $this->seriedb->readAllUser($userPseudo);

        if ($userPseudo === null || $infoUser === null) {
            $this->view->makeUnknownActionPage();
            //ERREUR SERIE PAS EN BDD
        }
        else $this->view->makeUserPage($userPseudo, $infoUser);


    }

    public function allUsersWithSeriesPage() {
        $allUsersWithSeries = $this->seriedb->readAll();
        if ($allUsersWithSeries === null) {
            $this->view->makeUnknownActionPage();
            //ERREUR SERIE PAS EN BDD
        }
        else $this->view->makeAllUsersWithSeriesPage($allUsersWithSeries);
    }

    public function newSerie() {
        $sb = new SerieBuilder();
        $this->view->makeSerieCreationPage($sb);
    }

    public function saveNewSerie(array $data) {
        $sb = new SerieBuilder($data);
        //var_dump($data) ;

        if ($sb->isValidSerie()){
            $serie = $sb->createSerie();
            $serieId = $this->seriedb->create($serie, "user1");

            $this->view->makeSeriePage("user1", $serieId);
            //$this->newManga($serieId);

            //RENVOYER SUR LA PAGE D'AJOUR D'UN MANGA
            //$this->v->makeColorPage($colorId, $color);


        }
        else {

            $this->view->makeSerieCreationPage($sb);
        }
    }

    public function newManga($userPseudo,$idSerie) {
        if($idSerie === null){
            $this->view->makeUnknownActionPage();
        }
        else {
            $sb = new MangaBuilder();
            $this->view->makeMangaCreationPage($sb, $idSerie);
        }

    }

    public function saveNewManga(array $data) {
        $idSerie = $data['idSerie'];

            $mb = new MangaBuilder($data);
            if ($mb->isValidManga() && !$this->mangadb->read($idSerie, $data['numTome'])){
                $manga = $mb->createManga();
                if($manga->getDateParu() === ''){
                    $manga->setDefaultDateParu();
                }
                $mangaId = $this->mangadb->create($manga, $idSerie);
                $this->view->makeMangaCreatedPage($idSerie, $_SESSION['user']);
            }
            else {
                $this->view->makeMangaCreationPage($mb, $idSerie);
            }


    }

    public function saveNewCompte(array $data) {

        $cb = new CompteBuilder($data);

        if ($cb->isValid($this->comptedb)){
            $compte = $cb->createCompte();
            $pseudo = $this->comptedb->create($compte);
            $this->view->makeCompteCreatedPage();
        }
        else {
            $this->view->makeInscriptionPage($cb);
        }
    }

    public function newCompte() {
        $cb = new CompteBuilder();
        $this->view->makeInscriptionPage($cb);
    }

    public function connexion (){
        $this->view->makeConnexionForm();
    }

    public function saveConn(array $data) {

        $pseudo = $data['pseudo'];
        $mdp = $data['mdp'];
        $compte = $this->comptedb->checkAuth($pseudo, $mdp);

        if ( $compte != null ){

            $_SESSION['pseudo'] =  $compte->getPseudo();
            $_SESSION['nom'] =  $compte->getNom();
            $_SESSION['prenom'] =  $compte->getPrenom();
            $_SESSION['dateBirth'] =  $compte->getDateBirth();
            $_SESSION['genre'] = $compte->getGenre();
            $this->view->makeConnSucessPage();
        }
        else {
            $this->view->makeConnexionForm("Vos identifiants ne sont pas bons");

        }
    }

    public function deconnexion (){

        if (key_exists('pseudo', $_SESSION ) ){
            session_destroy();
            $this->view->deconnexionSucess();
        }
    }

    public function deleteManga($userPseudo, $serieId, $tomeId) {

        $manga = $this->mangadb->read($serieId, $tomeId);
        if ($manga === null) {

            $this->view->makeMangaDeletedErr($serieId, $userPseudo);

        } elseif ( $_SESSION['pseudo'] !== $this->mangadb->readPseudo($serieId, $tomeId) ){

            $this->view->makeMangaDeletedErr2($serieId, $userPseudo);

        } else {
            /* La couleur existe, on prépare la page */
            $this->view->makeMangaDeletePage($userPseudo, $serieId, $manga);
        }
    }

    public function confirmMangaDelete($serieId, $tomeId) {
        /* L'utilisateur confirme vouloir supprimer
        * la couleur. On essaie. */
        $ok = $this->mangadb->delete($serieId, $tomeId);
        if (!$ok) {
            /* La couleur n'existe pas en BD */
            $this->view->makeUnknownMangaPage();
        } else {
            /* Tout s'est bien passé */
            $this->view->makeMangaDeletedPage($_SESSION['pseudo'], $serieId);
        }
    }

    public function modifyManga( $serieId, $tomeId) {
        if ( $_SESSION['pseudo'] === $this->mangadb->readPseudo($serieId, $tomeId) ){
            $m = $this->mangadb->read($serieId, $tomeId);
            if ($m === null) {
                $this->view->makeMangaDeletedErr2($serieId, $_SESSION['pseudo']);
            } else {
                /* Extraction des données modifiables */
                $mf = MangaBuilder::buildFromColor($m);
                /* Préparation de la page de formulaire */
                $this->view->makeMangaModifPage($_SESSION['pseudo'], $serieId, $tomeId, $mf);
            }
        }
    }

    public function saveMangaModifications($userPseudo,$serieId,$tomeId, array $data) {
        /* On récupère en BD la couleur à modifier */
        $manga = $this->mangadb->read($serieId, $tomeId);

        if ($manga === null) {
            // La couleur n'existe pas en BD
            $this->view->makeUnknownMangaPage();
        } else {

            $mf = new MangaBuilder($data);
            // Validation des données
            if ($mf->isValidManga()) {
                // Modification de la couleur
                $mf->updateManga($manga);
                // On essaie de mettre à jour en BD.
                //Normalement ça devrait marcher (on vient de
                //récupérer la couleur).

                echo 'date : '.$manga->getDateParu();
                //$ok = $this->mangadb->update($serieId, $manga);
                //if (!$ok)
                //throw new Exception("Identifier has disappeared?!");
                // Préparation de la page de la couleur
                //$infoSerie = $this->seriedb->read($serieId);
                //$this->view->makeMangaPage($userPseudo, $infoSerie, $manga);
            }
            else {
                $this->view->makeMangaModifPage($userPseudo, $serieId, $tomeId, $mf);
            }
        }

    }

}