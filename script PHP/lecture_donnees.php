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
        $rech = $_POST['recherche'];
        switch ($rech) {
            case 'start':
                $sql = "SELECT n_coureur, nom, prenom, annee_naissance, annee_prem FROM tdf_coureur where nom like upper('$nom%')";
                break;
            case 'in':
                $sql = "SELECT n_coureur, nom, prenom, annee_naissance, annee_prem FROM tdf_coureur where nom like upper('%$nom%')";
                break;
            default:
                $sql = "SELECT n_coureur, nom, prenom, annee_naissance, annee_prem FROM tdf_coureur where nom like upper('$nom%')";
                break;
        }

        if (isset($_POST['tri'])){
            $tri = $_POST['tri'];
            switch ($tri){
                case 'alpha':
                    $sql = sortOrder($sql, "nom");
                break;
                case 'num_coureur':
                    $sql = sortOrder($sql, "n_coureur");
                break;
                case 'date_naissance':
                    $sql = sortOrder($sql, "annee_naissance");
                break;
                case 'prem_participation':
                    $sql = sortOrder($sql, "annee_prem");
                break;
                default : 
                    $sql = sortOrder($sql, "nom");
            }
        }

        include ("../Formulaire/rechercheCoureur.htm");
        lecture_donnees($sql, $conn);
    }

    function lecture_donnees($sql, $conn){
        $cur = preparerRequetePDO($conn, $sql);
        $res = lireDonneesPDOPreparee($cur,$tab);
        AfficherResultats($tab, $res);
    }

    function sortOrder($sql, $condition){
        return $sql." order by $condition";
    }
?>