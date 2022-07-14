<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\EncoderService;
use App\Service\UserService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


use stdClass;


class SecurityController extends AbstractController
{

    /**
     * @Route("api/createOneUser", methods={"POST"})
     */
    public function createOneUser(ManagerRegistry $mr, HttpFoundationRequest $request, UserPasswordHasherInterface $passwordHash){
        $manager = $mr->getManager();
        
        $user = new User();
        $makeUser = json_decode($request->getContent());
  
      
        $getPassword = $makeUser->{"password"};
       
        $hashPassword = $passwordHash->hashPassword($user, $getPassword);
       
        $user->setEmail($makeUser->{'email'});
        $user->setPassword($hashPassword);
        $user->setRoles(["user"]);

        $manager->persist($user);
        $manager->flush();
        return new Response('ok', Response::HTTP_OK);
    }


     /**
     * @Route("api/register", methods={"POST"})
     */
    public function register(HttpFoundationRequest $request, UserRepository $userRepository): Response
    {
        $requestUser = json_decode($request->getContent());
        $maybeUser = $userRepository->findOneBy(['email' => $requestUser->{'email'}]);
       

       
        $hashPassword = $requestUser->{'password'};
        

        if($maybeUser==null){
            $message = "Compte non existant";
        }
        $password = password_verify($hashPassword,$maybeUser->getPassword());
       if($password == false){
           
            return new Response('identifiants incorrects', Response::HTTP_BAD_REQUEST);

       }else{
       
            if(!isset($_SESSION["user"])){
                session_start();
                $_SESSION["user"]=$maybeUser->getEmail();
            }
            $user = new stdClass();
            $user->email = $maybeUser->getEmail();
            $user->id = $maybeUser->getId();
            $user->role = $maybeUser->getRoles()[0];
            $message = json_encode($user);

       }
  
        

        return new Response($message, Response::HTTP_OK);
    }

  

    /**
     * @Route("api/user", methods={"GET"})
     */
    public function findCurrentUser(HttpFoundationRequest $request, ManagerRegistry $mr, UserService $userService, EncoderService $encode)
    {

        // $response = $userService->getCurrentUser($request, $mr);
        // dd($response);
        $response = null;
        if( $request->query->get("email") == null|| $request->query->get("id") == null){
            return new Response(json_encode($response) , Response::HTTP_BAD_REQUEST);
        }
        

        $id = $request->query->get("id");
        $email =$request->query->get("email");

        $user = $mr->getRepository(User::class)->find($id);
        // dd($user->getId());

        if($user == null){
             return new Response(json_encode($response) , Response::HTTP_BAD_REQUEST);
        }
        
        if($user->getId() != $id || $user->getEmail() != $email){
            return new Response(json_encode($response) , Response::HTTP_BAD_REQUEST);
        }

        $getUser = new stdClass();
        $getUser->email = $user->getEmail();
        $getUser->role = $user->getRoles()[0];
        $getUser->id = $user->getId();
        $getUser->pseudo = $user->getPseudo();
        $getUser->image = $user->getProfilImage();
        $message = json_encode($getUser);

        // $encodeJson = $encode->encode($user);
        // $response = new Response($encodeJson);
        // $response->headers->set('Content-Type', 'application/json');
        // return $response;
       
        return new Response($message, Response::HTTP_OK);

        // $code = $response ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;
        // $user = new User($response);

        // dd($response);

        //  return new Response(json_encode($response) , $code);
        // return $user;
    
    }
  


    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    /**
     * @Route("api/updateEmail/{id}", methods={"PUT"})
     */
    public function updateEmail(ManagerRegistry $mr, HttpFoundationRequest $request, int $id){
        $manager = $mr->getManager();
        $makeUser = json_decode($request->getContent());
        $user = $mr->getRepository(User::class)->find($id);
        
        $user->setEmail($makeUser->{'email'});

        $manager->persist($user);
        $manager->flush();
        return new Response('ok', Response::HTTP_OK);
    }
    /**
     * @Route("api/updatePseudo/{id}", methods={"PUT"})
     */
    public function updatePseudo(ManagerRegistry $mr, HttpFoundationRequest $request, int $id){
        $manager = $mr->getManager();
        $makeUser = json_decode($request->getContent());
        $user = $mr->getRepository(User::class)->find($id);
        
        $user->setPseudo($makeUser->{'pseudo'});
        $manager->persist($user);
        $manager->flush();
        return new Response('ok', Response::HTTP_OK);
    }



}
