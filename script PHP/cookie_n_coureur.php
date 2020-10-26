<?php
    $n_coureur = $_GET["uid"];
    
    setcookie("n_coureur", $n_coureur);
    include ('../script PHP/modif_coureur.php');
?>