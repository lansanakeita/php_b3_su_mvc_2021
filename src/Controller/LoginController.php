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
    if(!empty($_POST)){
      $repository = $this->em->getRepository(User::class);
      $user = $repository->find($_POST['username']);
      var_dump($user);
      if(!empty($user) && $user->getPassword() == password_hash($_POST['password'], PASSWORD_BCRYPT)){
        header('Location:/home');
      }
      else{
        echo 'Echec de la connexion';
      }
    }
    else{
      echo $this->twig->render('security/login.html.twig');
    }
  }
}