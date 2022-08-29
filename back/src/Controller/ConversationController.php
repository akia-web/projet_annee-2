<?php

namespace App\Controller;

use App\Entity\Conversations;
use App\Entity\Messages;
use App\Repository\ConversationsRepository;
use App\Repository\UserRepository;
use App\Service\AnnoncesService;
use Doctrine\Persistence\ManagerRegistry;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

class ConversationController extends AbstractController
{
    // #[Route('/conversation', name: 'app_conversation')]
    // public function index(): Response
    // {
    //     return $this->render('conversation/index.html.twig', [
    //         'controller_name' => 'ConversationController',
    //     ]);
    // }

     /**
     * @Route("api/conversation", methods={"POST"})
     */
    public function createConversationOrSendNewMessage(HttpFoundationRequest $request, ConversationsRepository $repo, ManagerRegistry $mr, UserRepository $userRepo, AnnoncesService $annoncesService){
        
     
        
        $requestConversation = json_decode($request->getContent());
        $findExpediteur = $userRepo->find($requestConversation->{'expediteur'});
        $findParticipant2 = $userRepo->find($requestConversation->{'participant2'});
        $messageEnvoyer = $requestConversation->{'message'};
        if($requestConversation->{'expediteur'} == $requestConversation->{'participant2'}){
            return new Response('Vous ne pouvez-pas vous envoyer un message à vous meme', Response::HTTP_BAD_REQUEST);
        }
       
        //on vérifie que les deux participants éxistent : 
        if($findExpediteur == null || $findParticipant2 == null){
            return new Response('utilisateurs non trouvé', Response::HTTP_NOT_FOUND);
        }
        
        //recherche s'il y a une conversation existante 
        $maybeConv = $repo->findByparticipant1Orparticipant2($requestConversation->{'expediteur'},$requestConversation->{'participant2'});
        // dd($maybeConv);
        $convExist = true;
        // si elle n'existe pas : on la créer
       if($maybeConv == null){
        $manager = $mr->getManager();
        $conversation = new Conversations();
        $conversation->setParticipant1($findExpediteur);
        $conversation->setParticipant2($findParticipant2);
        $manager->persist($conversation);
        $manager->flush();
        $maybeConv = $conversation;
        $convExist = false;
       }

       // ajout du message
      
       $date = \DateTime::createFromFormat('Y-m-d H:i:s', strtotime('now'));
       
       $manager2 = $mr->getManager();
       $message = new Messages();
       $message->setExpediteur($findExpediteur);
       if($convExist){
        $message->setConversation($maybeConv[0]);
       }else{
        $message->setConversation($maybeConv);
       }
      
       $message->setDate(new \DateTime());
       $message->setMessage($messageEnvoyer);
       $message->setVu(false);
       $manager2->persist($message);
       $manager2->flush();

       $newConversation = new stdClass();
       $newConversation->message = $message->getMessage();
       $newConversation->pseudo = $message->getExpediteur()->getPseudo();
       $newConversation->image = "http://localhost:8000/uploads/".$message->getExpediteur()->getProfilImage();
       $newConversation->date = $message->getDate()->format('d/m/Y');
       $newConversation->heure =  $annoncesService->getHours($message->getDate());
       $conversationInfo = new stdClass();
       $conversationInfo->id = $message->getConversation()->getId();
       $conversationInfo->participant1 = $message->getConversation()->getParticipant1()->getPseudo();
       $conversationInfo->participant2 = $message->getConversation()->getParticipant2()->getPseudo();
       $conversationInfo->imageDestinataire = "http://localhost:8000/uploads/".$findParticipant2->getProfilImage();
       $newConversation->conversation = $conversationInfo;

        return new Response(json_encode($newConversation), Response::HTTP_OK);
    }

    /**
     * @Route("api/conversation/{id}", methods={"GET"})
     */
    public function getMessageByUserAndUtilisateur(ConversationsRepository $repo, int $id, AnnoncesService $annoncesService){
        $conversation = $repo->find($id);
        $messages = $conversation->getMessages();
        $result = [];
        for($i = 0; $i<count($messages); $i++){
            $message = new stdClass();
            $message->expediteur = $messages[$i]->getExpediteur()->getId();
            $message->message = $messages[$i]->getMessage();
            $message->date = $messages[$i]->getDate()->format('d/m/Y');
            $message->heure =  $annoncesService->getHours($messages[$i]->getDate());
            $message->vu = $messages[$i]->getVu();
            $message->image = "http://localhost:8000/uploads/".$messages[$i]->getExpediteur()->getProfilImage();
            $message->pseudo = $messages[$i]->getExpediteur()->getPseudo(); 

            array_push($result,$message);
        }
        
        return new Response(json_encode($result), Response::HTTP_OK);
    }   
    /**
     * @Route("api/conversation", methods={"GET"})
     */
    public function getConversationByCurrentUser(ConversationsRepository $repo, HttpFoundationRequest $request){
        $id = $request->query->get("id");
        $findAllConversation = $repo->findByCurrentUser($id);
        $result = [];
        for($i = 0; $i < count($findAllConversation); $i++){
            $conversation = new stdClass();
            $conversation->id = $findAllConversation[$i]->getId();
            if($findAllConversation[$i]->getParticipant1()->getId() == $id){
                $conversation->pseudo = $findAllConversation[$i]->getParticipant2()->getPseudo();
                $conversation->image = "http://localhost:8000/uploads/".$findAllConversation[$i]->getParticipant2()->getProfilImage();
                $conversation->destinataire =  $findAllConversation[$i]->getParticipant2()->getId();
            }else{
                $conversation->pseudo = $findAllConversation[$i]->getParticipant1()->getPseudo();
                $conversation->image = "http://localhost:8000/uploads/".$findAllConversation[$i]->getParticipant1()->getProfilImage();
                $conversation->destinataire =  $findAllConversation[$i]->getParticipant1()->getId();
            }
            array_push($result, $conversation);
        }
        return new Response(json_encode($result, Response::HTTP_OK));
    }

}
