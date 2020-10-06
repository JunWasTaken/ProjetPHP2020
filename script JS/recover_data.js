var n_coureur;
function recoverDataArray(param){

    n_coureur = document.getElementById('N_COUREUR='+param);
    console.log(n_coureur.innerHTML);

    //https://stackoverflow.com/questions/20770562/how-to-get-javascript-return-value-to-php-variable
    window.location.href="../script PHP/cookie_n_coureur.php?uid="+n_coureur.innerHTML;
}