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
        var_dump($_FILES);

        //traitement des valeurs 
        if(empty($_POST['title'])) //   if($_POST['title']=="")
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
            $dossier = "../upload/";
            $fichier = basename($_FILES["image"]["name"]);
            $tailleMax = 200000;
            $taille = filesize($_FILES['image']['tmp_name']);
            $extensions = ['.png', '.jpg', '.jpeg', '.gif', '.svg'];
            $extension = strrchr($_FILES['image']['name'],'.');

            /* tester l'extension du fichier en comparaison du tableau $extensions */
            /* in_array permet de savoir si le 1er paramètre se retrouve dans le 2ème paramètre qui doit être un tableau */
            if(!in_array($extension, $extensions))
            {
                $imageError = "wrong-extension";
            }

            if($taille > $tailleMax)
            {
                $imageError = "size";
            }

            /* si $imageError n'existe pas  */
            if(!isset($imageError))
            {
                // traitement et formatage du nom du fichier envoyé
                $fichier = strtr($fichier, 
                'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
                'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');

                // remplacer les caractères spéciaux autre que les lettres en - (REGEX)
                $fichier = preg_replace('/([^.a-z0-9]+)/i','-',$fichier);

                // traitement des fichiers doublons
                $fichiercpt = rand().$fichier;

                // déplacement du fichier temporaire dans le dossier 'upload' avec son nouveau nom 
                if(move_uploaded_file($_FILES['image']['tmp_name'], $dossier.$fichiercpt))
                {
                    // le fichier est dans le dossier
                    // insertion dans la base de données
                    require "../connexion.php";
                    $insert = $bdd->prepare("INSERT INTO oeuvres(title,category,image,description,year) VALUES(:title,:category,:image,:description,:year)");
                    $insert->execute([
                        ":title" => $title,
                        ":category" => $category,
                        ":image" => $fichiercpt,
                        ":description" => $description,
                        ":year" => $year
                    ]);
                    $insert->closeCursor();
                    header("LOCATION:oeuvres.php?add=success");
                }else{
                    header("LOCATION:addArtwork.php?upload=error");
                }


            }else{
                header("LOCATION:addArtwork.php?imgerror=".$imageError);
            }






            // insertion dans la bdd 
        }else{
            
            // renvoyer l'utilisateur vers le formulaire avec l'info de l'erreur
            header("LOCATION:addArtwork.php?error=".$err);
        }


     }else{
         header('LOCATION:addArtwork.php');
     }
     

?>