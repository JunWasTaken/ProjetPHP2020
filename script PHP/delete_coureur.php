<?php
    include "pdo_oracle.php";
    include "util_chap11.php";
                
    $id_connection = "PPHP2A_04";
    $mdp_connection = "PPHP2A_04";
    $BDD = fabriquerChaineConnexPDO();
    $n_coureur = $_COOKIE['n_coureur'];
    $conn = OuvrirConnexionPDO($BDD,$id_connection,$mdp_connection);

    $sql = "DELETE FROM tdf_coureur where n_coureur = $n_coureur";

    $cur = preparerRequetePDO($conn, $sql);
    $res = majDonneesPrepareesPDO($cur);

    if ($res){
        echo "coureur supprimé ! ";
        echo "<button><a href='../Formulaire/Accueil.htm'>Retour Accueil</a></button>";
    }
?>