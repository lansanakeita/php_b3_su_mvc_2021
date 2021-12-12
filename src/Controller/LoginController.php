<?php

namespace App\Controller;

use App\Entity\User;
use App\Routing\Attribute\Route;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;

class LoginController extends AbstractController
{
 
  #[Route(path: "/login")]
  public function register(EntityManager $em)
  {
    echo $this->twig->render('security/login.html.twig');

       
    if(key_exists("login_btn", $_POST)){
      $username = $_POST["username"];
      $password_in_form = $_POST["password"];
      $password_in_bd = $this->getUserPassword($em, $username)[0]['password'];
      $role = $this->getStatus($em, $_POST["username"]);

        
        if ($this->usernameExist($em, $username) && password_verify($password_in_form, $password_in_bd) ) {
          $_SESSION['user'] = $username;
          $_SESSION['role'] = $role;
          header('Location:/contact');
          
        }
        echo "<script> alert('connection failed') </script>";
    }
  }

  /**
   * Vérification de l'existence de $username dans notre BD
   *
   * @param EntityManager $em
   * @param [type] $username
   * @return void
   */
  public function usernameExist(EntityManager $em, $username){
    $query = $em->createQuery("SELECT u FROM App\Entity\User u WHERE u.username = '".$username."' ")
                ->getResult();
    /**
     * si le résultat de notre requete est non vide c'est à dire si notre username 
     * correspond à un utilisateur dans notre BD on renvoie true
     */
    if (!empty($query)) {
      return true;
    }
    return false;
  }


  /**
   * Récupération du mot de passe de l'utilisateur $username
   *
   * @param EntityManager $em
   * @param [type] $username
   * @return void
   */
  public function getUserPassword(EntityManager $em, $username){
    $q = $em->createQuery("SELECT u.password FROM App\Entity\User u WHERE u.username = '".$username."' ")
            ->getResult();
    return $q;
  }


  public function getStatus(EntityManager $em, $username){
    $q = $em->createQuery("SELECT u.roles FROM App\Entity\User u WHERE u.username = '".$username."' ")
            ->getResult();
    return $q[0]["roles"];
  }


}