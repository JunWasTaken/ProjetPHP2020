<?php //script php traitement
    include "pdo_oracle.php";
    include "util_chap11.php";
                
    $id_connection = "PPHP2A_04";
    $mdp_connection = "PPHP2A_04";
    $BDD = fabriquerChaineConnexPDO();
    $conn = OuvrirConnexionPDO($BDD,$id_connection,$mdp_connection);

    $n_coureur = $_COOKIE['n_coureur'];
    $pays_coureur = select_pays_coureur($conn, $n_coureur);

    $sql = "update tdf_coureur";
    $i = 0;

    include ("../Formulaire/modifCoureur.htm");

    if (!empty($_POST['nom'])){ //modifie le nom
        $new_name = $_POST['nom'];
        $sql.=" set nom =upper('$new_name')";
        $i++;
    }

    /*$sql.=create_update_query($_POST['prénom'], 'prenom', $i, true);
    $sql.=create_update_query($_POST['date_naissance'], 'annee_naissance', $i, false);
    $sql.=create_update_query($_POST['annee_prem'], 'annee_prem', $i, false);*/

    //update_app_nation($conn, $n_coureur, $_POST['annee_debut'], $_POST['annee_fin'], $_POST['pays']);
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
    AfficherTab($sql);
    $cur = preparerRequetePDO($conn, $sql);
    $res = majDonneesPrepareesPDO($cur);
    if ($res){
        $sql = "SELECT n_coureur, nom, prenom, annee_naissance, annee_prem from tdf_coureur where n_coureur=$n_coureur";
        $cur = preparerRequetePDO($conn, $sql);
        $res = lireDonneesPDOPreparee($cur,$tab);
        AfficherCoureur($tab, $res);
    }
?>

<?php //fonction propre au fichier

    function select_pays_coureur($conn, $n_coureur){
        $sql = "SELECT nom from tdf_nation where code_cio in (select code_cio from tdf_app_nation where n_coureur = $n_coureur)";
        $res = LireDonneesPDO3($conn, $sql, $tab);
        $return_value = '';

        for ($i=0; $i<$res; $i++){
        foreach($tab[$i] as $key=>$val)
            $return_value = $val;
        }
        return $return_value;
    }

    function listePays($conn){ //affiche la liste des pays pour le coureur
        $sql = "select nom from tdf_nation order by nom";
        $res = LireDonneesPDO2($conn, $sql, $tab);
        AfficherPays($tab, $res);
    }

    function update_app_nation($conn, $n_coureur, $debut, $fin, $pays){
        $sql = "UPDATE tdf_app_nation ";
        $pays = select_code_cio($conn, $pays);
        $j = 0;

        $sql.=create_update_query($debut, 'annee_debut', $j, false);
        $sql.=create_update_query($fin, 'annee_fin', $j, false);
        $sql.=create_update_query($pays, 'code_cio', $j, true);

        $sql.=" where n_coureur = ".$n_coureur;
        AfficherTab($sql);
    }

    /**
     * Fonction create_update_query
     * Prends en paramètre : 
     *  - un début de requête SQL contenant update + le nom de la table
     *  - la valeur 
     *  - la colonne
     *  - un compteur
     *  - le type (true si la colonne est une string, false sinon)
     */
    function create_update_query($value, $cat, $cpt, $type){
        $sql ='';
        if (!empty($value)){ 
            if ($cpt>0){
                $new_value = $value;
                if ($type)
                    $sql.="set $cat ='$new_value'";
                else
                    $sql.="set $cat = $new_value";
            }else{
                $new_value = $value;
                if ($type)
                    $sql.=", $cat ='$new_value'";
                else
                    $sql.=", $cat = $new_value";
                $cpt++;
            }
        }

        return $sql;
    }

    function select_code_cio($conn, $pays){
        $sql = "select code_cio from tdf_nation where nom like'$pays%'";
        $res = LireDonneesPDO1($conn, $sql, $tab);
        $return_value = '';
    
        for ($i=0; $i<$res; $i++){
          foreach($tab[$i] as $key=>$val)
            $return_value = $val;
        }
        return $return_value;
      }
?>