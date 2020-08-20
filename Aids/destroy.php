<a href='index.php'> Löschen</a>
<?php 
    session_start();
    session_destroy();
    header("Location: index.php"); 
?>