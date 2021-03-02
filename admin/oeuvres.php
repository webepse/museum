<?php
    session_start();
    // si la session n'existe pas, redirection vers formulaire
    if(!isset($_SESSION['login']))
    {
        header("LOCATION:index.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Administration</title>
</head>
<body>
<div class="container-fluid">
    <h1>Administration de Museum</h1>
    <h3>Bonjour <?php echo $_SESSION['login'] ?></h3>
    <a href="dashboard.php?deco=ok" class="btn btn-danger my-1">Déconnexion</a>
    <div class="row">
        <div class="col-4">
            <a href="dashboard.php" class="btn btn-secondary my-1">Retour</a><br>
            <a href="addArtwork.php" class="btn btn-success my-1">Ajouter une oeuvre</a>
        </div>
    </div>
</div>
<div class="container">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Titre</th>
                    <th>Catégorie</th>
                    <th>Année</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
</div>

</body>
</html>