<?php
    include "pdo_oracle.php";
    include "util_chap11.php";
    include "testNom.php";
    include "testPrenom.php";
    include "testDate.php";

    $id_connection = "PPHP2A_04";
    $mdp_connection = "PPHP2A_04";
    $BDD = "oci:dbname=kiutoracle18.unicaen.fr:1521/info.kiutoracle18.unicaen.fr;charset=AL32UTF8";
    $conn = OuvrirConnexionPDO($BDD,$id_connection,$mdp_connection);

    $n_coureur = select_num_coureur($conn);
    $n_coureur+=1;
    $jour = date("Y");

    if (!empty($_POST['nom'])){ //vérifie si le prénom existe
      try{ //vérification que le nom est valide
        $nom = nomValide($_POST['nom']);
        $nom = strtoupper($nom);
        if (!empty($_POST['prénom'])){ //vérifie si le prénom n'est pas vide
          try{ //vérification que le prénom est valide
            $prenom = prenomValide($_POST['prénom']);
            $prenom = my_mb_ucfirstPrenom($prenom);
            if (is_present_db($conn, $nom, $prenom) == 0){ //on vérifie que le couple nom / prénom n'est pas déjà présent dans la DB
              if ($_POST['pays'] != "init"){ //vérifie que le coureur a bien une nationalité
                try{ //on effectue les tests sur les années
                  test_annee_complet($jour, $_POST['date_naissance'], $_POST['date_debut'], $_POST['date_prem']);
                  if (!empty($_POST['date_naissance']) && !empty($_POST['date_prem'])){ //création de la requête SQL si date_naissance et date_prem sont remplies
                    $date_prem = $_POST['date_prem'];
                    $date_naissance = $_POST['date_naissance'];
  
                    $sql = "INSERT INTO tdf_coureur (n_coureur, NOM, PRENOM, ANNEE_NAISSANCE, ANNEE_PREM, COMPTE_ORACLE, DATE_INSERT) values($n_coureur, '$nom', '$prenom', $date_naissance, $date_prem, 'Eleve', sysdate) ";
                    AfficherTab($sql);
                    $stmt = majDonneesPDO($conn, $sql); //insertion du coureur dans la table tdf_coureur
                    
                  }else if (!empty($_POST['date_naissance']) && empty($_POST['date_prem'])){ //création de la requête SQL si uniquement date_naissance est remplie
                    $date_naissance = $_POST['date_naissance'];
                    $sql = "INSERT INTO tdf_coureur (n_coureur, NOM, PRENOM, ANNEE_NAISSANCE, COMPTE_ORACLE, DATE_INSERT) values($n_coureur, '$nom', '$prenom', $date_naissance, 'Eleve', sysdate) ";
                    AfficherTab($sql);
                    $stmt = majDonneesPDO($conn, $sql); //insertion du coureur dans la table tdf_coureur
                  }else if(!empty($_POST['date_prem']) && empty($_POST['date_naissance'])){ //création de la requête SQL si uniquement date_prem est remplie
                    $date_prem = $_POST['date_prem'];
                    $sql = "INSERT INTO tdf_coureur (n_coureur, NOM, PRENOM, ANNEE_PREM, COMPTE_ORACLE, DATE_INSERT) values($n_coureur, '$nom', '$prenom', $date_prem, 'Eleve', sysdate) ";
                    AfficherTab($sql);
                    $stmt = majDonneesPDO($conn, $sql); //insertion du coureur dans la table tdf_coureur
                  }else{ //création de la requête SQL si ni date_naissance, ni date_prem remplie
                    $sql = "INSERT INTO tdf_coureur (n_coureur, nom, prenom, COMPTE_ORACLE, DATE_INSERT) values ($n_coureur, '$nom', '$prenom', 'Eleve', sysdate) ";
                    AfficherTab($sql);
                    $stmt = majDonneesPDO($conn, $sql); //insertion du coureur dans la table tdf_coureur
                  }
      
                  if ($stmt == 1){
                    echo "insertion tdf_coureur réussie";
                    $pays = select_code_cio($conn, $_POST['pays']);
                    if (!empty($_POST['date_debut'])){ //insertion dans tdf_app_nation si date_debut saisie
                      $annee = $_POST['date_debut'];
                      $stmt2 = insert_app_nation($conn, $pays, $annee, $n_coureur);
                    }else if (!empty($_POST['date_naissance'])){ //insertion dans tdf_app_nation si date_debut non saisie mais date_naissance
                      $annee = $_POST['date_naissance'];
                      $stmt2 = insert_app_nation($conn, $pays, $annee, $n_coureur);
                    }else{
                      $sql2 = "INSERT INTO tdf_app_nation (n_coureur, code_cio, COMPTE_ORACLE, DATE_INSERT) values ($n_coureur, '$pays', 'ELEVE', sysdate) ";
                      $stmt2 = majDonneesPDO($conn, $sql2);
                    } //insertion si il n'y a ni date_debut ni date_naissance
                  }
  
                  if ($stmt == 1 && $stmt2 == 1){
                    include "../Formulaire/Accueil.htm";
                  }
                }catch(Exception $e){
                  echo $e->getMessage();
                  //include ("../Formulaire/AjoutCoureur.htm");
                }
              }else //pays == "init"
                echo "vous n'avez saisi aucun pays";
                include "../Formulaire/AjoutCoureur.htm" ;
            }else{ //coureur présent
              echo "le couple nom / prénom existe déjà dans la DB";
              include "../Formulaire/AjoutCoureur.htm";
            }
          }catch (Exception $e){ //si le prénom contient des erreurs
            echo "<br>prénom : ",$e->getMessage();
            include ("../Formulaire/AjoutCoureur.htm");
          }
        }else
          include "../Formulaire/AjoutCoureur.htm" ;
      }catch (Exception $e){ //si le nom contient des erreurs
        echo "nom : ",$e->getMessage();
        include ("../Formulaire/AjoutCoureur.htm");
      }
    }else{ //nom pas saisi
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
    $sql = "INSERT INTO tdf_app_nation (n_coureur, code_cio, annee_debut, COMPTE_ORACLE, DATE_INSERT) values ($n_coureur, '$pays', $annee, 'ELEVE', sysdate)";
    AfficherTab($sql);
    $stmt = majDonneesPDO($conn, $sql);
    if ($stmt){
      echo "tdf_app_nation : insertion réussie ! ";
    }else
      echo "tdf_app_nation : l'insertion a échouée...";
    return $stmt;
  }

  function is_present_db($conn, $nom, $prenom){
    $sql = "SELECT n_coureur from tdf_coureur where nom = '$nom' and prenom = '$prenom'";
    $res = LireDonneesPDO3($conn, $sql, $tab);
    return $res;
  }
?>