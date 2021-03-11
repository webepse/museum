<?php
session_start();

// vider SESSION errorAddUser
unset($_SESSION['errorAddUser']); // unset détruit une variable

// https://www.php.net/manual/fr/function.array-push.php
// $tab = array();
    //$tab = [1,2,3];
    //var_dump($tab);
    // $tab += 4;
    // $tab = $tab + 4
    // $tab = [1,2,3] + 4
    //array_push($tab, 4,5,6);
    //var_dump($tab);


// si la session n'existe pas, redirection vers formulaire
if(!isset($_SESSION['login']))
{
    header("LOCATION:index.php");
}
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
        // vérifier dans la base de données s'il existe déjà 
        $reqLog = $bdd->prepare("SELECT * FROM admin WHERE login=?");
        $reqLog->execute([$login]);
        if($donLog = $reqLog->fetch())
        {
           array_push($err,2); // ajoute au tableau 2
        }      
    }

    if(empty($_POST['password']))
    {
        array_push($err, 3);
    }else{ 
        if($_POST['password']!=$_POST['comfirmPassword'])
        {
            array_push($err, 4);
        }
        // crypter mot de passe
        $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
    }

    if(empty($_POST['email']))
    {
        array_push($err, 5);
    }else{
        $email = strip_tags($_POST['email']); // REGEX pour la sécuriser, attention pas sécurisé
    }

    if(empty($err))
    {
       $insert = $bdd->prepare("INSERT INTO admin(login,mdp,email) VALUES(:login,:pass,:email)");
       $insert->execute([
           ":login"=>$login,
           ":pass"=>$password,
           ":email"=>$email
       ]);
       $insert->closeCursor();
       header("LOCATION:users.php?add=success");
        
    }else{
        $_SESSION['errorAddUser'] = $err;
        header("LOCATION:addUser.php");
    }


  



}else{
    // le formulaire n'a pas été envoyé donc redirection vers users.php
    header("LOCATION:users.php");
}





?>
