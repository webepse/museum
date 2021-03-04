<?php
    // tester si il y a le get id dans l'URL
    if(!isset($_GET['id']))
    {
        header("LOCATION:index.php");
    }
    require "connexion.php";

    // récup les données qui corresponde à l'id
    $id = htmlspecialchars($_GET['id']);


    $req = $bdd->prepare("SELECT * FROM oeuvres WHERE id=?");
    $req->execute([$id]);
    // tester s'il existe dans la bdd
    if(!$don = $req->fetch())
    {
        $req->closeCursor();
        header("LOCATION:404.php");
    }
    $req->closeCursor();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Museum</h1>
    <?php
        /* <?php echo $don['title'] ?> */
    ?>
    <h2><?= $don['category'] ?></h2>
    <h3>oeuvres: <?= $don['title'] ?></h3>
    <h4>Année de création : <?= $don['year'] ?></h4>
    <img src="upload/<?= $don['image'] ?>" alt="image de <?= $don['title'] ?>">

    <div class="description">
        <?= nl2br($don['description']) ?>
    </div>


    <a href="index.php">Retour</a>


  
</body>
</html>