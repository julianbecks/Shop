
<div style="text-align: center">
<?php

session_start();

// $pdo = new PDO("mysql:host=localhost;dbname=test1", "root", "");

// $connect = mysqli_connect("localhost", "root", "", "test1");

echo "<br>";
$total = 0;
$pieces = 0;


if($_GET["vorname"] == ""){
    echo "<h3>Es wurde kein Name eingegeben oder der Einkaufswagen ist leer</h3>";
    echo '<form action="test.php"><input type="submit" value="Zurück"></form>';
}
else
{
    if(!isset($_SESSION["shopping_cart"])){
        echo "Nichts im Einkaufswagen";

    } 
    else 
    {
        for($i = 0; $i < sizeof($_SESSION["shopping_cart"]); $i++){
            $total = $total + ($_SESSION["shopping_cart"][$i]["item_quantity"] * $_SESSION["shopping_cart"][$i]["item_price"]);
            $pieces = $pieces + $_SESSION["shopping_cart"][$i]["item_quantity"];
            $temppreis = $_SESSION["shopping_cart"][$i]["item_price"];
            $tempmenge = $_SESSION["shopping_cart"][$i]["item_quantity"];
    
            $temp = $pdo->prepare("INSERT INTO test(Menge, Preis) VALUES(:Menge, :Preis)");
            // $temp->execute(array(":Menge" => $_SESSION["shopping_cart"][$i]["item_quantity"], ":Preis" => $_SESSION["shopping_cart"][$i]["item_price"]));
    
            //$sql = "INSERT INTO test(Menge, Preis) VALUES($tempmenge, $temppreis)";
            //mysqli_query($connect, $sql);
        }
        echo "<h3>Danke für den Einkauf " . $_GET["vorname"] . "!<br></h3>";

        if($pieces > 1){
            echo "<h3>Sie haben " . $pieces . " Bücher für " . $total . " € gekauft</h3>";
            echo '<form action="destroy2.php"><input type="submit" value="Zurück zu den Büchern"></form>';
        }
        else{
            echo "<h3>Sie haben " . $pieces . " Buch für " . $total . " € gekauft</h3>";
            echo '<form action="destroy2.php" method="GET"><input type="submit" value="Zurück zu den Büchern"><form>';
        }        
    }
}
