<?php
    $id_connection = "ETU2_6";
    $mdp_connection = "ETU2_6";
    $BDD = "kiutoracle18.unicaen.fr:1521/info.kiutoracle18.unicaen.fr";

    include ('./pdo_oracle.php');
    include ('./util_chap11.php');

    function lecture_donnees(){
        $conn = OuvrirConnexionPDO($BDD,$id_connection,$mdp_connection);
        $sql = "SELECT * FROM TDF_COUREUR";
        LireDonneesPDO1($conn,$sql,$tab);
        AfficherDonnee2($tab);
    }
?>