<?php
    session_start();
    // si la session n'existe pas, redirection vers formulaire
    if(!isset($_SESSION['login']))
    {
        header("LOCATION:index.php");
    }

    // tester si il y a le get id dans l'URL
    if(!isset($_GET['id']))
    {
        header("LOCATION:users.php");
    }
    require "../connexion.php";

    // récup les données qui corresponde à l'id
    $id = htmlspecialchars($_GET['id']);


    $req = $bdd->prepare("SELECT * FROM admin WHERE id=?");
    $req->execute([$id]);
    // tester s'il existe dans la bdd
    if(!$don = $req->fetch())
    {
        $req->closeCursor();
        header("LOCATION:users.php");
    }
    $req->closeCursor();

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
            <a href="users.php" class="btn btn-secondary my-1">Retour</a><br>
        </div>
    </div>
</div>
<div class="container">
    <h1 class="mb-3">Modifier un administrateur</h1>
      <form action="treatmentUpdateUser.php?id=<?= $don['id'] ?>" method="POST">
        <?php
            if(isset($_SESSION['errorAddUser']))
            {
                echo "<div class='alert alert-danger'>Veuillez remplir le formulaire correctement";
                // https://www.php.net/manual/fr/control-structures.foreach.php
                foreach($_SESSION['errorAddUser'] as $error)
                {
                   switch($error)
                   {
                       case 1 :
                        echo "<div>Login vide</div>";
                        break;
                       case 2 : 
                        echo "<div>Login déjà existant</div>";
                        break;
                       case 3 :
                        echo "<div>Mot de passe vide</div>";
                        break; 
                       case 4 : 
                        echo "<div>Mot de passe pas identique</div>";
                        break;
                       case 5 :
                         echo "<div>Email vide</div>";
                         break; 
                   }
                }
                echo "</div>";
            }

        ?>
        <div class="form-group">
            <label for="login">Login: </label>
            <input type="text" id="login" name="login" class="form-control" value="<?= $don['login'] ?>">
        </div>
        <div class="form-group">
            <label for="mail">E-Mail: </label>
            <input type="email" id="mail" name="email" class="form-control" value="<?= $don['email'] ?>">
        </div>
        <div class="form-group my-3">
            <input type="submit" value="Modifier" class="btn btn-primary">
        </div>
      </form>
</div>

</body>
</html>