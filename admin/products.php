<?php
    session_start();
    if(!isset($_SESSION['login']))
    {
        header("LOCATION:index.php");
    }
    require "../connexion.php";

    // si on doit supprimer
    if(isset($_GET['delete']))
    {
        // protection
        $id = htmlspecialchars($_GET['delete']);
        // vérifier si le produit existe
        $search = $bdd->prepare("SELECT * FROM products WHERE id=?");
        $search->execute([$id]);
        if(!$donSearch = $search->fetch())
        {
            $search->closeCursor();
            header("LOCATION:products.php");
        }
        $search->closeCursor();
        // supprimer l'image dans le dossier
        unlink("../images/".$donSearch['image']);
        // supprimer l'entrée du produit dans la base de données
        $delete = $bdd->prepare("DELETE FROM products WHERE id=?");
        $delete->execute([$id]);
        $delete->closeCursor();
        // redirection vers le fichier avec un message flash
        header("LOCATION:products.php?deletesuccess=".$id);


    }


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Stock - Administration</title>
</head>
<body>
    <div class="container">
        <h2>Gestion des produits</h2>
        <a href="addProduct.php" class='btn btn-primary my-3'>Ajouter un produit</a>
        <a href="dashboard.php" class='btn btn-secondary m-3'>Retour</a>
        <?php
            // messages flash
            if(isset($_GET['addsuccess']))
            {
                echo "<div class='alert alert-success'>Vous avez bien ajouté un produit à la base de données</div>";
            }
            if(isset($_GET['update']))
            {
                echo "<div class='alert alert-warning'>Le produit n°".$_GET['update']." a bien été mis à jour</div>";
            }
            if(isset($_GET['deletesuccess']))
            {
                echo "<div class='alert alert-danger'>Le produit n°".$_GET['deletesuccess']." a bien été supprimé</div>";
            }
        ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nom</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                   
                    $req = $bdd->query("SELECT * FROM products");
                    while($don = $req->fetch())
                    {
                        echo "<tr>";
                            echo "<td>".$don['id']."</td>";
                            echo "<td>".$don['name']."</td>";
                            echo "<td>".$don['price']."</td>";
                            echo "<td>";
                                echo "<a href='updateProduct.php?id=".$don['id']."' class='btn btn-warning mx-2'>Modifier</a>";
                                echo "<a href='products.php?delete=".$don['id']."' class='btn btn-danger mx-2'>Supprimer</a>";
                            echo "</td>";
                        echo "</tr>";
                    }
                    $req->closeCursor();
                ?>
            </tbody>
        </table> 
    </div>
</body>
</html>