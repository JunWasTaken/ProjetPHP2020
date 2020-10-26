<?php 
//tout les test écrits dans le fichie ods

/*$tab[1] = "Ébé-ébé";
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
$tab[35] = "A";*/

mb_internal_encoding("UTF-8"); //encodage en utf-8

/*foreach($tab as $var){
	$enMin=mb_strtolower($var);
	$enMin=retireTiret($enMin);
	$enMin=my_mb_ucfirst($enMin);
	$enMin=ucwords($enMin);
	$enMin=maj($enMin);
	$enMin=remplacerAccents($enMin);
	retireEspace($enMin);
	interdit($enMin);
	echo'<br / &nbsp>';
}*/

function remplacerAccentsPrenom($prenom){
//Lien du tableau : https://stackoverflow.com/questions/3371697/replacing-accented-characters-php
	
$accents = array('Š'=>'S', 'š'=>'S', 'Ž'=>'Z', 'ž'=>'Z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'Ae', 'Ç'=>'C', 'È'=>'E', 'É'=>'E','Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U','Ú'=>'U', 'Û'=>'U', 'Ŭ' => 'U', 'Ü'=>'U', 'Ý'=>'Y', 'Ÿ'=> 'Y' , 'Þ'=>'B', 'ß'=>'B', 'ø'=> 'o', 'ñ'=>'n' );
$prenom = strtr($prenom, $accents);
return $prenom;
}

function remplacerApostrophesPrenom($prenom){ //transforme tout apostrophes différents de < ' >
	//$apostrophe=array("’"=>"''", "ʾ"=>"''", "′"=>"''", "ˊ"=>"''", "ꞌ"=>"''", "‘"=>"''", "ʿ"=>"''", "‵"=>"''", "ˋ"=>"''" ,"''"=> "' '");
	$prenom = str_replace("'", "''", $prenom);
	//$prenom = strtr($prenom, $apostrophe);
	return $prenom;
}

function my_mb_ucfirstPrenom($prenom){ //met premiere lettre en majuscule même si elle était accentuée avant
	$pre=mb_strtoupper(mb_substr($prenom, 0,1));
	return $pre.mb_substr($prenom, 1);
}

function majPrenom($prenom){ //met en majuscule si c'est un prénom composé
//source : //https://stackoverflow.com/questions/5546120/php-capitalize-after-dash
	$prenom = implode('-', array_map('my_mb_ucfirstPrenom', explode('-', $prenom))); //mettre en majuscule après un tiret
	$prenom = implode('\'', array_map('my_mb_ucfirstPrenom', explode('\'', $prenom))); //mettre en majuscule après un apostrophe
	$prenom = implode(' ', array_map('my_mb_ucfirstPrenom', explode(' ', $prenom)));//mettre en majuscule après un espace
	$tab=explode('[- \']', $prenom);
	$prenom=implode('[- \']', $tab);
	return $prenom;
}


function retireTiretPrenom($prenom){ //retire les tirets si il y en a en début ou fin de chaine
	$prenom = rtrim(ltrim($prenom, '[ -]'), '[ -]');
	return $prenom;
}

function interditPrenom($prenom){
	$regex1="$(.*--.*)$";
	$regex2="'/^[^\']*$/'";
	$regex3="$\\\\$";
	$regex4="~^[a-zA-ZÀ-ÖØ-öø-ÿœŒ\-'\s']+$~u";
	$regex5="/[a-zA-Z]/";
	$regex6='[€"]';

    if(preg_match($regex1, $prenom)){
		echo "Erreur de saisie1 : trop de - dans le prénom";
		return -1;
	}else if(preg_match($regex2, $prenom)){
		echo "Erreur de saisie2";
		return -1;
	}else if(preg_match($regex3, $prenom)){
		echo "Erreur de saisie 3 : présence de \ ";
		return -1;
	}else if(preg_match($regex6, $prenom)){
		echo "Erreur de saisie : € présent dans la chaîne";
		return -1;
	}else if(strlen($prenom) > 40){
		echo "chaîne trop longue";
		return -1;
	}else if($prenom == "'"){
		echo "vous ne pouvez pas saisir qu'un ' ";
		return -1;
	}else if (preg_match($regex4, $prenom) || preg_match($regex5, $prenom)) {
		return 1;
    }else {
    	echo "Erreur de saisie 5 :caractère invalide saisi";
	}
}

function retireEspacePrenom($prenom){ //remplace des espaces en un seul
	$prenom = preg_replace('/\s\s+/', ' ', $prenom);
	return $prenom;
}

function prenomValide($prenom){ //idem que pour le nom mais avec le prénom
    if (interditPrenom($prenom)==1){
  	  $prenom = mb_strtolower($prenom);
  	  $prenom = majPrenom($prenom);
      $prenom = remplacerAccentsPrenom($prenom);
      $prenom = remplacerApostrophesPrenom($prenom);
      $prenom = retireTiretPrenom($prenom);
	  $prenom = retireEspacePrenom($prenom);
	  $prenom = my_mb_ucfirstPrenom($prenom);
      return $prenom;
    }
  }
?>