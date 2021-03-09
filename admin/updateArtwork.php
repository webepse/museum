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
          header("LOCATION:oeuvres.php");
      }
      require "../connexion.php";
  
      // récup les données qui corresponde à l'id
      $id = htmlspecialchars($_GET['id']);
  
  
      $req = $bdd->prepare("SELECT * FROM oeuvres WHERE id=?");
      $req->execute([$id]);
      // tester s'il existe dans la bdd
      if(!$don = $req->fetch())
      {
          $req->closeCursor();
          header("LOCATION:oeuvres.php");
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
            <a href="oeuvres.php" class="btn btn-secondary my-1">Retour</a><br>
        </div>
    </div>
</div>
<div class="container">
    <h1 class="mb-3">Modifier <?= $don['title'] ?></h1>
      <form action="treatmentUpdateArtwork.php?id=<?= $don['id'] ?>" method="POST" enctype="multipart/form-data">
        <?php
            if(isset($_GET['error']))
            {
                echo "<div class='alert alert-danger'>Veuillez remplir le formulaire correctement (code erreur : ".$_GET['error']." )</div>";
            }

            if(isset($_GET['imgerror']))
            {
                echo "<div class='alert alert-danger'>Le fichier envoyé a eu problème (code erreur : ".$_GET['imgerror']." )</div>";
            }

            if(isset($_GET['upload']))
            {
                echo "<div class='alert alert-danger'>Le transfert du fichier a eu problème</div>";
            }

        ?>
        <div class="form-group mb-3">
            <label for="title">Titre: </label>
            <input type="text" id="title" name="title" class="form-control" placeholder="Titre de l'oeuvre" value="<?= $don['title'] ?>" required>
        </div>
        <div class="form-group mb-3">
            <label for="category">Catégorie</label>
            <select name="category" id="category" class="form-control">
                <?php
                    if($don['category']=="peinture")
                    {
                        echo '<option value="peinture" selected>Peinture</option>';
                        echo '<option value="statue">Statue</option>';
                        echo '<option value="historique">Historique</option>';
                        echo '<option value="gravure">Gravure</option>';
                        echo '<option value="photo">Photo</option>';
                    } elseif($don['category']=="statue")
                    {
                        echo '<option value="peinture">Peinture</option>';
                        echo '<option value="statue" selected>Statue</option>';
                        echo '<option value="historique">Historique</option>';
                        echo '<option value="gravure">Gravure</option>';
                        echo '<option value="photo">Photo</option>';
                    } elseif($don['category']=="historique")
                    {
                        echo '<option value="peinture">Peinture</option>';
                        echo '<option value="statue" >Statue</option>';
                        echo '<option value="historique" selected>Historique</option>';
                        echo '<option value="gravure">Gravure</option>';
                        echo '<option value="photo">Photo</option>';
                    }
                    elseif($don['category']=="gravure")
                    {
                        echo '<option value="peinture">Peinture</option>';
                        echo '<option value="statue" >Statue</option>';
                        echo '<option value="historique" >Historique</option>';
                        echo '<option value="gravure" selected>Gravure</option>';
                        echo '<option value="photo">Photo</option>';
                    }else{
                        echo '<option value="peinture">Peinture</option>';
                        echo '<option value="statue" >Statue</option>';
                        echo '<option value="historique" >Historique</option>';
                        echo '<option value="gravure">Gravure</option>';
                        echo '<option value="photo" selected>Photo</option>';
                    }
                ?>
            </select>
        </div>
        <div class="input-group mb-3">
            <input type="file" class="form-control" id="inputGroupFile02" name="image">
            <label class="input-group-text" for="inputGroupFile02">image</label>
        </div>
        <div class="form-group  mb-3">
            <label for="description">description</label>
            <textarea name="description" id="description" class="form-control"><?= $don['description'] ?></textarea>
        </div>
        <div class="form-group  mb-3">
            <label for="year">Année: </label>
            <input type="number" id="year" name="year" class="form-control" value="<?= $don['year'] ?>">
        </div>
        <div class="form-group">
            <input type="submit" value="Modifier" class="btn btn-warning">
        </div>
      </form>
</div>

</body>
</html>