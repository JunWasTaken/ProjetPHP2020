<?php
    include "pdo_oracle.php";
    include "util_chap11.php";

    $id_connection = "PPHP2A_04";
    $mdp_connection = "PPHP2A_04";
    $BDD = fabriquerChaineConnexPDO();
    $conn = OuvrirConnexionPDO($BDD,$id_connection,$mdp_connection);

    $n_coureur = select_num_coureur($conn);
    $n_coureur+=1;

    if (!empty($_POST['nom'])){
      $nom = ($_POST['nom']);
      $prenom = ucfirst($_POST['prénom']);
      if (!empty($_POST['date_naissance']))
        $date_naissance = $_POST['date_naissance'];
      if (!empty($_POST['date_prem'])){
        $date_prem = $_POST['date_prem'];
      }
      $sql = "INSERT INTO tdf_coureur values($n_coureur, upper('$nom'), '$prenom', $date_naissance, $date_prem);";

      $stmt = majDonneesPDO($conn, $sql);
      AfficherTab($stmt);
      if ($stmt){
        echo "insertion réussie ! ";
      }

      echo $sql;
    }else{
      include("../Formulaire/AjoutCoureur.htm");
    }

    /*$nom = !empty($_POST['nom']) ? $_POST['nom'] : NULL;
    $prenom = !empty($_POST['prenom'])? $_POST['prenom'] : NULL;
    $ddn = intval(!empty($_POST['ddn']))? $_POST['ddn'] : NULL;
    $prem = intval(!empty($_POST['prem']))? $_POST['prem'] : NULL;

    if(!empty($nom) && !empty($prenom)){
    	var_dump($ddn);
      $sql ="INSERT INTO TDF_COUREUR(NOM, PRENOM, ANNEEE_NAISSANCE, ANNEE_PREM) VALUES (:nom,:prenom,:ddn,:prem)";
      AfficherTab($sql);
    	$tab= preparerRequetePDO($conn,$sql);
    	ajouterParamPDO($tab,':nom',$nom);
      ajouterParamPDO($tab,':prenom',$prenom);
      ajouterParamPDO($tab,':ddn',$ddn);
      ajouterParamPDO($tab,':prem',$prem);
      $res = majDonneesPrepareesPDO($tab);
      AfficherTab($res);
      AfficherTab($conn);
    }else{
    	echo "non";
    }*/
?>

<?php

    function select_num_coureur($conn){
      $sql = "SELECT MAX(n_coureur) from tdf_coureur";
      $res = LireDonneesPDO1($conn,$sql,$tab);
      $return_value = 0;

      for ($i=0; $i<$res; $i++){
        foreach($tab[$i] as $key=>$val)
          $return_value = $val;
      }
      return $return_value;
    }

?>