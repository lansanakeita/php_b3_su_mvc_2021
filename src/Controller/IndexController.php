<?php

namespace App\Controller;

use App\Routing\Attribute\Route;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class IndexController extends AbstractController
{
  #[Route(path: "/index")]
  public function index(EntityManager $em)
  {
   
  }
  #[Route(path: "/contact", name: "contact")]
  public function contact()
  {
    //var_export($_SESSION);
    if (!empty($_SESSION)) {
      echo $this->twig->render('index/contact.html.twig');

      $this->logout();
    }else{      
      $this->redirectLoginPageUnsuccesfullAction();
    }
  }

  
  public function logout(){
    if (array_key_exists("logout_btn", $_POST)) {
      //unset($_SESSION);
      $_SESSION = [];
      header('Location:/login');
    }
  }

  public function redirectLoginPageUnsuccesfullAction(){
    echo "<h1 style='color:darkred; text-align:center;'> Pour accéder à cette page, veuillez vous connecter avant </h1>";

    header('Location:/login');
    //echo $this->twig->render('security/login.html.twig');
  }


}
