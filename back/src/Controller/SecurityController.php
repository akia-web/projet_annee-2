<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
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
            $message = json_encode($user);

       }
  
        

        return new Response($message, Response::HTTP_OK);
    }

  

    /**
     * @Route("api/user", methods={"GET"})
     */
    public function findCurrentUser(HttpFoundationRequest $request, ManagerRegistry $mr, UserService $userService)
    {

        $response = $userService->getCurrentUser($request, $mr);
        // dd($response);
        $code = $response ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;
        $user = new User($response);

        // dd($response);

         return new Response(json_encode($response) , $code);
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

}
