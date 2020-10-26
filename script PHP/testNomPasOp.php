<?php 
//tout les test écrits dans le fichie ods
$tab[1] = "Ébé-ébé";
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
$tab[31] = "Æ'-’nO";
$tab[32] = "çççç ççç ÇÇÇÇ ÇÇÇ";
$tab[33] = "àâäéèêëïîôöùûüÿç";
$tab[34] = "ÀÂÄÉÈÊËÏÎÔÖÙÛÜŸÇ";
$tab[36] ="Ø";


foreach($tab as $var){
	$enMaj=remplacerAccents($var);
	$enMaj=remplacerApostrophes($enMaj);
	$enMaj=strtoupper($enMaj);
	$enMaj=retireTiret($enMaj);
	retireEspaceApostrophe($enMaj);
	retireEspace($enMaj);
	interdit($enMaj);	
	echo'<br / &nbsp>';
}

function remplacerAccents($nom){
//Lien du tableau : https://stackoverflow.com/questions/3371697/replacing-accented-characters-php
	
$accents = array('Š'=>'S', 'š'=>'S', 'Ž'=>'Z', 'ž'=>'Z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'AE', 'Ç'=>'C', 'È'=>'E', 'É'=>'E','Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U','Ú'=>'U', 'Û'=>'U', 'Ŭ' => 'U', 'Ü'=>'U', 'Ý'=>'Y', 'Ÿ'=> 'Y' , 'Þ'=>'B', 'ß'=>'B', 'à'=>'A', 'á'=>'A', 'â'=>'A', 'ã'=>'A', 'ä'=>'A', 'å'=>'A', 'æ'=>'AE', 'ç'=>'C','è'=>'E', 'é'=>'E', 'ê'=>'E', 'ë'=>'E', 'ì'=>'I', 'í'=>'I', 'î'=>'I', 'ï'=>'I', 'ð'=>'O', 'ñ'=>'N', 'ò'=>'O', 'ó'=>'O', 'ô'=>'O', 'õ'=>'O','ö'=>'O', 'ø'=>'O', 'ù'=>'U', 'ú'=>'U', 'û'=>'U', 'ü'=>'U', 'ý'=>'Y', 'þ'=>'B', 'ÿ'=>'Y', '®'=>'R', 'œ' => "OE" );
$nom = strtr($nom, $accents);
return $nom;
}

function remplacerApostrophes($nom){
	$apostrophe=array("’"=>"'", "ʾ"=>"'", "′"=>"'", "ˊ"=>"'", "ꞌ"=>"'", "‘"=>"'", "ʿ"=>"'", "‵"=>"'", "ˋ"=>"'" ,"''"=> "' '	");
	$nom = strtr($nom, $apostrophe);
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
	$regex4="~^[a-zA-ZÀ-ÖØ-öø-ÿœŒ\-'\s']+$~u";
	$regex5="/[a-zA-Z]/";

    if(preg_match($regex1, $nom)){
		echo "Erreur de saisie";
		return -1;
	}else if(preg_match($regex2, $nom)){
		echo "Erreur de saisie";
		return -1;
	}else if(preg_match($regex3, $nom)){
		echo "Erreur de saisie";
		return -1;
	}else if(strlen($nom) > 35){
		echo "Erreur de saisie";
		return -1;
	}else if (preg_match($regex4, $nom) && preg_match($regex5, $nom)) {
		return 1;
    }else {
    	echo "Erreur de saisie";
	}
}

function retireEspaceApostrophe($nom){
	if(preg_match("#( )*#",$nom)) 
    	return preg_replace("#( )*#", "", $nom);
	return $nom;
}

function retireEspace($nom){
	$nom = preg_replace('/\s\s+/', ' ', $nom);
	return $nom;
}
?>