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
$tab[22] ="'";
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
$tab[35] = "ØØ";

mb_internal_encoding("UTF-8");

foreach($tab as $var){
	$enMin=mb_strtolower($var);
	$enMin=retireTiret($enMin);
	$enMin=my_mb_ucfirst($enMin);
	$enMin=ucwords($enMin);
	$enMin=maj($enMin);
	$enMin=remplacerAccents($enMin);
	$enMin=remplacerApostrophes($enMin);
	retireEspace($enMin);
	interdit($enMin);
	echo'<br / &nbsp>';
}

function remplacerAccents($prenom){
//Lien du tableau : https://stackoverflow.com/questions/3371697/replacing-accented-characters-php
	
$accents = array('Š'=>'S', 'š'=>'S', 'Ž'=>'Z', 'ž'=>'Z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'Ae', 'Ç'=>'C', 'È'=>'E', 'É'=>'E','Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U','Ú'=>'U', 'Û'=>'U', 'Ŭ' => 'U', 'Ü'=>'U', 'Ý'=>'Y', 'Ÿ'=> 'Y' , 'Þ'=>'B', 'ß'=>'B', 'ø'=> 'o', 'ñ'=>'n' );
$prenom = strtr($prenom, $accents);
return $prenom;
}

function remplacerApostrophes($prenom){
	$apostrophe=array("’"=>"'", "ʾ"=>"'", "′"=>"'", "ˊ"=>"'", "ꞌ"=>"'", "‘"=>"'", "ʿ"=>"'", "‵"=>"'", "ˋ"=>"'" );
	$prenom = strtr($prenom, $apostrophe);
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


function retireTiret($prenom){
	$prenom = rtrim(ltrim($prenom, '[ -]'), '[ -]');
	return $prenom;
}

function interdit($nom){
	$regex1="$(.*--.*){2}$";
	$regex2="'/^[^\']*$/'";
	$regex3="$\\\\$";
	$regex4="~^[a-zA-ZÀ-ÖØ-öø-ÿœŒ\-'\s']+$~u";
	$regex5="/[a-zA-Z]/";

    if(preg_match($regex1, $nom)){
		echo "Erreur de saisie";

	}else if(preg_match($regex2, $nom)){
		echo "Erreur de saisie";

	}else if(preg_match($regex3, $nom)){
		echo "Erreur de saisie";

	}else if(strlen($nom) > 35){
    	echo "Erreur de saisie";

	}else if (preg_match($regex4, $nom) && preg_match($regex5, $nom)) {
    	echo"$nom";

    }else {
    echo "Erreur de saisie";
	}
}

/*function remplacerApostrophe($prenom){
	$regex="’ʾ′ˊˈꞌ‘ʿ‵ˋ";
	$regex2="'"
	if(preg_match($regex, $prenom)){
		preg_replace($regex, $regex2, $prenom);
	}
}*/

function retireEspace($prenom){
	$prenom = preg_replace('/\s\s+/', ' ', $prenom);
	return $prenom;
}
?>