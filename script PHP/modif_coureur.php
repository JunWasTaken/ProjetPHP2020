<?php
    include "pdo_oracle.php";
    include "util_chap11.php";
    //include "lecture_donnees.php";
                
    $id_connection = "PPHP2A_04";
    $mdp_connection = "PPHP2A_04";
    $BDD = fabriquerChaineConnexPDO();
    $n_coureur = $_COOKIE['n_coureur'];
    $conn = OuvrirConnexionPDO($BDD,$id_connection,$mdp_connection);

    $sql = "update tdf_coureur";
    $i = 0;

    if (!empty($_POST['nom'])){ //modifie le nom
        $new_name = $_POST['nom'];
        $sql.=" set nom =upper('$new_name')";
        $i++;
    }

    if (!empty($_POST['prénom'])){ //modifie le nom
        if ($i>0){
            $new_first_name = $_POST['prénom'];
            $sql.=", prenom ='$new_first_name'";
        }else{
            $new_first_name = $_POST['prénom'];
            $sql.=" set prenom ='$new_first_name'";
            $i++;
        }
    }

    if (!empty($_POST['date_naissance'])){ //modifie la date de naissance
        if ($i>0){
            $new_birthdate = $_POST['date_naissance'];
            $sql.=", annee_naissance = $new_birthdate";
        }else{
            $new_birthdate = $_POST['date_naissance'];
            $sql.=" set date_naissance = $new_birthdate";
            $i++;
        }
    }

    if(!empty($_POST['annee_prem'])){ //modifie l'année de première participation
        if ($i>0){
            $new_prem = $_POST['annee_prem'];
            $sql.=", annee_prem = $new_prem";
        }else{
            $new_prem = $_POST['annee_prem'];
            $sql.=" set annee_prem = $new_prem";
            $i++;
        }
    }
    $sql.=" where n_coureur = ".$n_coureur;
    echo $sql;
    echo "<br>conn : ";

    $cur = preparerRequetePDO($conn, $sql);
    $res = majDonneesPrepareesPDO($cur);
    if ($res){
        $sql = "SELECT n_coureur, nom, prenom, annee_naissance, annee_prem from tdf_coureur where n_coureur=$n_coureur";
        $cur = preparerRequetePDO($conn, $sql);
        $res = lireDonneesPDOPreparee($cur,$tab);
        AfficherResultats($tab, $res);
    }
?>