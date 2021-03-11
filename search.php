<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="search.php" method="GET">
        <label for="rech">Rechercher: </label>
        <input type="text" id="rech" name="search" value="">
        <div>
            <input type="submit" value="Rechercher">
        </div>
    </form>

    <h3>Resultat de la recherche</h3>
    <?php 
        if(isset($_GET['search']))
        {
            $search=htmlspecialchars($_GET['search']);
            require "connexion.php";
            $req = $bdd->prepare("SELECT * FROM oeuvres WHERE title LIKE :search OR category LIKE :search");
            $req->execute([
                ":search" => "%".$search."%"
            ]);
            if($req->rowCount() > 0)
            {
                while($don = $req->fetch())
                {
                    echo "<div><a href='artwork.php?id=".$don['id']."'>".$don['title']."</a></div>";
                }
            }else{
                echo "<div>Auncun résultat pour : ".$search."</div>";
            }



        }else{
            echo "<div>Aucune recherche effectuée</div>";
        }



    ?>



</body>
</html>