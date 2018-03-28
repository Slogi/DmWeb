<h2>Inscrivez-vous</h2>
<label>Nom
    <input name="nomCompte" type="text" value="<?php echo self::htmlesc($builder->getData($nomCompteRef)) ?>">
    <?php   $err = $builder->getErrors($nomCompteRef);
            if ($err !== null)
            echo ' <span>'.$err.'</span>';
            ?>
</label>
<label>Prenom
    <input name="prenomCompte" type="text" value="<?php echo self::htmlesc($builder->getData($prenomCompteRef)) ?>">
    <?php   $err = $builder->getErrors($nomCompteRef);
    if ($err !== null)
        echo ' <span>'.$err.'</span>';
    ?>
</label>
<label>Date de Naissance
    <input name="dateBirthCompte" type="date" value="<?php echo self::htmlesc($builder->getData($dateBirthCompteRef)) ?>">
    <?php   $err = $builder->getErrors($nomCompteRef);
    if ($err !== null)
        echo ' <span>'.$err.'</span>';
    ?>
</label>
<label>Homme
    <input type="radio" checked="checked" id="homme" name="genreCompte" value="homme">
</label>
<label>Femme
    <input type="radio" id="femme" name="genreCompte" value="femme">
</label>
<label>Autre
    <input type="radio" name="genreCompte" id="autre">
</label>
<label>Pseudo
    <input name="pseudoCompte" type="text" value="<?php echo self::htmlesc($builder->getData($pseudoCompteRef)) ?>">
    <?php   $err = $builder->getErrors($nomCompteRef);
    if ($err !== null)
        echo ' <span>'.$err.'</span>';
    ?>
</label>
<label>Mot de passe
    <input name="mdpCompte" type="password" value="<?php echo self::htmlesc($builder->getData($mdpCompteRef)) ?>">
    <?php   $err = $builder->getErrors($nomCompteRef);
    if ($err !== null)
        echo ' <span>'.$err.'</span>';
    ?>
</label>
