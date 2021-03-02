<?php
    session_start();
     // si la session n'existe pas, redirection vers formulaire de connexion
     if(!isset($_SESSION['login']))
     {
         header("LOCATION:index.php");
     }
     // tester si le formulaire est envoyé 
     if(isset($_POST['title']))
     {
        $err=0;
        var_dump($_POST);
        var_dump($_FILES['image']);

        //traitement des valeurs 
        if(empty($_POST['title']))
        {
            $err=1;
        }else{
            $title = htmlspecialchars($_POST['title']);
        }

        if(empty($_POST['category']))
        {
            $err=2;
        }else{
            $category = htmlspecialchars($_POST['category']);
        }

        if(empty($_POST['description']))
        {
            $err=3;
        }else{
            $description= htmlspecialchars($_POST['description']);
        }

        if(empty($_POST['year']))
        {
            $err=4;
        }else{
            $year = htmlspecialchars($_POST['year']);
        }


        if($err===0)
        {
            // traitement de l'image

            // insertion dans la bdd 
        }else{
            
            // renvoyer l'utilisateur vers le formulaire avec l'info de l'erreur
            header("LOCATION:addArtwork.php?error=".$err);
        }


     }else{
         header('LOCATION:addArtwork.php');
     }
     

?>