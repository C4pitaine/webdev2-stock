<?php 
    require "connexion.php";
    $limit = 3;
    $reqCount = $bdd->query("SELECT * FROM products");
    $count = $reqCount->rowCount(); // permet de recup le nombre de produit via la req
    $reqCount->closeCursor();
    $nbPage = ceil($count/$limit); // arrondi au sup
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Stock - Products page</title>
</head>
<body>
    <div id="pagination">
        <?php
            if(isset($_GET['page']))
            {
                $pg = $_GET['page'];
            }else{
                $pg = 1;
            }

            // (1-1)*3=0
            // (2-1)*3=3
            // (3-1)*3=6
            $offset = ($pg-1)*$limit;

            if($pg>1)
            {
                echo "&nbsp;<a href='products.php?page=".($pg-1)."'> < </a>";
            }
            echo "Page ".$pg." ";
            if($pg!=$nbPage && $pg<$nbPage)
            {
                echo "&nbsp;<a href='products.php?page=".($pg+1)."'> > </a>";
            }


        ?>


    </div>


    <?php 
        $products = $bdd->prepare("SELECT * FROM products ORDER BY id DESC LIMIT :offset , :mylimit");
        $products->bindParam(':offset', $offset, PDO::PARAM_INT);
        $products->bindParam(':mylimit', $limit, PDO::PARAM_INT);
        $products->execute();
        while($donProd = $products->fetch())
        {
            echo "<div><a href='product.php?id=".$donProd['id']."'>".$donProd['name']."</a></div>";
        }
        $products->closeCursor();
    ?>
</body>
</html>