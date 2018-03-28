<!DOCTYPE html>
<html lang="fr">
<head>
    <title><?php echo $this->title; ?></title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="<?php echo $this->style; ?>" />
    <link rel="stylesheet" href="./skin/banner.css" />
</head>
<body>
<?php include "templateMenu.php"; ?>
<main>
    <h1> <?php echo $userPseudo ; ?></h1>
    <h2><?php echo $this->title ; ?></h2>
    <ul>
        <?php echo  $this->content ; ?>
    </ul>

</main>

</body>
</html>