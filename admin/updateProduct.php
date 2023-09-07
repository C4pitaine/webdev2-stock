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
      <h2>Modifier un produit</h2>
      <form action="treatmentUpdateProduct.php?id=<?= $don['id'] ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $don['id'] ?>">
        <div class="form-group my-3">1
            <label for="name">Nom: </label>
            <input type="text" name="name" id="name" value="<?= $don['name'] ?>" class="form-control">
        </div>
        <div class="form-group my-3">
            <label for="description">Description: </label>
            <textarea name="description" id="description" class="form-control"><?= $don['description'] ?></textarea>
        </div>
        <div class="form-group my-3">
            <label for="price">Prix: </label>
            <input type="number" name="price" id="price" step="0.01" value="<?= $don['price'] ?>" class="form-control">
        </div>
        <div class="form-group my-3">
            <label for="image">Image: </label>
            <div class="row">
                <div class="col-4">
                    <img src="../images/<?= $don['image'] ?>" class="img-fluid" alt="image de <?= $don['name'] ?>">
                </div>
            </div>
            <input type="file" name="image" id="image" class="form-control">
        </div>
        <div class="form-group my-3">
            <input type="submit" value="Modifier" class="btn btn-warning">
        </div>
      </form>
    </div>
</body>
</html>