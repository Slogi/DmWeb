<!DOCTYPE html>
<html lang="fr">
<head>
    <title><?php echo  $sTitre ; ?></title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="" />
</head>
<body>

<main>
    <h1><?php echo  $userPseudo ; ?></h1>
    <h1><?php echo  'Manga : ' . $sTitre . ' Tome : ' . $mNumTome ; ?></h1>
    <p><?php echo 'Appartient à la série : '  .$sTitre; ?></p>
    <p><?php echo 'Synopsis : ' . $sSynopsis; ?></p>
    <p><?php echo 'Auteur : ' .$sAuteur; ?></p>

    <h3><?php echo 'Résumé : '.$mResume; ?></h3>
    <h3><?php echo 'Date de parution : '.$mDateParu; ?></h3>

    <a href="<?php echo $this->router->mangaDeletePage($userPseudo, $sId, $mNumTome) ; ?>">Supprimer</a>






</main>

</body>
</html>