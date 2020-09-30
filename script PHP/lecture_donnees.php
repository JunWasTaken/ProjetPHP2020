<?php
    include "pdo_oracle.php";
    include "util_chap11.php";
                
    $id_connection = "PPHP2A_04";
    $mdp_connection = "PPHP2A_04";
    $BDD = fabriquerChaineConnexPDO();

    if (empty($_POST['nom']) && empty($_POST['prénom'])){
        include ("../Formulaire/modifCoureur.htm");
        echo "Le nom n'est pas saisi";
    }else{
        $nom = $_POST['nom'];
        $prénom = $_POST['prénom'];
        lecture_donnees($id_connection, $mdp_connection, $BDD, $nom, $prénom);
    }

    function lecture_donnees($id_connection, $mdp_connection, $BDD, $nom, $prénom){
        $conn = OuvrirConnexionPDO($BDD,$id_connection,$mdp_connection);
        $sql = "SELECT * FROM tdf_coureur where nom = $nom && prenom = $prénom";
        $nb = LireDonneesPDO1($conn,$sql,$tab);
        AfficherDonnee2($tab);
    }
?>