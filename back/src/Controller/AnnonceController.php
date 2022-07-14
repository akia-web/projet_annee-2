<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\Categories;
use App\Entity\User;
use App\Repository\AnnoncesRepository;
use App\Service\EncoderService;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
      

        $id = $requestAnnonce->{'idUser'};
        $email =$requestAnnonce->{'emailUser'};
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
    public function getAllAnnonces(AnnoncesRepository $repo)
    {
       
        $findAllAnnonces = $repo->findAll();
        
        $encoder = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers,$encoder);
        $jsonContent = $serializer->serialize($findAllAnnonces, 'json', [
            'circular_reference_handler'=> function($object){
                return $object->getId();
            }
        ]);
   
        $response = new Response($jsonContent);

        $response->headers->set('Content-Type', 'application/json');
        return $response;

       
    }


    /**
     * @Route("api/Allannonces", methods={"GET"})
    */
    public function getAllAnnonces2(AnnoncesRepository $repo)
    {
       
        $findAllAnnonces = $repo->findBy([], ['date' => 'DESC']);
        $result = [];
        for($i = 0; $i<count($findAllAnnonces); $i++){
            $annonce = new stdClass();
            $annonce->id = $findAllAnnonces[$i]->getId();
            $annonce->name = $findAllAnnonces[$i]->getName();
            $annonce-> date = $findAllAnnonces[$i]->getDate();
            $annonce-> images = $findAllAnnonces[$i]->getImages();
            $annonce-> categorie = $findAllAnnonces[$i]->getCategorie()->getName();
            $annonce-> adresse = $findAllAnnonces[$i]->getAdresse();
            $annonce-> ville = $findAllAnnonces[$i]->getVille();
            $annonce-> codepostal = $findAllAnnonces[$i]->getCodepostal();
            $annonce-> description = $findAllAnnonces[$i]->getDescription();

            array_push($result, $annonce);

        }
  
        return new Response(json_encode($result))  ;

       
    }

    /**
     * @Route("api/annonces/{idCategorie}", methods={"GET"})
    */

    public function getAnnoncesByName(AnnoncesRepository $repo, string $idCategorie, EncoderService $encode){
        $findAnnonce = $repo->findBy(array('categorie' => $idCategorie));
        $encodeJson = $encode->encode($findAnnonce);
        $response = new Response($encodeJson);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    
    /**
     * @Route("api/annoncesByUser/{idUser}", methods={"GET"})
    */

    public function getAnnoncesByUser(AnnoncesRepository $repo, string $idUser, EncoderService $encode){
        $findAnnonce = $repo->findBy(array('user' => $idUser));
        $encodeJson = $encode->encode($findAnnonce);
        $response = new Response($encodeJson);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


     /**
     * @Route("api/annonces/{id}", methods={"DELETE"})
    */

    public function deleteAnnonce(ManagerRegistry $mr, Annonces $annonce){
        $em = $mr->getManager();
        $em->remove($annonce);
        $em->flush();
        
        return new Response('ok', Response::HTTP_OK);
        
    }

     
    /**
     * @Route("api/annoncesById/{annonceId}", methods={"GET"})
    */

    public function getAnnoncesById(AnnoncesRepository $repo, string $annonceId, EncoderService $encode){
        $findAnnonce = $repo->findBy(array('id' => $annonceId));
        $encodeJson = $encode->encode($findAnnonce);
        $response = new Response($encodeJson);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

     /**
     * @Route("api/annonces/{id}", methods={"PUT"})
     */
    public function ModifAnnonce(HttpFoundationRequest $request, ManagerRegistry $mr, string $id): Response
    {
        $manager = $mr->getManager();
        
        $annonce = new Annonces;
        $requestAnnonce = json_decode($request->getContent());
      

        $idUser = $requestAnnonce->{'idUser'};
        $email =$requestAnnonce->{'emailUser'};
        $categorieName = $requestAnnonce->{'categorie'};
        $date = new DateTime($requestAnnonce->{'date'}) ;

        $user = $mr->getRepository(User::class)->find($idUser);
        
        $findcategeorie = $mr->getRepository(Categories::class)->findBy(array('name' => $categorieName));
   
        $annonce = $mr->getRepository(Annonces::class)->find($id);
        
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
}
