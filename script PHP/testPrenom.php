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

mb_internal_encoding("UTF-8");

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
	
$accents = array('Š'=>'s', 'š'=>'s', 'Ž'=>'z', 'ž'=>'z', 'À'=>'a', 'à'=>'a', 'Á'=>'a', 'á'=>'a', 'Â'=>'a', 'â'=>'a', 'Ã'=>'a', 'ã'=>'a', 'Ä'=>'a', 'ä'=>'a', 'Å'=>'a', 'å'=>'a', 'Æ'=>'ae', 'æ' =>'a','Ç'=>'c', 'È'=>'e', 'è'=>'e', 'É'=>'e', 'é'=>'e','Ê'=>'e', 'ê'=>'e','Ë'=>'e', 'ë'=>'e', 'Ì'=>'i', 'ì'=>'i', 'Í'=>'i', 'í'=>'i', 'Î'=>'i', 'î'=>'i', 'Ï'=>'i', 'ï'=>'i', 'Ñ'=>'n', 'ñ'=>'n','Ò'=>'o', 'ò'=>'o', 'Ó'=>'o', 'ó'=>'o', 'Ô'=>'o', 'ô'=>'o', 'Õ'=>'o', 'õ'=>'o', 'Ö'=>'o', 'ö'=>'o', 'Ø'=>'o', 'ø'=>'o','Ù'=>'u', 'ù'=>'u', 'Ú'=>'u', 'ú'=>'u', 'Û'=>'u', 'û'=>'u', 'Ŭ' => 'u', 'ü'=>'u', 'Ü'=>'u', 'Ý'=>'y', 'ý'=>'y', 'Ÿ'=> 'y', 'ÿ'=>'y', 'ð'=>'d', 'Ð'=>'d', 'þ'=>'b', 'Þ'=>'b', 'ß'=>'b');
$prenom = strtr($prenom, $accents);
return $prenom;
}

function my_mb_ucfirst($prenom){
	$pre=mb_strtoupper(mb_substr($prenom, 0,1));
	return $pre.mb_substr($prenom, 1);
}

//https://stackoverflow.com/questions/5546120/php-capitalize-after-dash
function maj($prenom){
	$prenom = implode('-', array_map('my_mb_ucfirst', explode('-', $prenom)));
	$prenom = implode('\'', array_map('my_mb_ucfirst', explode('\'', $prenom)));
	$tab=explode('[- \']', $prenom);
	$prenom=implode('[- \']', $tab);
	return $prenom;
}


function retireTiretPrenom($prenom){
	$prenom = rtrim(ltrim($prenom, '[ -]'), '[ -]');
	return $prenom;
}

function interditPrenom($prenom){
	$regex="$\\\\$";
	$regex2="/[!$%^&*)(_+|~=}{€@\:;><?!,.]/";
	$regex3="$(.*--.*){2}$";
	$regex4="'/^[^\']*$/'";

	if(preg_match($regex, $prenom)){
		return -1;
		
	}else if(preg_match($regex2, $prenom)){
		return -1;

	}else if(preg_match($regex3, $prenom)){
		return -1;

	}else if(preg_match($regex4, $prenom)){
		return -1;

	}else if (strlen($prenom) > 40){
    	echo"interdit";

    }else if($prenom=="'"){
        return -1;

	}else if($prenom==""){
        return -1;

    }else{
		return 1;
	}	

}

function retireEspacePrenom($prenom){
	$prenom = preg_replace('/\s\s+/', ' ', $prenom);
	return $prenom;
}

function prenomValide($prenom){ //idem que pour le nom mais avec le prénom
    if (interditPrenom($prenom)){
      $prenom = remplacerAccentsPrenom($prenom);
      $prenom = retireTiretPrenom($prenom);
      $nom = retireEspacePrenom($prenom);
      return $nom;
    }
  }
?>