<?php
// E.Porcq  util_chap11.php  28/08/2018 

function AfficherDonnee1($tab,$nbLignes)
{
  if ($nbLignes > 0) 
  {
    echo "<table border=\"1\">\n";
    echo "<tr>\n";
    foreach ($tab as $key => $val)  // lecture des noms de colonnes
    {
      echo "<th>$key</th>\n";
    }
    echo "</tr>\n";
	  echo $nbLignes;
    for ($i = 0; $i < $nbLignes; $i++) // balayage de toutes les lignes
    {
      echo "<tr>\n";
      foreach ($tab as $data) // lecture des enregistrements de chaque colonne
	  {
        echo "<td>$data[$i]</td>\n";
      }
      echo "</tr>\n";
    }
    echo "</table>\n";
  } 
  else 
  {
    echo "Pas de ligne<br />\n";
  } 
  echo "$nbLignes Lignes lues<br />\n";
}
//---------------------------------------------------------------------------------------------
function AfficherDonnee2($tab)
{
  foreach($tab as $ligne)
  {
    foreach($ligne as $valeur)
	  echo $valeur." ";
    echo "<br/>";
  }
}
//---------------------------------------------------------------------------------------------
function AfficherDonnee3($tab,$nb)
{
  for($i=0;$i<$nb;$i++)
    echo $tab[$i][0]." ".$tab[$i][1]." ".$tab[$i][2]."\n";
}
//---------------------------------------------------------------------------------------------
function AfficherTab($tab)
{
	echo "<PRE>";
	print_r($tab);
	echo "</PRE>";
}
//---------------------------------------------------------------------------------------------

function AfficherResultats($tab, $nb){
  if ($nb>0){
    echo "<table>";

    echo "<tr>"; //affichage des titres de colonnes
    foreach($tab[0] as $key => $val){
      echo "<th>$key</th>";
    }
    echo "</tr>";

    for($i = 0; $i<$nb; $i++){ //affichage des résultats 
      echo "<tr>";
      foreach($tab[$i] as $val){
        echo "<td>";
        echo $val;
        echo "</td>";
      }
      echo "</tr>";
    }

    echo "</table>";
  }else
    echo "Pas de résultats";

  
}
?>




