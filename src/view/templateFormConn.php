<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $this->title; ?></title>
    <link rel="stylesheet" href="<?php echo $this->style ?>" />
    <link rel="stylesheet" href="./skin/banner.css" />
</head>
<body>
<?php
include "templateMenu.php";?>
<main>
    <?php
    echo $this->content;
    ?>
</main>
</body>
</html>