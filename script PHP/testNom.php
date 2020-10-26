<?php 
//tout les test écrits dans le fichie ods
/**$tab[1] = "Ébé-ébé";
$tab[2] = "ébé-ébé";
$tab[3] = 'ébé-Ébé';
$tab[4] = "éÉé-Ébé";
$tab[5] = "'éÉ'é-É'bé'";
$tab[6] = "'éæé-É'bé'";
$tab[7] = "'éæé-É'Ŭé'";
$tab[8] = "'é !é-É'Ŭé'";
$tab[9] = "éé’’éé--uù  gg";
$tab[10] = "Éééé--gg--gg";
$tab[11] = "DE LA TR€UC";
$tab[12] = "DE LA TRUC";
$tab[13] = "ééééééééééééééééééééééééééééééééééééééééééééééé";
$tab[14] = "ùùùùùùùùùùùùùùùùùùùù";
$tab[15] = "-péron-de - la   branche-";
$tab[16] = "pied-de-biche";
$tab[17] = "Ferdinand--SaintMalo ALAnage";
$tab[18] = "Ferdinand--SaintMalo-ALAnage";
$tab[19] = "aa--bb--cc";
$tab[20] = "A' ' b";
$tab[21] = "A'";
$tab[22] = "'";
$tab[23] = "x";
$tab[24] = "A '' b";
$tab[25] = "bénard     ébert";
$tab[26] = "ÆøœŒøñ";
$tab[27] = "\a";
$tab[28] = "\\a";
$tab[29] = "b\\a";
$tab[30] = "b\a";
$tab[31] = "Æ'-'nO";
$tab[32] = "çççç ççç ÇÇÇÇ ÇÇÇ";
$tab[33] = "àâäéèêëïîôöùûüÿç";
$tab[34] = "ÀÂÄÉÈÊËÏÎÔÖÙÛÜŸÇ";
$tab[36] ="-";


foreach($tab as $var){
	$nom=remplacerAccents($var);
	$enMaj=strtoupper($nom);
	$enMaj=retireTiret($enMaj);
	interdit($enMaj);
	retireEspace($enMaj);
	echo'<br / &nbsp>';
}*/

function remplacerAccents($nom){
//Lien du tableau : https://stackoverflow.com/questions/3371697/replacing-accented-characters-php
	
$accents = array('Š'=>'s', 'š'=>'s', 'Ž'=>'z', 'ž'=>'z', 'À'=>'a', 'à'=>'a', 'Á'=>'a', 'á'=>'a', 'Â'=>'a', 'â'=>'a', 'Ã'=>'a', 'ã'=>'a', 'Ä'=>'a', 'ä'=>'a', 'Å'=>'a', 'å'=>'a', 'Æ'=>'ae', 'æ' =>'a','Ç'=>'c', 'ç'=>'c', 'È'=>'e', 'è'=>'e', 'É'=>'e', 'é'=>'e','Ê'=>'e', 'ê'=>'e','Ë'=>'e', 'ë'=>'e', 'Ì'=>'i', 'ì'=>'i', 'Í'=>'i', 'í'=>'i', 'Î'=>'i', 'î'=>'i', 'Ï'=>'i', 'ï'=>'i', 'Ñ'=>'n', 'ñ'=>'n','Ò'=>'o', 'ò'=>'o', 'Ó'=>'o', 'ó'=>'o', 'Ô'=>'o', 'ô'=>'o', 'Õ'=>'o', 'õ'=>'o', 'Ö'=>'o', 'ö'=>'o', 'Ø'=>'o', 'ø'=>'o','Ù'=>'u', 'ù'=>'u', 'Ú'=>'u', 'ú'=>'u', 'Û'=>'u', 'û'=>'u', 'Ŭ' => 'u', 'ü'=>'u', 'Ü'=>'u', 'Ý'=>'y', 'ý'=>'y', 'Ÿ'=> 'y', 'ÿ'=>'y', 'ð'=>'d', 'Ð'=>'d', 'þ'=>'b', 'Þ'=>'b', 'ß'=>'b','œ' => "OE",'®'=>'R');
$nom = strtr($nom, $accents);
return $nom;
}

function remplacerApostrophes($nom){
	//$apostrophe=array("’"=>"''", "ʾ"=>"''", "′"=>"''", "ˊ"=>"''", "ꞌ"=>"''", "‘"=>"''", "ʿ"=>"''", "‵"=>"''", "ˋ"=>"''" ,"''"=> "' '");
	$nom = str_replace("'", "''", $nom);
	//$nom = strtr($nom, $apostrophe);
	return $nom;
}

function retireTiret($nom){
	$nom = rtrim(ltrim($nom, '[ -]'), '[ -]');
	return $nom;
}

function interdit($nom){
	$regex1="$(.*--.*){2}$";
	$regex2="'/^[^\']*$/'";
	$regex3="$\\\\$";
	$regex4="~^[a-zA-ZÀ-ÖØ-öø-ÿŸœŒ\-'\s']+$~u";
	$regex5="/[a-zA-Z]/";
	$regex6="[€\"]";

    if(preg_match($regex1, $nom)){
		echo "Erreur de saisie1 : trop de - dans le nom";
		return -1;
	}else if(preg_match($regex2, $nom)){
		echo "Erreur de saisie2";
		return -1;
	}else if(preg_match($regex3, $nom)){
		echo "Erreur de saisie 3 : présence de \ ";
		return -1;
	}else if(strlen($nom) > 40){
		echo "Erreur de saisie4 : chaîne trop longue";
		return -1;
	}else if(preg_match($regex6, $nom)){
		echo "Erreur de saisie : € présent dans la chaîne";
		return -1;
	}else if($nom == "'"){
		echo "vous ne pouvez pas saisir qu'un ' ";
		return -1;
	}else if (preg_match($regex4, $nom) || preg_match($regex5, $nom)) {
		return 1;
    }else {
		echo "Erreur de saisie 5 :caractère invalide saisi";
	}

}

function retireEspace($nom){
	$nom = preg_replace('/\s\s+/', ' ', $nom);
	return $nom;
}

function nomValide($nom){ //vérifie que le nom du coureur est bien valide, et le modifie pour qu'il corresponde au formatage de la base de données
    if (interdit($nom) == 1){
      $nom = remplacerAccents($nom);
      $nom = remplacerApostrophes($nom);
      $nom = retireTiret($nom);
	  $nom = retireEspace($nom);
	  $nom = strtoupper($nom);
      return $nom;
    }
  }
?>