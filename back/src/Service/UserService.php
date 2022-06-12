<?php

namespace App\Service;
use App\Entity\User;



class UserService{

    public function getCurrentUser($request, $mr){

        if( $request->query->get("email") == null|| $request->query->get("id") == null){
            return false;
        }
        

        $id = $request->query->get("id");
        $email =$request->query->get("email");

        $user = $mr->getRepository(User::class)->find($id);
        // dd($user->getId());

        if($user == null){
            return false;
        }
        
        if($user->getId() != $id || $user->getEmail() != $email){
            return false;
        }
        
        return true;

    }
}