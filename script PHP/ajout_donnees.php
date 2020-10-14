<?php
    include "pdo_oracle.php";
    include "util_chap11.php";
    include "testNom.php";

    $id_connection = "PPHP2A_04";
    $mdp_connection = "PPHP2A_04";
    $BDD = fabriquerChaineConnexPDO();
    $conn = OuvrirConnexionPDO($BDD,$id_connection,$mdp_connection);

    $n_coureur = select_num_coureur($conn);
    $n_coureur+=1;
    AfficherTab($n_coureur);

    if (!empty($_POST['nom'])){
      $nom = strtoupper(nomValide($_POST['nom']));
      $prenom = ucfirst($_POST['prénom']);
      if (!empty($_POST['date_naissance']))
        $date_naissance = $_POST['date_naissance'];
      if (!empty($_POST['date_prem'])){
        $date_prem = $_POST['date_prem'];
      }
      $sql = "INSERT INTO tdf_coureur (n_coureur, NOM, PRENOM, ANNEE_NAISSANCE, ANNEE_PREM) values($n_coureur, '$nom', '$prenom', $date_naissance, $date_prem) ";

      $stmt = majDonneesPDO($conn, $sql);
      AfficherTab($stmt);
      if ($stmt){
        echo "insertion réussie ! ";
      }

      echo $sql;
    }else{
      include("../Formulaire/AjoutCoureur.htm");
    }
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

    function nomValide($nom){
      echo $nom;
      if (interdit($nom) == 1){
        $nom = remplacerAccents($nom);
        echo $nom;
        $nom = retireTiret($nom);
        echo $nom;
        $nom = retireEspace($nom);
      }
      return $nom;
    }

?>