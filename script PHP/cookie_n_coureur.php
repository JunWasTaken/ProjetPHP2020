<?php
    $n_coureur = $_GET["uid"];

    setcookie("n_coureur", $n_coureur);
    include ('../Formulaire/modifCoureur.htm');
?>