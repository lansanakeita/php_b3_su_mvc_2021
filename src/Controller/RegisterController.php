<?php

namespace App\Controller;
use App\Entity\User;
use App\Routing\Attribute\Route;
use DateTime;
use Doctrine\ORM\EntityManager;

class RegisterController extends AbstractController
{
  //#[Route(path: "/")]
  #[Route(path: "/")]
  public function register(EntityManager $em)
  {
  
    echo $this->twig->render('security/register.html.twig');
   
   /* if(!empty($_POST)){
        $user = new User();
        if (isset($_POST)) {
            $_POST['username']    =  $user->getUsername;  
            $_POST['name']        = $user->getName;    
            $_POST['firstname']   =$user->getFirstName;  
            $_POST['email']       =  $user->getEmail ;  
            $_POST['date']        = $user->getBirthDate ; 
            $_POST['password']    =  $user->getPassword;         
        }
            
        /*
        $user->getUsername  = ($_POST['username']);
        $user->getName      = ($_POST['name']);
        $user->getFirstName = ($_POST['firstname']);
        $user->getEmail     = ($_POST['email']);
        $user->getBirthDate = ($_POST['date']);
        $user->getPassword  = ($_POST['password']);*/
        //var_dump($user); 
       /* $em->persist($user);
        $em->flush(); 
    }*/



    if(!empty($_POST)){
        
        extract($_POST); 

        if(isset($username) && isset($name) && isset($firtsname) && isset($email) && isset($birthDate) && isset($password)  && isset($passwordConfirm)){
         
        if(!empty($username) && !empty($name) && !empty($firtsname) && !empty($email) &&!empty($birthDate) && !empty($password)  && !empty($passwordConfirm)){
            echo'avant......................................................';

            if($password == $passwordConfirm){
                $options =['cost'=>12]; 
                
            }
           $hashpass = password_hash($password, PASSWORD_BCRYPT, $options); 
           include '.env.local';
            
           $data->$base->prepare("INSERT INTO user(username, name, firstname, email, birthDate, password) VALUES (:username, :name, firstname, :email, :birthDate, :password)");
           $data->execute([
                'username' =>$username, 
                'name' =>$name, 
                'firtsname' => $firtsname,
                'email' => $email,
                'birthDate' =>$birthDate, 
                'password' =>$password,
           ]); 

           
           echo'Bravo pour linscription';
        }
    }
        else{
            echo'ce compte exite';
        }
    }
  }

}
 