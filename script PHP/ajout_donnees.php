<?php
    include "pdo_oracle.php";
    include "util_chap11.php";
    include "testNom.php";
    include "testPrenom.php";

    $id_connection = "PPHP2A_04";
    $mdp_connection = "PPHP2A_04";
    $BDD = "oci:dbname=kiutoracle18.unicaen.fr:1521/info.kiutoracle18.unicaen.fr;charset=AL32UTF8";
    $conn = OuvrirConnexionPDO($BDD,$id_connection,$mdp_connection);

    $n_coureur = select_num_coureur($conn);
    $n_coureur+=1;

    if (!empty($_POST['nom'])){ //vérifie si le prénom existe
      $nom = strtoupper(nomValide($_POST['nom']));
      if (!empty($_POST['prénom'])){ //vérifie si le prénom n'est pas vide
        $prenom = prenomValide($_POST['prénom']);
        if (is_present_db($conn, $nom, $prenom) == 0){ //on vérifie que le couple nom / prénom n'est pas déjà présent dans la DB
          if (!empty($_POST['pays'])){ //vérifie que le coureur a bien une nationalité
            if (!empty($_POST['date_naissance']) && !empty($_POST['date_prem'])){ //création de la requête SQL si date_naissance et date_prem sont remplies
              $date_prem = $_POST['date_prem'];
              $date_naissance = $_POST['date_naissance'];
              if (!($date_prem < $date_naissance)){
                $sql = "INSERT INTO tdf_coureur (n_coureur, NOM, PRENOM, ANNEE_NAISSANCE, ANNEE_PREM) values($n_coureur, '$nom', '$prenom', $date_naissance, $date_prem) ";
                $stmt = majDonneesPDO($conn, $sql); //insertion du coureur dans la table tdf_coureur
              }else{ //date_prem < date_naissance
                echo "La date de première participation ne peut pas être inférieure à la date de naissance ";
                include ("../Formulaire/AjoutCoureur.htm");
              }  
            }else if (!empty($_POST['date_naissance']) && empty($_POST['date_prem'])){ //création de la requête SQL si uniquement date_naissance est remplie
              $date_naissance = $_POST['date_naissance'];
              $sql = "INSERT INTO tdf_coureur (n_coureur, NOM, PRENOM, ANNEE_NAISSANCE) values($n_coureur, '$nom', '$prenom', $date_naissance) ";
              $stmt = majDonneesPDO($conn, $sql); //insertion du coureur dans la table tdf_coureur
            }else if(!empty($_POST['date_prem']) && empty($_POST['date_naissance'])){ //création de la requête SQL si uniquement date_prem est remplie
              $date_prem = $_POST['date_prem'];
              $sql = "INSERT INTO tdf_coureur (n_coureur, NOM, PRENOM, ANNEE_PREM) values($n_coureur, '$nom', '$prenom', $date_prem) ";
              $stmt = majDonneesPDO($conn, $sql); //insertion du coureur dans la table tdf_coureur
            }else{ //création de la requête SQL si ni date_naissance, ni date_prem remplie
              $sql = "INSERT INTO tdf_coureur (n_coureur, nom, prenom) values ($n_coureur, '$nom', '$prenom') ";
              $stmt = majDonneesPDO($conn, $sql); //insertion du coureur dans la table tdf_coureur
            }

            if ($stmt == 1){
              if ($_POST['pays'] != "init"){ //On ne commence pas à insérer dans la table tdf_app_nation si aucun pays n'a été saisi
                $pays = select_code_cio($conn, $_POST['pays']);
                if (!empty($_POST['date_debut'])){ //insertion dans tdf_app_nation si date_debut saisie
                  $annee = $_POST['date_debut'];
                  insert_app_nation($conn, $pays, $annee, $n_coureur);
                }else if (!empty($_POST['date_naissance'])){ //insertion dans tdf_app_nation si date_debut non saisie mais date_naissance
                  $annee = $_POST['date_naissance'];
                  insert_app_nation($conn, $pays, $annee, $n_coureur);
                }else //insertion si il n'y a ni date_debut ni date_naissance
                  $sql2 = "INSERT INTO tdf_app_nation (n_coureur, code_cio) values ($n_coureur, '$pays') ";
                AfficherTab($sql2);
                $stmt2 = majDonneesPDO($conn, $sql2);
                AfficherTab($stmt2);
                if ($stmt2){ //insertion tdf_app_nation réussie
                  include ('../Formulaire/Accueil.htm');
                }else
                  echo "tdf_app_nation : l'insertion a échouée...";
              }else{ //pas de nationalité saisie
                echo "Pas de nationalité saisie : pas d'insertion dans tdf_app_nation";
                include "../Formulaire/Accueil.htm";
              }
            }
          }else
            include "../Formulaire/AjoutCoureur.htm" ;
        }else{
          echo "le couple nom / prénom existe déjà dans la DB";
          include "../Formulaire/AjoutCoureur.htm";
        }
      }else
        include "../Formulaire/AjoutCoureur.htm" ;
    }else{
      include "../Formulaire/AjoutCoureur.htm" ;
    }
?>

<?php
  function select_num_coureur($conn){ //retourne le num_coureur le plus élevé
    $sql = "SELECT MAX(n_coureur) from tdf_coureur";
    $res = LireDonneesPDO1($conn,$sql,$tab);
    $return_value = 0;

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

  function insert_app_nation($conn, $pays, $annee, $n_coureur){
    $sql = "INSERT INTO tdf_app_nation (n_coureur, code_cio, annee_debut) values ($n_coureur, '$pays', $annee)";
    AfficherTab($sql);
    $stmt = majDonneesPDO($conn, $sql);
    if ($stmt){
      echo "tdf_app_nation : insertion réussie ! ";
    }else
      echo "tdf_app_nation : l'insertion a échouée...";
  }

  function is_present_db($conn, $nom, $prenom){
    $sql = "SELECT n_coureur from tdf_coureur where nom = '$nom' and prenom = '$prenom'";
    $res = LireDonneesPDO3($conn, $sql, $tab);
    return $res;
  }
?>