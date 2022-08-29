<?php

namespace App\Controller;

use App\Entity\Gallerie;
use App\Repository\GallerieRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;


class GallerieController extends AbstractController
{
    /**
     * @Route("api/gallerie", methods={"POST"})
    */
    public function postNewCategorie(ManagerRegistry $mr, HttpFoundationRequest $request, GallerieRepository $gallerieRepo, UserRepository $userRepo){
        $manager = $mr->getManager();
        $gallerie = new Gallerie();
        $userId =  $request->request->get('userId');
       
        $user = $userRepo->find($userId);
        if($user == null){
            return new Response('Utilisateur non existant', Response::HTTP_BAD_GATEWAY);
        }

        $image = $request->files->get('image');
        $fichier =  md5(uniqid()).'.'.$image->guessExtension();

        // On copie le fichier dans le dossier uploads
        $image->move(
           $this->getParameter('image_gallerie_directory'),
          $fichier
       );
        $gallerie->setName($fichier);
        $gallerie->setUser($user);
       
        $manager->persist($gallerie);
        $manager->flush();
        

        $newGallerie = new stdClass();
        $newGallerie->id = $gallerie->getId();
        $newGallerie->image = "http://localhost:8000/uploads/gallerie/". $gallerie->getName();
        $newGallerie->userId = $gallerie->getUser()->getId();
        $newGallerie->pseudo = $gallerie->getUser()->getPseudo();
        $newGallerie->avatar = "http://localhost:8000/uploads/".$gallerie->getUser()->getProfilImage();

        return new Response(json_encode($newGallerie, Response::HTTP_OK));
    }

    /**
     * @Route("api/gallerie", methods={"GET"})
    */
    public function getGallerie(GallerieRepository $repo){
        $findAllImage = $repo->findAll();
     
        $result = [];
        for($i = 0; $i < count($findAllImage); $i++){
            $image = new stdClass();
            $image->id = $findAllImage[$i]->getId();
            $image->image = "http://localhost:8000/uploads/gallerie/". $findAllImage[$i]->getName();
            $image->userId = $findAllImage[$i]->getUser()->getId();
            $image->pseudo = $findAllImage[$i]->getUser()->getPseudo();
            $image->avatar = "http://localhost:8000/uploads/".$findAllImage[$i]->getUser()->getProfilImage();
            array_push($result, $image);
        }   

        return new Response(json_encode($result), Response::HTTP_OK);
    }

      /**
     * @Route("api/gallerie/{id}", methods={"DELETE"})
     */
    public function deleteImage(ManagerRegistry $mr, Gallerie $gallerie){
        $image = $gallerie->getName();
        
        $em = $mr->getManager();
        $em->remove($gallerie);
        $em->flush();

        if($image != null){
            $ancienneImageExist = file_exists($this->getParameter('image_gallerie_directory')."/".$image);
            if($ancienneImageExist){
                unlink($this->getParameter('image_gallerie_directory')."/".$image);
            }
        }
        
        
        return new Response('ok', Response::HTTP_OK);
        
    }
}
