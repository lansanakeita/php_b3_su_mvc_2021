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

    if(!empty($_POST)){
        $username = $_POST['username'];
        $name = $_POST['name'];
        $firtsname = $_POST['firstname'];
        $email = $_POST['email'];
        $birthDate = new dateTime($_POST['birthDate']);
        $password = $_POST['password'];
        if(!empty($username) && !empty($name) && !empty($firtsname) && !empty($email) && !empty($birthDate) && !empty($password)){
           
            // cripter le mot de passe
           $hashpass = password_hash($password, PASSWORD_BCRYPT); 
       
           $user = new User();

           $user->setName($name)
             ->setFirstName($firtsname)
             ->setUsername($username)
             ->setPassword($hashpass)
             ->setEmail($email)
             ->setBirthDate($birthDate);
       
           $em->persist($user);
           $em->flush();

           header('Location:/login');
        }
        else{
            echo'ce compte existe';
        }
    }
  

}
}
 