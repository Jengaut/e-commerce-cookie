<?php
// Modifie la valeur d'une option de configuration du fichier php.ini
ini_set("display_error", 1);
// Rapporte toutes les erreurs ( https://www.php.net/manual/fr/function.error-reporting.php )
error_reporting(E_ALL);

$message = '';

// ADD TO CART
if (isset($_POST['add_to_cart'])) {
    // Si le cookie existe
    if (isset($_COOKIE['shopping_cart'])) {
        // on recupere les infos du cookie
        $cookie_data = $_COOKIE['shopping_cart'];
        // on decode les infos du cookie
        $cart_data = json_decode($cookie_data, true);
    } else {
        // si non, on cree un tableau cart_data
        $cart_data = array();
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
    } else {
        // On cree un tableau avec les informations du produit
        $item_array = array(
            'hidden_id' => $_POST['hidden_id'],
            'hidden_name' => $_POST['hidden_name'],
            'hidden_price' => $_POST['hidden_price'],
            'quantity' => $_POST['quantity']
        );
        // on ajoute l'item dans le panier
        $cart_data[] = $item_array;
    }
    // on encode les items en JSON
    $item_data = json_encode($cart_data);
    // On cree un cookie avec l'info sous forme de JSON pour 30 jours
    setcookie('shopping_cart', $item_data, time() + (86400 * 30));
    // On redirige l'utilisateur
    header("location:index.php?success=1");
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
            header("location:index.php?remove=1");
        }
    }
}


// MSG FOR ADD TO CART
if (isset($_GET['success'])) {
    $message = '
    <div>
        le produit a ete ajouter avec succes
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

        <heather>
            <div id="img">
                <img src="images/logo.png" width="200" height="200" alt="Logo" />
            </div>
            <h1>Juste pour vos yeux!</h1>
        </heather>

        <main>
            <div class="produits">
                <div class="col-md-4">
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <div class="product">
                            <img src="images/1.png" alt="">
                            <h4>Lunette tendance d'automne</h4>
                            <h4>40$</h4>
                            <input type="number" name="quantity" value="1">
                            <input type="hidden" name="hidden_name" value="Lunette tendance d'automne">
                            <input type="hidden" name="hidden_price" value="40">
                            <input type="hidden" name="hidden_id" value="1">
                            <input type="submit" value="Ajouter au panier" name="add_to_cart">
                        </div>
                    </form>
                </div>

                <div class="col-md-4">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <div class="product">
                            <img src="images/5.png" alt="" width="250">
                            <h4>Lunette tendance printemps</h4>
                            <h4>50$</h4>
                            <input type="number" name="quantity" value="1">
                            <input type="hidden" name="hidden_name" value="Lunette tendance printemps">
                            <input type="hidden" name="hidden_price" value="50">
                            <input type="hidden" name="hidden_id" value="2">
                            <input type="submit" value="Ajouter au panier" name="add_to_cart">
                        </div>
                    </form>
                </div>
                <div class="col-md-4">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <div class="product">
                            <img src="images/3.png" alt="" width="250">
                            <h4>Lunette tendance hiver</h4>
                            <h4>100$</h4>
                            <input type="number" name="quantity" value="1">
                            <input type="hidden" name="hidden_name" value="Lunette tendance hiver">
                            <input type="hidden" name="hidden_price" value="100">
                            <input type="hidden" name="hidden_id" value="3">
                            <input type="submit" value="Ajouter au panier" name="add_to_cart">
                        </div>
                    </form>
                </div>
                <div class="col-md-4">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <div class="product">
                            <img src="images/4.png" alt="" width="250">
                            <h4>Lunette tendance ete</h4>
                            <h4>1000$</h4>
                            <input type="number" name="quantity" value="1">
                            <input type="hidden" name="hidden_name" value="Lunette tendance ete">
                            <input type="hidden" name="hidden_price" value="1000">
                            <input type="hidden" name="hidden_id" value="4">
                            <input type="submit" value="Ajouter au panier" name="add_to_cart">
                        </div>
                    </form>
                </div>
                <div class="col-md-4">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <div class="product">
                            <img src="images/2.png" alt="" width="250">
                            <h4>Lunette tendance pluie</h4>
                            <h4>10$</h4>
                            <input type="number" name="quantity" value="1">
                            <input type="hidden" name="hidden_name" value="Lunette tendance pluie">
                            <input type="hidden" name="hidden_price" value="10">
                            <input type="hidden" name="hidden_id" value="5">
                            <input type="submit" value="Ajouter au panier" name="add_to_cart">
                        </div>
                    </form>
                </div>
                <div class="col-md-4">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <div class="product">
                            <img src="images/6.png" alt="" width="250">
                            <h4>Lunette tendance soleil</h4>
                            <h4>360$</h4>
                            <input type="number" name="quantity" value="1">
                            <input type="hidden" name="hidden_name" value="Lunette tendance soleil">
                            <input type="hidden" name="hidden_price" value="360">
                            <input type="hidden" name="hidden_id" value="6">
                            <input type="submit" value="Ajouter au panier" name="add_to_cart">
                        </div>
                    </form>
                </div>
            </div>

            <div style="clear:both" class="commande">
                <br />
                <h3>Vos produit ajouté</h3>
                <table>
                    <tr>
                        <td width="40%">Nom</td>
                        <td width="20%">Quantite</td>
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
                                <td><?php echo $v["quantity"]; ?></td>
                                <td><a href="index.php?action=delete&id=<?php echo $v["hidden_id"]; ?>" class="effacer">Effacer</a></td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </table>
            </div>

            <div class="panier">
                <ul>
                    <li><a href="panier.php" class="button"><img alt="panier" src="images/panier.png"></a></li>
                </ul>
            </div>

            <div class="message">
                <?php
                if (isset($_COOKIE['shopping_cart'])) {
                    //    print_r($_COOKIE['shopping_cart']);
                }
                // SHOW MSG
                echo $message;
                ?>
            </div>

        </main>

        <footer>
            <div class="contact">
                <h3>Besoin d'aide, contactez moi</h3>
            </div>
            <div class="page">
                <div class="info-page">
                    <div class="image-cropper">
                        <img src="images/moi.jpg" alt="Verdun" class="moi" />
                    </div>
                    <div class="info">
                        <p>jennifer@hotmail.com</p>
                        <p>Tel: 444-444-4444</p>
                        <p>777 rue Alfonce, Verdun QC<br>
                            H1H 1H1</p>
                    </div>

                    <div class="social">
                        <a href="#" class="button"><img alt="fb" src="images/fb.png" /></a>
                        <a href="#" class="button"><img alt="insta" src="images/insta.png" /></a>
                    </div>
                </div>

                <p>&#169; Tous droits réservés 2022, Jennifer</p>

            </div>

        </footer>
</body>

</html>