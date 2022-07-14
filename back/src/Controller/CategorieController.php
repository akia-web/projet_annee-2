<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Service\CategorieService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CategorieController extends AbstractController
{
    /**
     * @Route("api/categories", methods={"GET"})
    */
    public function GetAllCategories(CategorieService $categorieService, CategoriesRepository $repo)
    {
       $categories = $categorieService->getAllCategories($repo);
       $encoder = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers,$encoder);
        $jsonContent = $serializer->serialize($categories, 'json', [
            'circular_reference_handler'=> function($object){
                return $object->getId();
            }
        ]);
   
        $response = new Response($jsonContent);

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
