<?php
    session_start();
     // si la session n'existe pas, redirection vers formulaire de connexion
     if(!isset($_SESSION['login']))
     {
         header("LOCATION:index.php");
     }

       // tester si il y a le get id dans l'URL
       if(!isset($_GET['id']))
       {
           header("LOCATION:oeuvres.php");
       }
     
       // récup les données qui corresponde à l'id
       $id = htmlspecialchars($_GET['id']);
       require "../connexion.php";
        $req = $bdd->prepare("SELECT * FROM oeuvres WHERE id=?");
        $req->execute([$id]);
        // tester s'il existe dans la bdd
        if(!$don = $req->fetch())
        {
            $req->closeCursor();
            header("LOCATION:oeuvres.php");
        }
        $req->closeCursor();


     // tester si le formulaire est envoyé 
     if(isset($_POST['title']))
     {
        $err=0;
        //var_dump($_POST);
        //var_dump($_FILES);

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
            if(empty($_FILES['image']['tmp_name']))
            {
                // gestion s'il y a PDF
                if(!empty($_FILES['pdf']['tmp_name']))
                {
                    $dossier = "../upload/";
                    $pdf = basename($_FILES["pdf"]["name"]);
                    $pdfTaille = filesize($_FILES['pdf']['tmp_name']);
                    $pdfExtension = strrchr($_FILES['pdf']['name'],'.');
                      /* tester l'extension du fichier en comparaison du tableau $extensions */
                    /* in_array permet de savoir si le 1er paramètre se retrouve dans le 2ème paramètre qui doit être un tableau */
                    if($pdfExtension!=".pdf")
                    {
                        $fileError = "pdf-wrong-extension";
                    }
    
                    if($pdftaille > $tailleMax)
                    {
                        $fileError = "pdf-size";
                    }

                    if(!isset($fileError))
                    {
                        $pdf = strtr($pdf, 
                        'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
                        'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
    
                        $pdf = preg_replace('/([^.a-z0-9]+)/i','-',$pdf);
    
                        $pdfcpt = rand().$pdf;

                        if(move_uploaded_file($_FILES['pdf']['tmp_name'], $dossier.$pdfcpt))
                        {

                            // test s'il y avait un pdf avant 
                            if(!empty($don['pdf']))
                            {
                                unlink("../upload/".$don['pdf']);
                            }
                            // modification avec pdf
                            $upload = $bdd->prepare("UPDATE oeuvres SET title=:title, category=:category,description=:description, year=:year, pdf=:pdf WHERE id=:myid");
                            $upload->execute([
                                ":title" => $title,
                                ":category" => $category,
                                ":description" => $description,
                                ":year" => $year,
                                ":myid" => $id,
                                ":pdf"=>$pdfcpt
                            ]);
                            $upload->closeCursor();
                            // redirection vers oeuvres.php avec message success 
                            header("LOCATION:oeuvres.php?update=success&id=".$id);
                        }
                        else{
                            header("LOCATION:updateArtwork.php?id=".$id."&upload=error");
                        }
                        
                    }else{
                        header("LOCATION:updateArtwork.php?id=".$id."&fileerror=".$fileError);
                    }

                }else{
                    // modification sans toucher au pdf
                    $upload = $bdd->prepare("UPDATE oeuvres SET title=:title, category=:category,description=:description, year=:year WHERE id=:myid");
                    $upload->execute([
                        ":title" => $title,
                        ":category" => $category,
                        ":description" => $description,
                        ":year" => $year,
                        ":myid" => $id
                    ]);
                    $upload->closeCursor();
                    // redirection vers oeuvres.php avec message success 
                    header("LOCATION:oeuvres.php?update=success&id=".$id);
                }


            }else{
                // traitement de la modification de l'image
            
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
                        $fileError = "wrong-extension";
                    }

                    if($taille > $tailleMax)
                    {
                        $fileError = "size";
                    }

                      // gestion du PDF 
                        if(!empty($_FILES['pdf']['tmp_name']))
                        {
                            $pdf = basename($_FILES["pdf"]["name"]);
                            $pdfTaille = filesize($_FILES['pdf']['tmp_name']);
                            $pdfExtension = strrchr($_FILES['pdf']['name'],'.');

                            /* tester l'extension du fichier en comparaison du tableau $extensions */
                            /* in_array permet de savoir si le 1er paramètre se retrouve dans le 2ème paramètre qui doit être un tableau */
                            if($pdfExtension!=".pdf")
                            {
                                $fileError = "pdf-wrong-extension";
                            }

                            if($pdftaille > $tailleMax)
                            {
                                $fileError = "pdf-size";
                            }
                        }

                    /* si $imageError n'existe pas  */
                    if(!isset($fileError))
                    {
                        // traitement et formatage du nom du fichier envoyé
                        $fichier = strtr($fichier, 
                        'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
                        'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');

                        // remplacer les caractères spéciaux autre que les lettres en - (REGEX)
                        $fichier = preg_replace('/([^.a-z0-9]+)/i','-',$fichier);

                        // traitement des fichiers doublons
                        $fichiercpt = rand().$fichier;

                        // gestion pdf
                        if(!empty($_FILES['pdf']['tmp_name']))
                        {
                            $pdf = strtr($pdf, 
                            'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
                            'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
        
                            $pdf = preg_replace('/([^.a-z0-9]+)/i','-',$pdf);
        
                            $pdfcpt = rand().$pdf;
                        }

                        // déplacement du fichier temporaire dans le dossier 'upload' avec son nouveau nom 
                        // attention avec cette méthode, il y a un risque d'image perdue si une erreur arrive lors du déplacement du fichier. 
                        if(move_uploaded_file($_FILES['image']['tmp_name'], $dossier.$fichiercpt))
                        {
                            // le fichier est dans le dossier
                            // aller chercher l'ancienne image et la mettre à la poubelle
                                unlink("../upload/".$don['image']);
                            // gestion pdf
                            if(empty($_FILES['pdf']['tmp_name']))
                            {

                                // faire un update des info
                                    $upload = $bdd->prepare("UPDATE oeuvres SET title=:title, category=:category, image=:image, description=:description, year=:year WHERE id=:myid");
                                    $upload->execute([
                                        ":title" => $title,
                                        ":category" => $category,
                                        ":image" => $fichiercpt,
                                        ":description" => $description,
                                        ":year" => $year,
                                        ":myid" => $id
                                    ]);
                                    $upload->closeCursor();
                                    // redirection vers oeuvres.php avec message success 
                                    header("LOCATION:oeuvres.php?update=success&id=".$id);
                            }else{

                                if(move_uploaded_file($_FILES['pdf']['tmp_name'], $dossier.$pdfcpt))
                                {
                                     // test s'il y avait un pdf avant 
                                    if(!empty($don['pdf']))
                                    {
                                        unlink("../upload/".$don['pdf']);
                                    }
                                    $upload = $bdd->prepare("UPDATE oeuvres SET title=:title, category=:category, image=:image, description=:description, year=:year, pdf=:pdf WHERE id=:myid");
                                    $upload->execute([
                                        ":title" => $title,
                                        ":category" => $category,
                                        ":image" => $fichiercpt,
                                        ":description" => $description,
                                        ":year" => $year,
                                        ":myid" => $id,
                                        ":pdf"=>$pdfcpt
                                    ]);
                                    $upload->closeCursor();
                                    // redirection vers oeuvres.php avec message success 
                                    header("LOCATION:oeuvres.php?update=success&id=".$id);

                                }else{
                                    header("LOCATION:updateArtwork.php?id=".$id."&upload=error");
                                }



                            }


                         
                        }else{
                            header("LOCATION:updateArtwork.php?id=".$id."&upload=error");
                        }
                    }else{
                        header("LOCATION:updateArtwork.php?id=".$id."&imgerror=".$imageError);
                    }
            }     
        }else{
            
            // renvoyer l'utilisateur vers le formulaire avec l'info de l'erreur
            header("LOCATION:updateArtwork.php?id=".$id."&error=".$err);
        }


     }else{
         header('LOCATION:updateArtwork.php?id='.$id);
     }
     

?>