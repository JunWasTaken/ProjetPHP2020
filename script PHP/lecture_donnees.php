<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Formulaire modification coureur</title>
    </head>
    <body>
        <?php ?>
    </body>
</html>
<?php
    include "pdo_oracle.php";
    include "util_chap11.php";
                
    $id_connection = "PPHP2A_04";
    $mdp_connection = "PPHP2A_04";
    $BDD = fabriquerChaineConnexPDO();

    if (empty($_POST['nom']) && empty($_POST['prénom'])){
        include ("../Formulaire/rechercheCoureur.htm");
        echo "Le nom n'est pas saisi";
    }else{
        $nom = $_POST['nom'];
        $prénom = $_POST['prénom'];
        $conn = OuvrirConnexionPDO($BDD,$id_connection,$mdp_connection);
        include ("../Formulaire/rechercheCoureur.htm");
        lecture_donnees($nom, $prénom, $conn);
    }

    function lecture_donnees($nom, $prénom, $conn){
        $sql = "SELECT distinct * FROM tdf_coureur where nom == $nom && prénom == $prénom";
        $cur = preparerRequetePDO($conn, $sql);
        $res = lireDonneesPDOPreparee($cur,$tab);
        AfficherDonnee1($tab, $res);
    }
?>