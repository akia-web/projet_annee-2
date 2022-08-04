<?php

namespace App\Service;

use stdClass;

class AnnoncesService{
    public function getDateFr($date){
        $nomDujour= AnnoncesService::getDayFR($date->format('l'));
        $dateJour = $date->format('d');
        $mois=AnnoncesService::getMonthFR($date->format('m'));
        $annee = $date->format('Y');
        
        return "$nomDujour $dateJour $mois $annee";
    }

    public function getHours($date){
        $heure = $date->format('H');
        $minute = $date->format('i');
        return "à $heure h $minute";
    }

    public function getDayFR($jour){
        switch ($jour){
            case 'Monday':
                return 'Lundi';
            case 'Tuesday':
                return 'Mardi';
            case 'Wednesday':
                return "Mercredi";
            case "Thursday":
                return "Jeudi";
            case "Friday":
                return "Vendredi";
            case "Saturday":
                return "Samedi";
            case "Sunday":
                return "Dimanche";
        }
    }

    public function getMonthFR($mois){
        switch ($mois){
            case '01':
                return 'janvier';
            case '02':
                return 'février';
            case '03':
                return "mars";
            case "04":
                return "avril";
            case "05":
                return "mai";
            case "06":
                return "juin";
            case "07":
                return "juillet";
            case '08':
                    return "aout";
            case "09":
                    return "septembre";
            case "10":
                    return "octobre";
            case "11":
                    return "novrembre";
            case "12":
                    return "décembre";
        }
    }

    public function getAnnonces($annonces){
        $result = [];
        for($i = 0; $i < count($annonces); $i++){
            $annonce = new stdClass();
            $annonce->id = $annonces[$i]->getId();
            $annonce->name = $annonces[$i]->getName();
            $annonce->description = $annonces[$i]->getDescription();
            $annonce->adresse = $annonces[$i]->getAdresse();
            $annonce->codePostal = $annonces[$i]->getCodepostal();
            $annonce->ville = $annonces[$i]->getVille();
            $annonce->images = $annonces[$i]->getImages();
            $annonce->date = AnnoncesService::getDateFr($annonces[$i]->getDate());
            $annonce->minute = AnnoncesService::getHours($annonces[$i]->getDate()); 
            $annonce->pseudo = $annonces[$i]->getUser()->getPseudo();
            $annonce->avatar = "http://localhost:8000/uploads/".$annonces[$i]->getUser()->getProfilImage();
           
            $categorie = new stdClass;
            $categorie->id = $annonces[$i]->getCategorie()->getId();
            $categorie->name = $annonces[$i]->getCategorie()->getName();
            $annonce->categorie = $categorie;
          
            array_push($result, $annonce);

        }
        return $result;
    }
}