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
            $annonce->authorId= $annonces[$i]->getUser()->getId();
            $categorie = new stdClass;
            $categorie->id = $annonces[$i]->getCategorie()->getId();
            $categorie->name = $annonces[$i]->getCategorie()->getName();
            $annonce->categorie = $categorie;
          
            array_push($result, $annonce);

        }
        return $result;
    }

    public function getOneAnnonce($item){
        $annonce = new stdClass();
        $annonce->id = $item->getId();
        $annonce->name = $item->getName();
        $annonce->description = $item->getDescription();
        $annonce->adresse = $item->getAdresse();
        $annonce->codePostal = $item->getCodepostal();
        $annonce->ville = $item->getVille();
        $annonce->images = $item->getImages();
        $annonce->date = AnnoncesService::getDateFr($item->getDate());
        $annonce->minute = AnnoncesService::getHours($item->getDate()); 
        $annonce->pseudo = $item->getUser()->getPseudo();
        $annonce->avatar = "http://localhost:8000/uploads/".$item->getUser()->getProfilImage();
        $annonce->authorEmail = $item->getUser()->getEmail();
        $annonce->authorId= $item->getUser()->getId();
        $categorie = new stdClass;
        $categorie->id = $item->getCategorie()->getId();
        $categorie->name = $item->getCategorie()->getName();
        $annonce->categorie = $categorie;
      
        return $annonce;
    }
    public function tronquer($text){
        $max = 40;  

        if (strlen($text) >= $max) {
          $text = substr($text, 0, $max)."...";  
          
        }
        return $text;
    }
   
}