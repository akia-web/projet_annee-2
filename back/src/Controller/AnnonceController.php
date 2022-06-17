<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\Categories;
use App\Entity\User;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncode;

class AnnonceController extends AbstractController
{
    /**
     * @Route("api/annonces", methods={"POST"})
     */
    public function createAnnonce(HttpFoundationRequest $request, ManagerRegistry $mr): Response
    {
        $manager = $mr->getManager();
        
        $annonce = new Annonces;
        $requestAnnonce = json_decode($request->getContent());
      

        $id = 138;
        $email ="ch.royer15@gmail.com";
        $categorieName = $requestAnnonce->{'categorie'};
        $date = new DateTime($requestAnnonce->{'date'}) ;

        $user = $mr->getRepository(User::class)->find($id);
        
        $findcategeorie = $mr->getRepository(Categories::class)->findBy(array('name' => $categorieName));
   
     
       
        $annonce->setUser($user);
        $annonce->setName($requestAnnonce->{'titre'});
        $annonce->setDescription($requestAnnonce->{'description'});
        $annonce->setCategorie($findcategeorie[0]);
        $annonce->setImages($requestAnnonce->{'image'});
        $annonce->setDate($date);
        $annonce->setAdresse($requestAnnonce->{'adresse'});
        $annonce->setCodepostal($requestAnnonce->{'codePostal'});
        $annonce->setVille($requestAnnonce->{'ville'});

        $manager->persist($annonce);
        $manager->flush();
        return new Response('ok', Response::HTTP_OK);

     
    }
    /**
     * @Route("api/annonces", methods={"GET"})
    */
    public function getAllAnnonces(ManagerRegistry $mr)
    {
       
        $findAllAnnonces = $mr->getRepository(Annonces::class)->findAll();
   
        return $this->json($findAllAnnonces) ;
    }
}
