<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\AnnoncesFollow;
use App\Entity\User;
use App\Repository\AnnoncesFollowRepository;
use App\Service\EncoderService;
use Doctrine\ORM\Mapping\Id;
use Doctrine\Persistence\ManagerRegistry;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FollowAnnonceController extends AbstractController
{
    /**
     * @Route("api/followAnnonce", methods={"POST"})
     */
    public function FollowAnnonce(HttpFoundationRequest $request, ManagerRegistry $mr): Response
    {
        $manager = $mr->getManager();
        
        $annonceFollow = new AnnoncesFollow;
        $requestAnnonce = json_decode($request->getContent());
      
        if(!isset($requestAnnonce->{'userId'}) ||
        !isset( $requestAnnonce->{'emailUser'}) || 
        !isset($requestAnnonce->{'idAnnonce'}) ){
            return new Response("champs non renseignés" , Response::HTTP_BAD_REQUEST);
        }
        $userId = $requestAnnonce->{'userId'};
        $email =$requestAnnonce->{'emailUser'};
        $idAnnonce = $requestAnnonce->{'idAnnonce'};

        // trouve l'utilisateur
        $user = $mr->getRepository(User::class)->find($userId);
        
        if($user==null){
            return new Response("utilisateur non reconnu" , Response::HTTP_BAD_REQUEST);
        }
        if($email != $user->getEmail()){
            return new Response("utilisateur non reconnu" , Response::HTTP_BAD_REQUEST);
        }

        // trouve l'annonce
        $annonce = $mr->getRepository(Annonces::class)->find($idAnnonce);
        
        if($annonce==null){
            return new Response("Annonce non trouvée", Response::HTTP_BAD_REQUEST);
        }

        // cherche si c'est deja assigné

        $findFollow = $mr->getRepository(AnnoncesFollow::class)->findBy(  ['user' => $userId, 'Annonces'=> $idAnnonce]);
       
        if($findFollow != null){
            return new Response("Vous participez déjà a  cette annonce" , Response::HTTP_BAD_REQUEST);
        }

        $annonceFollow->setUser($user);
        $annonceFollow->setAnnonces($annonce);
        $manager->persist($annonceFollow);
        $manager->flush();
        return new Response('ok', Response::HTTP_OK);


    }

    /**
     * @Route("api/isFollowAnnonce", methods={"GET"})
    */

    public function isCurrentUserFollowThisAnnounce(ManagerRegistry $mr, HttpFoundationRequest $request){
        if( $request->query->get("userId") == null|| $request->query->get("idAnnonce") == null){
            return new Response("erreur" , Response::HTTP_BAD_REQUEST);
        }
        $userId = $request->query->get("userId");
        $idAnnonce = $request->query->get("idAnnonce");

        $findFollow = $mr->getRepository(AnnoncesFollow::class)->findBy(  ['user' => $userId, 'Annonces'=> $idAnnonce]);
        if($findFollow!=null){
            return new Response(true, Response::HTTP_OK);
        } else {
            return new Response(false, Response::HTTP_OK);
        }
    }


    /**
     * @Route("api/followAnnonce/{id}/{annonceId}", methods={"DELETE"})
    */

    public function deleteAnnonce(ManagerRegistry $mr, int $id, int $annonceId){
       
       
        $annonceFollow = $mr->getRepository(AnnoncesFollow::class)->findBy( ['user' => $id, 'Annonces'=> $annonceId]);
        $em = $mr->getManager();
        $em->remove($annonceFollow[0]);
        $em->flush();
        
        return new Response('ok', Response::HTTP_OK);
        
    }

    /**
     * @Route("api/allFollowAnnonceByUser/{userId}", methods={"GET"})
    */
    public function getAllFollowAnnonceByUser(AnnoncesFollowRepository $repo, int $userId, EncoderService $encode){
        $findAnnonceFollow = $repo->findBy(array('user' => $userId));
        $tableau = [];
       
       

        for($i =0 ; $i<count($findAnnonceFollow); $i++){
            $annonce = new stdClass();
            $annonce->{'Followid'} = $findAnnonceFollow[$i]->getId();
            $annonce->{'id'}= $findAnnonceFollow[$i]->getAnnonces()->getId();
            $annonce->{'name'}= $findAnnonceFollow[$i]->getAnnonces()->getName();
            $annonce->{'images'}= $findAnnonceFollow[$i]->getAnnonces()->getImages();
            $annonce->{'description'}= $findAnnonceFollow[$i]->getAnnonces()->getDescription();
            $annonce->{'adresse'}= $findAnnonceFollow[$i]->getAnnonces()->getAdresse();
            $annonce->{'ville'}= $findAnnonceFollow[$i]->getAnnonces()->getVille();
            $annonce->{'codepostal'}= $findAnnonceFollow[$i]->getAnnonces()->getCodepostal();
            $annonce->{'date'}= $findAnnonceFollow[$i]->getAnnonces()->getDate();
            array_push($tableau,$annonce);
        }
        
        return new Response(json_encode($tableau));

    }
}
