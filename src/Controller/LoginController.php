<?php

namespace App\Controller;

use App\Entity\User;
use App\Routing\Attribute\Route;
use Doctrine\ORM\EntityManager;

class LoginController extends AbstractController
{
 
  #[Route(path: "/login")]
  public function register(EntityManager $em)
  {
    echo $this->twig->render('security/login.html.twig');

    $user = new User; 
    var_dump($_POST);
    if(isset($_POST)){
      $repository = $this->em->getRepository(User::class);
      $user = $repository->find($_POST['username']);
      var_dump($user);
    }
    // if(!empty($_POST['username']) && !empty($_POST['password']))
    // {
    //     $_SESSION['username'] = $user->getUsername();
       
    //     echo'Merci pour la connexion'; 
    //     var_dump($_POST);
    // }


  }
}