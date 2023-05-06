<?php

$message = '';

if (isset($_POST['update'])) {
    // Si le cookie existe
    if (isset($_COOKIE['shopping_cart'])) {
        // on recupere les infos du cookie
        $cookie_data = $_COOKIE['shopping_cart'];
        // on decode les infos du cookie
        $cart_data = json_decode($cookie_data, true);
    }

    $item_list = array_column($cart_data, 'hidden_id');
    // var_dump($item_list); // Liste des ID dans le panier
    // var_dump($cart_data); // Liste des produits
    // die();

    // Si l'ID du produit est dans le tableau
    if (in_array($_POST["hidden_id"], $item_list)) {
        // On parcours le tableau
        foreach ($cart_data as $k => $v) {
            // Si l<ID du tableau est egal a l'ID du produit
            if ($cart_data[$k]["hidden_id"] == $_POST["hidden_id"]) {
                // on mets a jour la quantite du produit
                $cart_data[$k]["quantity"] = $cart_data[$k]["quantity"] + $_POST["quantity"];
            }
        }
    }

    // on encode les items en JSON
    $item_data = json_encode($cart_data);
    // On cree un cookie avec l'info sous forme de JSON pour 30 jours
    setcookie('shopping_cart', $item_data, time() + (86400 * 30));
    // On redirige l'utilisateur
    header("location:panier.php?update=1");
}


// DELETE ALL
if(isset($_GET["action"]) == "clear")
{
   setcookie("shopping_cart","", time() -3600);
   header("location:panier.php?clearAll=1");
}

// DELETE SPECIFIC PRODUCT [element2]
if(isset($_GET["action"]) == "delete")
{
    $cookie_data = stripslashes($_COOKIE['shopping_cart']);
    $cart_data = json_decode($cookie_data, true);
    foreach($cart_data as $k => $v) {
        if($cart_data[$k]["hidden_id"] == $_GET['id']) {
            unset($cart_data[$k]);
            $item_data = json_encode($cart_data);
            setcookie('shopping_cart', $item_data, time() + (86400 *30));
            header("location:panier.php?remove=1");
        }
    }
}

// MSG FOR UPDATE
if (isset($_GET['update'])) {
    $message = '
    <div>
        le panier a ete mise a jour :)
    </div>
    ';
}

// MSG FOR DELETE ALL
if (isset($_GET['clearAll'])) {
    $message = '
    <div>
        le panier a ete vider
    </div>
    ';
}

// MSG FOR DELETE PRODUCT
if (isset($_GET['remove'])) {
    $message = '
    <div>
        le produit est supprimé avec succes
    </div>
    ';
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CookieCart</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js" defer></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" defer></script>
    <link rel="stylesheet" href="style.css" media="screen">

</head>

<body>

    <div class="container">

        <div class="navigation">
            <ul>
                <li><a href="index.php">Acceuil</a></li>
            </ul>
        </div>

        <heather>
            <div id="img">
                <img src="images/logo.png" width="200" height="200" alt="Logo" />
            </div>
            <h1>Juste pour vos yeux!</h1>

        </heather>


        <div style="clear:both" class="total">
            <br />
            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=clear" class="vider">Vider le panier</a>
            <h3>Details de la commande</h3>
            <form method="post" action="panier.php">
                <table>
                    <tr>
                        <td width="30%">Nom</td>
                        <td width="12%">Quantite</td>
                        <td width="15%"></td>
                        <td width="20%">Prix</td>
                        <td width="20%">total</td>
                        <td width="20%">Action</td>
                    </tr>
                    <?php
                    if (isset($_COOKIE['shopping_cart'])) {
                        $total = 0;
                        $cookie_data = stripslashes($_COOKIE['shopping_cart']);
                        $cart_data = json_decode($cookie_data, true);
                        foreach ($cart_data as $k => $v) {

                    ?>
                            <tr>
                                <td><?php echo $v["hidden_name"]; ?></td>
                                <td><input type="number" name="quantity" min="1" value="<?php echo $v["quantity"]; ?>"></td>
                                <td><input type="submit" value="UPDATE" class="update" name="update"></td>
                                <td><?php echo $v["hidden_price"]; ?>$</td>
                                <td><?php echo number_format($v["quantity"] * $v["hidden_price"], 2) ?>$</td>
                                <td><a href="panier.php?action=delete&id=<?php echo $v["hidden_id"]; ?>" class="effacer">Effacer</a></td>
                            </tr>
                            
                    <?php
                        }
                    } else {
                        echo "<tr>Ton panier est vide</tr>";
                    }
                    ?>
                </table>

                <div class="message">
                <?php
                if (isset($_COOKIE['shopping_cart'])) {
                    //    print_r($_COOKIE['shopping_cart']);
                }
                // SHOW MSG
                echo $message;
                ?>
            </div>

            </form>
        </div>
    </div>
</body>

</html>