<?php
    include "pdo_oracle.php";
    include "util_chap11.php";
    include "testNom.php";
    include "testPrenom.php";

    $id_connection = "PPHP2A_04";
    $mdp_connection = "PPHP2A_04";
    $BDD = fabriquerChaineConnexPDO();
    $conn = OuvrirConnexionPDO($BDD,$id_connection,$mdp_connection);

    $n_coureur = select_num_coureur($conn);
    $n_coureur+=1;
    $pays_coureur = select_pays_coureur($conn, $n_coureur);

    if (!empty($_POST['nom'])){ //vérifie si le prénom existe
      $nom = strtoupper(nomValide($_POST['nom']));
      if (!empty($_POST['prénom'])){ //vérifie si le prénom n'est pas vide
        $prenom = my_mb_ucfirst(prenomValide($_POST['prénom']));
        if (!empty($_POST['pays'])){ //vérifie que le coureur a bien une nationalité
          if (empty($_POST['date_naissance']) && !empty($_POST['date_naissance'])){
            $date_prem = $_POST['date_prem'];
            $sql = "INSERT INTO tdf_coureur (n_coureur, NOM, PRENOM, ANNEE_PREM) values($n_coureur, '$nom', '$prenom', $date_prem) ";
          }else if(!empty($_POST['date_naissance']) && empty($_POST['date_prem'])){
            $date_naissance = $_POST['date_naissance'];
            $sql = "INSERT INTO tdf_coureur (n_coureur, NOM, PRENOM, ANNEE_NAISSANCE) values($n_coureur, '$nom', '$prenom', $date_naissance) ";
          }else{
            $date_prem = $_POST['date_prem'];
            $date_naissance = $_POST['date_naissance'];
            $sql = "INSERT INTO tdf_coureur (n_coureur, NOM, PRENOM, ANNEE_NAISSANCE, ANNEE_PREM) values($n_coureur, '$nom', '$prenom', $date_naissance, $date_prem) ";
          }
          $stmt = majDonneesPDO($conn, $sql);
          if ($stmt){
            echo "tdf_coureur : insertion réussie ! ";
            if (!empty($_POST['date_debut']) && $_POST['pays'] != "init"){
              $pays = $_POST['pays'];
              $annee = $_POST['date_debut'];
              insert_app_nation($conn, $pays, $annee, $n_coureur);
            }else if (empty($_POST['date_debut']) && $_POST['pays'] != "init"){
              $pays = $_POST['pays'];
              insert_app_nation($conn, $pays, $date_naissance, $n_coureur);
            }else
              echo "sélectionner un pays valide";
              include "../Formulaire/AjoutCoureur.htm";
            include ("../Formulaire/Accueil.htm");
          }else{
            echo "l'insertion a échouée...";
            include "../Formulaire/AjoutCoureur.htm";
          }
        }else
          include "../Formulaire/AjoutCoureur.htm" ;
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
    AfficherPays($tab, $res);
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
    $pays = select_code_cio($conn, $pays);
    $sql = "INSERT INTO tdf_app_nation (n_coureur, code_cio, annee_debut) values ($n_coureur, '$pays', $annee)";
    $stmt = majDonneesPDO($conn, $sql);
    if ($stmt){
      echo "tdf_app_nation : insertion réussie ! ";
    }else
      echo "tdf_app_nation : l'insertion a échouée...";
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
?>