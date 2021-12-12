# Dossier PHP MVC - MARTINET Clément - KEITA Lansana

- [Introduction](#introduction)
- [Modification du routing](#Modification-du-routing)
- [Création de compte](#Creation-de-compte)
- [Authentification](#Authentifiation)
- [Rôles](#Rôles)
- [Deconnexion](#Deconnexion)


## Introduction

Les modifications apportées sont la création de compte, la connexion ainsi que les rôles et le routing en POST.

## Modification du routing

Pour le besoin des formulaires de connexion et d'inscription il a fallu créer un nouveau système de routing en méthode POST et pas seulement GET.
Pour cela il a fallu ajouter une méthode dans le constructeur de notre router ainsi que les méthodes get/set correspondantes :

```php
public function __construct(
    string $path,
    string $httpMethod = "GET",
    string $httpMethod2 = "POST",
    string $name = "default"
  ) {
    $this->path = $path;
    $this->httpMethod = $httpMethod;
    $this->httpMethod2 = $httpMethod2;
    $this->name = $name;
  }
```

Même chose de faite dans notre router à l'ajout, récupération et execution d'une route.

Ajout :

```php
public function addRoute(
    string $name,
    string $url,
    string $httpMethod,
    string $httpMethod2,
    string $controller,
    string $method
  ): self {
    $this->routes[] = [
      'name' => $name,
      'url' => $url,
      'http_method' => $httpMethod,
      'controller' => $controller,
      'method' => $method
    ];

    $this->routes2[] = [
      'name' => $name,
      'url' => $url,
      'http_method2' => $httpMethod2,
      'controller' => $controller,
      'method' => $method
    ];
```
Récupération :

```php
public function getRoute(string $uri, string $httpMethod, string $httpMethod2): ?array
  {
    foreach ($this->routes as $route) {
      if ($route['url'] === $uri && $route['http_method'] === $httpMethod) {
        return $route;
      }
    }

    foreach ($this->routes2 as $route) {
      if ($route['url'] === $uri && $route['http_method2'] === $httpMethod2) {
        return $route;
      }
    }

    return null;
  }
```

Execution : 

```php
public function execute(string $uri,  string $httpMethod, string $httpMethod2)
  {
    $route = $this->getRoute($uri, $httpMethod, $httpMethod2);

    if ($route === null) {
      throw new RouteNotFoundException();
    }

    $controllerName = $route['controller'];
    $constructorParams = $this->getMethodParams($controllerName, '__construct');
    $controller = new $controllerName(...$constructorParams);

    $method = $route['method'];
    $params = $this->getMethodParams($controllerName, $method);

    call_user_func_array(
      [$controller, $method],
      $params
    );
  }
```

## Création de compte

Création d'un formulaire HTML en utilisant Bootstrap pour la mise en forme :

```php
<div class="container">
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-50 mx-auto">
            <div class="card border-0 shadow rounded-3 my-5">
                <div class="card-body p-4 p-sm-5">
                    <h2 class="card-title text-center mb-5 fw-light fs-5">Inscrivez-vous</h2>
                    <form method="post">

                    <div class="form-floating mb-4">
                        <label for="username">Pseudo</label>
                        <input type="text" name="username" class="form-control my-input" id="username"
                        placeholder="jeanLaporte">
                    </div>

                    <div class="form-floating mb-4">
                        <label for="name">Nom</label>
                        <input type="text" name="name" class="form-control my-input" id="name" placeholder="LAPORTE">
                    </div>

                    <div class="form-floating mb-4">
                        <label for="firstname">Prénom</label>
                        <input type="text" name="firstname" class="form-control my-input" id="firstname" placeholder="Jean">
                    </div>

                    <div class="form-floating mb-4">
                        <label for="email">Email</label>
                        <input type="text" name="email" class="form-control my-input" id="email" placeholder="jean@myges.fr">
                    </div>

                    <div class="form-floating mb-4">
                        <label for="birthDate">Date de naissance</label>
                        <input type="date" name="birthDate" class="form-control my-input" id="birthDate"
                        placeholder="04/12/2021">
                    </div>

                    <div class="form-floating mb-4">
                    <select name="roles" id="">
                        <option value="user ">User</option>
                        <option value="admin">Admin</option>
                        
                    </select>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="password" name="password"  class="form-control my-input" id="name" placeholder="**********">
                        <label for="floatingPassword">Mot de passe</label>
                    </div>    
                    
                    <div class="text-center" >
                        <button class="btn btn-primary" type="submit" name="submit">S'inscrire
                        </button>
                    </div>
                    <hr class="my-4">
                    <div class="text-center">
                        Déjà inscrit? <a href="/login">ici</a>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
```

Ce formulaire va nous ramener vers le controller d'inscription qui cette fois va enregistrer l'utilisateur si des données sont envoyées et si l'enregistrement se passe bien envoyer sur la page de connexion :

```php
if(!empty($_POST)){
    $username = $_POST['username'];
    $name = $_POST['name'];
    $firtsname = $_POST['firstname'];
    $email = $_POST['email'];
    $birthDate = new dateTime($_POST['birthDate']);
    $password = $_POST['password'];
    if(!empty($username) && !empty($name) && !empty($firtsname) && !empty($email) && !empty($birthDate) && !empty($password)){
        
    // crypter le mot de passe
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
else{
    echo $this->twig->render('security/register.html.twig');
}
```

Lien vers les sources utilisées [ici](http://www.codeurjava.com/2016/12/formulaire-de-login-avec-html-css-php-et-mysql.html).

## Authentification

Création d'un formulaire de connexion comme précédemment pour l'inscription avec retour sur le controller de connexion si des données sont envoyées.

Suite à des difficultées à utiliser la fonction de récupération des données de doctrine (getRepository() qui nous renvoyait NULL), nous avons optés pour une récupération directement via une requête SQL.

```php
public function getUserPassword(EntityManager $em, $username){
    $q = $em->createQuery("SELECT u.password FROM App\Entity\User u WHERE u.username = '".$username."' ")
            ->getResult();
    return $q;
}
```

Lien vers les sources utilisées [ici](https://symfony.com/doc/current/doctrine.html).
Utilisation d'anciens projets personnels pour les autres fonctionnalitées.

## Rôles

Ajout d'une fonctionnalité de rôle pour permettre ou non l'accès à certaines fonctionnalitées.
Nous avons donc ajoutés un rôle à notre utilisateur et celui-ci est récupéré en variable session pour être réutilisé plus tard.
Modification de notre class user en conséquence.

```php
$_SESSION['user'] = $username;
$_SESSION['role'] = $role;
```

## Deconnexion

AJout d'un bouton de deconnexion une fois connecté.

```php
<form method="post">
    <div class="text-center" >
        <button class="btn btn-primary" type="submit" name="logout_btn"> Deconnexion </button>
    </div>
</form>
```