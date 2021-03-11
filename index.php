<?php
    require "connexion.php";
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
    <div><a href="search.php">Rechercher une oeuvre</a></div>
    <h2>les oeuvres</h2>
    <?php
        $artworks = $bdd->query("SELECT * FROM oeuvres");
        while($donArt = $artworks->fetch())
        {
           echo "<div><a href='artwork.php?id=".$donArt['id']."'>".$donArt['title']."</a></div>";
        }
        $artworks->closeCursor();

    ?>
</body>
</html>