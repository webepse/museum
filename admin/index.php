<?php
    session_start();
    $erreur="";
    var_dump($_POST);
    /* vérifier si notre formulaire a été envoyé ou non */
    if(isset($_POST['username']))
    {
        /* tester si le login et mdp est vide */
        if(empty($_POST['username']) || empty($_POST['password']))
        {
            /* si vide je donne du contenu a ma variable erreur */
            $erreur="<div class='alert alert-danger'>Le login ou le mot de passe n'est pas correct</div>";
        }else{
            /* si pas vide, vérifier dans la bdd et protection de username */
            $username = htmlspecialchars($_POST['username']);
            require "../connexion.php"; // il est à l'extérieur du dossier admin
            // requête à la bdd
            $req = $bdd->prepare("SELECT * FROM admin WHERE login=?");
            $req->execute([$username]);
            // tester s'il y a une correspondance dans la bdd
            if($don = $req->fetch())
            {
                if(password_verify($_POST['password'],$don['mdp']))
                {
                    /* création d'une variable de session avec session_start() tout en haut */
                   $_SESSION['login']=$don['login'];
                   /* redirection vers dashboard.php */
                   header("LOCATION:dashboard.php");

                }else{
                    $erreur="<div class='alert alert-danger'>Le mot de passe n'est pas correct</div>";
                }
            }else{
                $erreur="<div class='alert alert-danger'>Le login n'est pas correct</div>";
            }
        }
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
    <title>Document</title>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-4 offset-4">
                <h1>Museum</h1>
                <h2>Connexion à l'administration du site</h2>
                <form action="index.php" method="POST">
                    <?php
                        echo $erreur;
                    ?>
                    <div class="form-group my-2">
                        <label for="login">Login: </label>
                        <input type="text" id="login" name="username" class="form-control">
                    </div>
                    <div class="form-group my-2">
                        <label for="mdp">Mot de passe: </label>
                        <input type="password" id="mdp" name="password" class="form-control">
                    </div>
                    <div class="form-group my-2">
                        <input type="submit" value="Connexion" class="btn btn-success">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>