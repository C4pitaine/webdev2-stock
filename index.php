<?php
    require "connexion.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Stock</title>
</head>
<body>
    <?php
        $req = $bdd->query("SELECT * FROM products ORDER BY id DESC LIMIT 0,3");
        while($don = $req->fetch())
        {
            echo "<a href='product.php?id=".$don['id']."' class='product'>";
                echo "<div>".$don['name']."</div>";
                echo "<img src='images/".$don['image']."' alt='image de ".$don['name']."'>";
            echo "</a>";    
        }
        $req->closeCursor();
    ?>
    <a href="products.php">Voir tous les produits</a>
</body>
</html>