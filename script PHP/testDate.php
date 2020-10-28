<?php
    function prem_plus_grande_naissance($prem, $naissance){ //vérifie que la date de première participation est bien plus grande de 20ans de la date de naissance
        if (!($prem>=($naissance+20)))
            throw new Exception("La date de première participation n'est pas assez grande");

        return true;
    }

    function debut_sup_naissance($debut, $naissance){ //vérifie que la date_début est sup ou égale à la date de naissance
        if (!($debut>=$naissance))
            throw new Exception("La date de début est inférieure à l'année de naissance, c'est impossible");

        return true;
    }

    function debut_inf_prem($debut, $prem){
        if (!($debut<=$prem))
            throw new Exception("La date de début n'est pas inférieure à la date de première participation");
        
        return true;
    }

    function annee_naissance_valide($naissance, $annee){
        if (!($naissance<=($annee+60)))
            throw new Exception("La date de naissante n'est pas valide");

        return true;
    }

    function test_annee_complet($annee, $naissance, $debut, $prem){
        if (isset($naissance) && isset($prem) && isset($debut)){
            prem_plus_grande_naissance($prem, $naissance);
            debut_sup_naissance($debut, $naissance);
            debut_inf_prem($debut, $prem);
            annee_naissance_valide($naissance, $annee);
        }else if (isset($naissance) && isset($prem)){
            annee_naissance_valide($naissance, $annee);
            prem_plus_grande_naissance($prem, $naissance);
        }else if (isset($naissance) && isset($debut)){
            debut_sup_naissance($debut, $naissance);
        }else if (isset($debut) && isset($prem))
            debut_inf_prem($debut, $prem);

        return true;
    }
?>