<?php //script php traitement
    include "pdo_oracle.php";
    include "util_chap11.php";
    include "testNom.php";
    include "testPrenom.php";
                
    $id_connection = "PPHP2A_04";
    $mdp_connection = "PPHP2A_04";
    $BDD = "oci:dbname=kiutoracle18.unicaen.fr:1521/info.kiutoracle18.unicaen.fr;charset=AL32UTF8";
    $conn = OuvrirConnexionPDO($BDD,$id_connection,$mdp_connection);

    $n_coureur = $_COOKIE['n_coureur'];
    $pays_coureur = select_pays_coureur($conn, $n_coureur);

    $sql = "update tdf_coureur";
    $i = 0;
    $sql2 = "UPDATE tdf_app_nation ";
    $j = 0;

    include ("../Formulaire/modifCoureur.htm");

    try{
        participer($conn, $n_coureur);
        //on éxécute la requête uniquement si on change le nom ou le prénom ou la date de naissance ou la date de première participation
        if (!empty($_POST['nom']) || !empty($_POST['prénom']) || !empty($_POST['date_naissance']) || !empty($_POST['annee_prem'])){ 
            if (!empty($_POST['nom'])){ //modifie le nom
                $new_name = strtoupper(nomValide($_POST['nom']));
                $sql.=" set nom =upper('$new_name')";
                $i++;
            }

            if (!empty($_POST['prénom'])){ //modifie le nom
                if ($i>0){
                    $new_first_name = prenomValide($_POST['prénom']);
                    $sql.=", prenom ='$new_first_name'";
                }else{
                    $new_first_name = prenomValide($_POST['prénom']);
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
                    $sql.=" set annee_naissance = $new_birthdate";
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
            $cur = preparerRequetePDO($conn, $sql);
            $res = majDonneesPrepareesPDO($cur);
        }

        $exist = isset($_POST['pays']);
        echo $exist;

        if (!empty($_POST['annee_debut']) || !empty($_POST['annee_fin']) || isset($_POST['pays'])){
            if (exist_app_nation($conn, $n_coureur)){
                if (!empty($_POST['annee_debut'])){
                    if ($j>0){
                        $new_debut = $_POST['annee_debut'];
                        $sql2.=", annee_debut = $new_debut";
                    }else{
                        $new_debut = $_POST['annee_debut'];
                        $sql2.="set annee_debut = $new_debut";
                        $j++;
                    }
                }
            
                if (!empty($_POST['annee_fin'])){
                    if ($j>0){
                        $new_fin = $_POST['annee_fin'];
                        $sql2.=", annee_fin = $new_fin";
                    }else{
                        $new_fin = $_POST['annee_fin'];
                        $sql2.="set annee_fin = $new_fin";
                        $j++;
                    }
                }
            
                if ($_POST['pays'] != $pays_coureur && $_POST['pays'] != "init"){
                    if ($j>0){
                        $new_pays = select_code_cio($conn, $_POST['pays']);
                        $sql2.=", code_cio = '$new_pays'";
                    }else{
                        $new_pays = select_code_cio($conn, $_POST['pays']);
                        $sql2.="set code_cio = '$new_pays'";
                        $j++;
                    }
                }
                $sql2.=" where n_coureur = ".$n_coureur;

                $cur = preparerRequetePDO($conn, $sql2);
                $res = majDonneesPrepareesPDO($cur);
                if ($res){
                    $sql = "SELECT n_coureur, nom, prenom, annee_naissance, annee_prem, annee_debut, annee_fin, code_cio from tdf_coureur join tdf_app_nation using (n_coureur) where n_coureur=$n_coureur";
                    $cur = preparerRequetePDO($conn, $sql);
                    $res = lireDonneesPDOPreparee($cur,$tab);
                    AfficherCoureur($tab, $res);
                }
            }else{
                if ($_POST['pays'] != "init"){
                    $pays = select_code_cio($conn, $_POST['pays']);

                    if (!empty($_POST['annee_debut'])){
                        $annee = $_POST['annee_debut'];
                        insert_app_nation($conn, $pays, $annee, $n_coureur);
                        echo "le coureur existe désormais dans la table tdf_app_nation";
                        include "../Formulaire/Accueil.htm";
                    }
                }
            }
        }
    }catch(Exception $e){
        echo $e->getMessage();
    }
    
?>

<?php //fonction propre au fichier

    function participer($conn, $n_coureur){
        $sql = "SELECT n_coureur from tdf_parti_coureur where n_coureur = $n_coureur";
        $res = LireDonneesPDO3($conn, $sql, $tab);
        if ($res>0){
            throw new Exception('Coureur a participé à tdf précédent');
        }
    }

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
        AfficherAjaxPays($tab, $res);
    }

    function update_app_nation($conn, $n_coureur, $debut, $fin, $pays){
        $sql = "UPDATE tdf_app_nation ";
        $pays = select_code_cio($conn, $pays);
        $j = 0;

        $sql.=create_update_query($debut, 'annee_debut', $j, false);
        $sql.=create_update_query($fin, 'annee_fin', $j, false);
        $sql.=create_update_query($pays, 'code_cio', $j, true);

        $sql.=" where n_coureur = ".$n_coureur;
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

    function exist_app_nation($conn, $n_coureur){
        $sql = "select n_coureur from tdf_app_nation where n_coureur = $n_coureur";
        $res = LireDonneesPDO1($conn, $sql, $tab);

        if ($res > 0)
            return true;
        return false;
    }

    function insert_app_nation($conn, $pays, $annee, $n_coureur){
        $sql = "INSERT INTO tdf_app_nation (n_coureur, code_cio, annee_debut) values ($n_coureur, '$pays', $annee)";
        $stmt = majDonneesPDO($conn, $sql);
        if ($stmt){
          echo "tdf_app_nation : insertion réussie ! ";
        }else
          echo "tdf_app_nation : l'insertion a échouée...";
    }
?>