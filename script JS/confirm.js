function confirm_deletion(){
    var result = confirm("voulez-vous supprimer ce coureur ? ");
    if (result){
        window.location.href = '../script PHP/delete_coureur.php';
    }else
        window.location.href = '../script PHP/modif_coureur.php';
}