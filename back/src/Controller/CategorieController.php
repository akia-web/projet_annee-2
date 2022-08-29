<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Repository\AnnoncesRepository;
use App\Repository\CategoriesRepository;
use Doctrine\Persistence\ManagerRegistry;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        $item->image ="http://localhost:8000/uploads/categories/". $categories[$i]->getImage();
        $item->adminApproval = $categories[$i]->getAdminApproval();
        $item->annonces = count($repoAnnonces->findByCategorieAndDateAfterNowAndIsApproval($categories[$i]->getId(),date("Y-m-d H:i:s") ));
       

        array_push($response, $item);
       }
      
        return new Response(json_encode($response, Response::HTTP_OK));
    }
    /**
     * @Route("api/categories", methods={"POST"})
    */
    public function postNewCategorie(ManagerRegistry $mr, HttpFoundationRequest $request, CategoriesRepository $repo){
        // dd($request->request->get('name'));
        $manager = $mr->getManager();
        $categorie = new Categories();
        $nameCategorie =  $request->request->get('name');
        $maybeCategorie = $repo->findOneBy(['name'=>$nameCategorie]);

        if($maybeCategorie != null){
            return new Response( 'catégorie déjà existante', Response::HTTP_BAD_REQUEST);
        }
        $adminApproval =  $request->request->get('adminApproval');
       
        $image = $request->files->get('image');
        $fichier = $nameCategorie.'.'.$image->guessExtension();

        // On copie le fichier dans le dossier uploads
        $image->move(
           $this->getParameter('images_categories_directory'),
          $fichier
       );
        $categorie->setName($nameCategorie);
        $categorie->setImage($fichier);
        $categorie->setAdminApproval(filter_var($adminApproval, FILTER_VALIDATE_BOOLEAN) );
        $manager->persist($categorie);
        $manager->flush();
        

        $newCategorie = new stdClass();
        $newCategorie->id = $categorie->getId();
        $newCategorie->name = $categorie->getName();
        $newCategorie->image = "http://localhost:8000/uploads/categories/". $categorie->getImage();
        $newCategorie->adminApproval = $categorie->getAdminApproval();
        $newCategorie->annonces = 0;
        return new Response(json_encode($newCategorie, Response::HTTP_OK));
    }

    /**
     * @Route("api/categories/{id}", methods={"DELETE"})
    */

    public function deleteCategorie(ManagerRegistry $mr, Categories $categorie){
        $ancienneImageExist = file_exists($this->getParameter('images_categories_directory')."/".$categorie->getImage());
        if($ancienneImageExist){
            unlink($this->getParameter('images_categories_directory')."/".$categorie->getImage());
        }
        $em = $mr->getManager();
        $em->remove($categorie);
        $em->flush();
        
        return new Response('ok', Response::HTTP_OK);
        
    }

    /**
     * @Route("api/modifCategories", methods={"POST"})
    */
    public function modifCategorie(HttpFoundationRequest $request, CategoriesRepository $repo, ManagerRegistry $mr){
        $id = $request->request->get('id');
        $maybeCategorie = $repo->find($id);
        
        if($maybeCategorie == null){
            return new Response('categorie non trouvé', Response::HTTP_BAD_REQUEST);
        }

        $name = $request->request->get('name');
       
        $adminApproval =  $request->request->get('adminApproval');
       
        
        //on regarde si une autre categorie n'a pas le meme nom
        $getCategorieByName = $repo->findOneBy(["name"=> $name]);
       
        if($getCategorieByName != null ){
        
            if($getCategorieByName->getId() != $id){
                return new Response('catégorie déjà existante', Response::HTTP_BAD_REQUEST);
            }
        }


       
      

        if($request->files->get('image') !=null){

          
            
            $image = $request->files->get('image');
            if($image->guessExtension() != "png"){

                return new Response('pas le bon type de fichier', Response::HTTP_FORBIDDEN);
            
            }else{

                // on supprime l'ancienne image si le nom de la catégorie est différentes
                if($name != $maybeCategorie->getName()){
                    $ancienneImageExist = file_exists($this->getParameter('images_categories_directory')."/".$maybeCategorie->getImage());
                    if($ancienneImageExist){
                        unlink($this->getParameter('images_categories_directory')."/".$maybeCategorie->getImage());
                    }

                }

                // on ajoute la nouvelle image
                $fichier = $name.'.'.$image->guessExtension();

                $image->move(
                   $this->getParameter('images_categories_directory'),
                  $fichier
               );

               $maybeCategorie->setImage($fichier);

            }
           
        }else{
            $image = file_get_contents($this->getParameter('images_categories_directory')."/".$maybeCategorie->getImage());
            $image.rename($this->getParameter('images_categories_directory')."/".$maybeCategorie->getImage(), $this->getParameter('images_categories_directory')."/".$name.".png");
            $maybeCategorie->setImage($name.".png");
       
        }
      
        $maybeCategorie->setName($name);
        $maybeCategorie->setAdminApproval(filter_var($adminApproval, FILTER_VALIDATE_BOOLEAN));
        $manager = $mr->getManager();
        $manager->persist($maybeCategorie);
        $manager->flush();

        
        return new Response('ok', Response::HTTP_OK);


        
    }
}
