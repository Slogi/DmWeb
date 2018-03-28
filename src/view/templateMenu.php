<header>
    <div class="nav">
        <img src="./img/banner.png" />
        <ul>
            <li class="accueil"><a href="<?php echo $this->router->accueilPage()?>">Accueil</a></li>

            <?php if (key_exists('pseudo', $_SESSION)){ ?>
                <li class="creerSerie"><a href="<?php echo $this->router->creerSerie()?>">Créer Serie</a></li>
                <li class="profil"><a href="#">Mon Profil</a></li>
                <li class="deconnecter"><a href ="<?php echo $this->router->deconnecter()?>">Déconnecter</a></li>
            <?php }
            else { ?>
                <li class="inscript"><a href="<?php echo $this->router->inscriptionPage()?>">Inscription</a></li>
                <li class="connexion"><a href="<?php echo $this->router->connexionPage()?>">Connexion</a></li>
            <?php } ?>
            <li class="apropos"><a href="#">A propos</a></li>
        </ul>
    </div>
</header>
