<div style="text-align: center; margin-top: 50px">
    <?php

    session_start();

    $pdo = new PDO("mysql:host=localhost;dbname=meinshop", "root", "");

    $total = 0;
    $pieces = 0;
    $id = 0;


    if ($_GET["vorname"] == "") {
        echo "<h3>Es wurde kein Name eingegeben oder der Einkaufswagen ist leer</h3>";
        echo '<form action="test.php"><input type="submit" value="Zurück"></form>';
    } else {
        if (!isset($_SESSION["shopping_cart"])) {
            echo "Nichts im Einkaufswagen";
        } else {
            foreach ($_SESSION['shopping_cart'] as $values) {
                $total = $total + ($values['item_quantity'] * $values['item_price']);
                $pieces = $pieces + $values["item_quantity"];
            }
            
            $sql = $pdo->prepare("INSERT INTO orders(Name, Gesamtpreis) VALUES(?, ?)");
            $sql->execute([$_GET['vorname'], $total]);

            $sql = $pdo->prepare("SELECT MAX(OrderID) FROM orders");
            $sql->execute();
            $result = $sql->fetchAll();
            $id = $result[0]['MAX(OrderID)'];

            foreach ($_SESSION['shopping_cart'] as $values) {
                $temppreis = $values["item_price"] * $values['item_quantity'];
                $tempmenge = $values["item_quantity"];

                $sql = $pdo->prepare("INSERT INTO orderdetails(OrderID, ProductID, Menge, Preis) VALUES(?, ?, ?, ?)");
                $sql->execute([$id, $values['item_id'] , $values["item_quantity"], $values["item_price"]]);
            }

            echo "<h3>Danke für den Einkauf " . $_GET["vorname"] . "!<br></h3>";

            if ($pieces > 1) {
                echo "<h3>Sie haben " . $pieces . " Bücher für " . $total . " € gekauft</h3>";
                echo '<form action="destroy2.php"><input type="submit" value="Zurück zu den Büchern"></form>';
            } else {
                echo "<h3>Sie haben " . $pieces . " Buch für " . $total . " € gekauft</h3>";
                echo '<form action="destroy2.php" method="GET"><input type="submit" value="Zurück zu den Büchern"><form>';
            }
        }
    }
