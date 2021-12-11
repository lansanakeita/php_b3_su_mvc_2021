<?php

namespace App\Controller;

use App\Entity\User;
use App\Routing\Attribute\Route;
use DateTime;
use Doctrine\ORM\EntityManager;

class LoginController extends AbstractController
{
 
  #[Route(path: "/login")]
  public function register(EntityManager $em)
  {
      $user = new User; 
    echo $this->twig->render('security/login.html.twig');

    session_start();
    if(!empty($_POST['username']) && !empty($_POST['password']))
    {
        $_SESSION['username'] = $user->getUsername();
       
        echo'Merci pour la connexion'; 
        var_dump($_POST);
    }


  }
}