<?php
    if(isset($_GET['id']))
    {
        $id = htmlspecialchars($_GET['id']);
    }else{
        header("LOCATION:index.php");
    }
    
    require "connexion.php";
    $req = $bdd->prepare("SELECT * FROM products WHERE id=?");
    $req->execute([$id]);
    $don = $req->fetch();
    if(!$don)
    {
        $req->closeCursor();
        header("LOCATION:index.php");
    }
    $req->closeCursor();

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Stock - <?= $don['name'] ?></title>
</head>
<body>
    <h1><?= $don['name'] ?></h1>
    <div><?= $don['price'] ?>€</div>
    <div><?= nl2br($don['description']) ?></div>
    <img src="images/<?= $don['image'] ?>" alt="image de <?= $don['name'] ?>">
</body>
</html>