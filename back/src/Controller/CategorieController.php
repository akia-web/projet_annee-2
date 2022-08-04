<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Repository\AnnoncesRepository;
use App\Repository\CategoriesRepository;
use App\Service\CategorieService;
use Doctrine\Persistence\ManagerRegistry;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

class CategorieController extends AbstractController
{
    /**
     * @Route("api/categories", methods={"GET"})
    */
    public function GetAllCategories(CategoriesRepository $repoCategories, AnnoncesRepository $repoAnnonces)
    {
       $categories = $repoCategories->findAll();
       
       $response = [];
       for($i=0; $i<count($categories); $i++){
        $item = new stdClass();
        $item->id = $categories[$i]->getId();
        $item->name = $categories[$i]->getName();
        $item->annonces = count($repoAnnonces->findBy(array('categorie' => $item->id)));
       

        array_push($response, $item);
       }

      
        return new Response(json_encode($response, Response::HTTP_OK));
    }
    /**
     * @Route("api/categories", methods={"POST"})
    */
    public function postNewCategorie(ManagerRegistry $mr, HttpFoundationRequest $request){
        $manager = $mr->getManager();
        $categorie = new Categories();
        $requestCategorie = json_decode($request->getContent());
        $categorie->setName($requestCategorie->{'name'});
        $manager->persist($categorie);
        $manager->flush();

        $newCategorie = new stdClass();
        $newCategorie->id = $categorie->getId();
        $newCategorie->name = $categorie->getName();
        $newCategorie->annonces = 0;
        return new Response(json_encode($newCategorie, Response::HTTP_OK));
    }

    /**
     * @Route("api/categories/{id}", methods={"DELETE"})
    */

    public function deleteCategorie(ManagerRegistry $mr, Categories $categorie){
        $em = $mr->getManager();
        $em->remove($categorie);
        $em->flush();
        
        return new Response('ok', Response::HTTP_OK);
        
    }
}
