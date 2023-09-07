<?php
    session_start();
    if(!isset($_SESSION['login']))
    {
        header("LOCATION:index.php");
    }

    // vérif présence de l'id
    if(isset($_GET['id']))
    {
        $id=htmlspecialchars($_GET['id']);
    }else{
        header("LOCATION:products.php");
    }

    // vérifi si l'id est dans la bdd
    require "../connexion.php";
    $req = $bdd->prepare("SELECT * FROM products WHERE id=?");
    $req->execute([$id]);
    if(!$don = $req->fetch())
    {
        $req->closeCursor();
        header("LOCATION:products.php");
    }
    $req->closeCursor();

    // vérifier si on vient du formulaire
    if(isset($_POST['name']))
    {
       // traiter les informations (pas le fichier)
       $err = 0;
       if(empty($_POST['name']))
       {
           $err=1;
       }else{
           $name = htmlspecialchars($_POST['name']);
       }  

       if(empty($_POST['description']))
       {
           $err=2;
       }else{
           $description= htmlspecialchars($_POST['description']);
       }

       if(empty($_POST['price']))
       {
           $err=3;
       }else{
           $price = htmlspecialchars($_POST['price']);
       }

       if($err == 0)
       {
            if(empty($_FILES['image']['tmp_name']))
            {
                $update = $bdd->prepare("UPDATE products SET name=:name, description=:descri, price=:prix WHERE id=:myid");
                $update->execute([
                    ":name" => $name,
                    ":descri" => $description,
                    ":prix" => $price,
                    ":myid" => $id
                ]);
                $update->closeCursor();
                header("LOCATION:products.php?update=".$id);

            }else
            {
                // traiter le fichier 
                $dossier = '../images/';
                $fichier = basename($_FILES['image']['name']);
                $taille_maxi = 200000;
                $taille = filesize($_FILES['image']['tmp_name']);
                $extensions = ['.png', '.gif', '.jpg', '.jpeg'];
                $extension = strrchr($_FILES['image']['name'], '.');

                if(!in_array($extension, $extensions))
                {
                    $err=4;
                }
     
                if($taille>$taille_maxi)
                {
                    $err=5;
                }

                if($err == 0)
                {
                    $fichier = strtr($fichier,'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                    $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);

                    $fichiercpt = rand().$fichier;

                    if(move_uploaded_file($_FILES['image']['tmp_name'], $dossier.$fichiercpt))
                    {
                        // suppression du fichier
                        unlink("../images/".$don['image']);

                        $update = $bdd->prepare("UPDATE products SET name=:name, description=:descri, price=:prix, image=:img WHERE id=:myid");
                        $update->execute([
                            ":name" => $name,
                            ":descri" => $description,
                            ":prix" => $price,
                            ":img" => $fichiercpt,
                            ":myid" => $id
                        ]);
                        $update->closeCursor();
                        header("LOCATION:products.php?update=".$id);

                    }else{
                        header("LOCATION:updateProduct.php?id=".$id."&error=6");
                    }

                }else{
                    header("LOCATION:updateProduct.php?id=".$id."&error=".$err);
                } 
                
            }

       }else{
           header("LOCATION:updateProduct.php?id=".$id."&error=".$err);
       }
   



    }else{
       header("LOCATION:updateProduct.php?id=".$id);
    }