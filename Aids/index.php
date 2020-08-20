<?php
    session_start();
    $connect = mysqli_connect("localhost" , "root", "", "test1");
    if(isset($_POST["add_to_cart"]))
    {
      if(isset($_SESSION["shopping_cart"]))
      {
        $item_array_id = array_column($_SESSION["shopping_cart"], "item_id");
        if(!in_array($_GET['id'], $item_array_id))
        {
          $count = count($_SESSION["shopping_cart"]);
          $item_array = array(
            'item_id'         => $_GET['id'],
            'item_name'       => $_POST['hidden_name'],
            'item_price'      => $_POST['hidden_price'],
            'item_quantity'   => $_POST['quantity']
          );
          $_SESSION["shopping_cart"][$count] = $item_array;
        }
        else
        {
          echo '<script>alert("Item already added")</script>';
          echo '<script>window.location="index.php"</script>';
        }
      }
      else
      {
        $item_array = array(
          'item_id'         => $_GET['id'],
          'item_name'       => $_POST['hidden_name'],
          'item_price'      => $_POST['hidden_price'],
          'item_quantity'   => $_POST['quantity']
        );
        $_SESSION["shopping_cart"][0] = $item_array;
      }
    }

    if(isset($_GET["action"]))
    {
      if($_GET["action"] == "delte")
      {
        foreach($_SESSION["shopping_cart"] as $keys => $values)
        {
          if($values["item_id"] == $_GET["id"])
          {
            unset($_SESSION["shopping_cart"]["keys"]);
            echo '<script>alert("Item Removed")</script>';
            echo '<script>window.location="index.php"</script>';
          }
        }
      }
    }
    if(isset($_GET["logout"])){
      session_start();
      session_destroy();
    }

?>



<!DOCTYPE html>
<html lang="en">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Document</title>
      <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
      <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script> -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    </head>
    <body>
        <br>
        <div class="container" style="width: 700px;">
          <h3 align="center">Shopping Cart</h3>
          <form method="GET">
            <div style="text-align: center">
              <h3><a href="test.php">Test</a></h3>
            </div>
          </form>
          <br>
          <!-- <h3><a href="destroy.php"> Löschen</a></h3>
          <input type="submit" name="logout" value="Löschen"> -->
            <?php
              $query = "SELECT * FROM products ORDER BY id ASC";
              $result = mysqli_query($connect, $query);
              if(mysqli_num_rows($result) > 0)
              {
                while($row = mysqli_fetch_array($result))
                  {
                    ?>

                      <div class="col-md-4">
                        <form method="post" action="index.php?action=add&id=<?php echo $row['id']; ?>">
                          <div style="border: 1px solid #333; background-color:#f1f1f1; border-radius:5px; padding:16px;" align="center">
                            <img src="<?=$row['bild'] ?>" width="163" heigh="200" class="img-responsive">
                              <h4 class="text-info"><?=$row['titel']?></h4>
                              <h4 class="text-danger"><?=$row['preis']?> €</h4>
                              <input type="text" name="quantity" class="form-control" value="1">
                              <input type="hidden" name="hidden_name" value="<? echo $row['titel'] ?>">
                              <input type="hidden" name="hidden_price" value="<? echo $row['preis'] ?>">
                              <input type="submit" name="add_to_cart" style="margin-top: 5px;" class="btn btn-success" value="Add to Cart">
                              <!-- <input type="submit" name="add_to_cart" style="margin-top: 5px;" class="btn btn-success" value="Add to Cart" /> -->
                            </div>
                          </form>
                        </div>

                      <?php
                  }
              }
            ?>
            <div style="clear: both;"></div>
            <br>
            <h3>Order Deatils</h3>
            <div class="table-responsive">
              <table class="table table-borderd">
                <tr>
                  <th width="40%">Name</th>
                  <th width="10%">Menge</th>
                  <th width="20%">Preis</th>
                  <th width="15%">Gesamt</th>
                  <th width="5%">Action</th>
                </tr>
                <?php
                  if(!empty($_SESSION["shopping_cart"]))
                  {
                    $total = 0;
                    foreach($_SESSION["shopping_cart"] as $keys => $values)
                    {
                      ?>
                      <tr>
                        <td><?php echo $values["item_name"]; ?></td>
                        <td><?php echo $values["item_quantity"]; ?></td>
                        <td><?php echo $values["item_price"]; ?></td>
                        <td><?php echo number_format($values["item_quantity"] * $values["item_price"], 2); ?></td>
                        <td><a href="index.php?action=delete&id=<?php echo $values["item_id"]; ?>"> <span class="text-danger">Löschen</span></a></td>
                      </tr>
                      <?php
                      if(is_numeric($values["item_quantity"]) && is_numeric($values["item_price"])){
                        echo "true";
                      }
                      else
                      {
                        echo "false";
                      }
                      $total = $total + ($values["item_quantity"] * $values["item_price"]);
                    }
                    ?>
                    <td colspan="3" align="right">Gesamt</td>
                    <td align="right"> <?php echo number_format($total, 2); ?> €</td>
                    <td align="right"> <a href="destroy.php">Alles Löschen</a></td>
                    <td></td>
                    <?php
                  }
                 ?>
              </table>
            </div>
        </div>
<br>

    </body>
</html>
