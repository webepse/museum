<?php
session_start();

// vider SESSION errorAddUser
unset($_SESSION['errorAddUser']); // unset détruit une variable



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


// vérif si le formulaire a été envoyé 
if(isset($_POST['login']))
{
    require "../connexion.php";
    // initialisation des erreurs en tableau vide
    $err = [];
    if(empty($_POST['login']))
    {
        array_push($err,1); // ajoute au tableau 1
    }else{
        $login=htmlspecialchars($_POST['login']);
        // vérifier que l'utilisateur a changé le login
        if($login!=$don['login'])
        {
            // vérifier dans la base de données s'il existe déjà 
            $reqLog = $bdd->prepare("SELECT * FROM admin WHERE login=?");
            $reqLog->execute([$login]);
            if($donLog = $reqLog->fetch())
            {
               array_push($err,2); // ajoute au tableau 2
            }
            $reqLog->closeCursor();      
        }
    }

    if(empty($_POST['email']))
    {
        array_push($err, 5);
    }else{
        $email = strip_tags($_POST['email']); // REGEX pour la sécuriser, attention pas sécurisé
    }

    if(empty($err))
    {
       $update = $bdd->prepare("UPDATE admin SET login=:log, email=:mail WHERE id=:myid");
       $update->execute([
           ":log"=>$login,
           ":mail"=>$email,
           ":myid"=>$id
       ]);
       $update->closeCursor();
       header("LOCATION:users.php?update=success&id=".$id);
        
    }else{
        $_SESSION['errorAddUser'] = $err;
        header("LOCATION:updateUser.php?id=".$id);
    }


  



}else{
    // le formulaire n'a pas été envoyé donc redirection vers users.php
    header("LOCATION:users.php");
}





?>
