<?php

namespace App\Routing;

use App\Routing\Attribute\Route;
use App\Utils\Filesystem;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionMethod;

class Router
{
  private $routes = [];
  private $routes2 = [];
  private ContainerInterface $container;
  private const CONTROLLERS_NAMESPACE = "App\\Controller\\";
  private const CONTROLLERS_DIR = __DIR__ . "/../Controller";

  public function __construct(ContainerInterface $container)
  {
    $this->container = $container;
  }

  /**
   * Add a route into the router internal array
   *
   * @param string $name
   * @param string $url
   * @param string $httpMethod
   * @param string $httpMethod2
   * @param string $controller Controller class
   * @param string $method
   * @return self
   */
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


    return $this;
  }

  /**
   * Get a route. Returns null if not found
   *
   * @param string $uri
   * @param string $httpMethod
   * @param string $httpMethod2
   * @return array|null
   */
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

  /**
   * Executes a route based on provided URI and HTTP method.
   *
   * @param string $uri
   * @param string $httpMethod
   * @param string $httpMethod2
   * @return void
   * @throws RouteNotFoundException
   */
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

  /**
   * Resolve method's parameters from the service container
   *
   * @param string $controller name of controller
   * @param string $method name of method
   * @return array
   */
  private function getMethodParams(string $controller, string $method): array
  {
    $methodInfos = new ReflectionMethod($controller . '::' . $method);
    $methodParameters = $methodInfos->getParameters();

    $params = [];

    foreach ($methodParameters as $param) {
      $paramName = $param->getName();
      $paramType = $param->getType()->getName();

      if ($this->container->has($paramType)) {
        $params[$paramName] = $this->container->get($paramType);
      }
    }

    return $params;
  }

  public function registerRoutes(): void
  {
    $classNames = Filesystem::getClassNames(self::CONTROLLERS_DIR);

    foreach ($classNames as $class) {
      $this->registerRoute($class);
    }
  }

  public function registerRoute(string $className): void
  {
    $fqcn = self::CONTROLLERS_NAMESPACE . $className;
    $reflection = new ReflectionClass($fqcn);

    if ($reflection->isAbstract()) {
      return;
    }

    $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

    foreach ($methods as $method) {
      $attributes = $method->getAttributes(Route::class);

      foreach ($attributes as $attribute) {
        /** @var Route */
        $route = $attribute->newInstance();

        $this->addRoute(
          $route->getName(),
          $route->getPath(),
          $route->getHttpMethod(),
          $route->getHttpMethod2(),
          $fqcn,
          $method->getName()
        );
      }
    }
  }
}
