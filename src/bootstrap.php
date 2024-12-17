<?php
/*
Fichier : src/bootstrap.php
*/
declare(strict_types=1);

use Tracy\Debugger;

session_start();

/*
  ==1==
  Initialiser COMPOSER
*/
require '../vendor/autoload.php';

/*
  ==2==
  Intégrer les constantes diverses et celles de configuration pour la base
*/
require 'Config/config.php';

/*
  ==3==
  Afficher les erreurs avec Tracy.
  https://tracy.nette.org/fr/guide
*/
Debugger::setSessionStorage(new Tracy\NativeSession());
Debugger::enable();
// Debugger::enable(Debugger::Production);
Debugger::$showBar = true;
// Debugger::$strictMode = E_ALL; // afficher toutes les erreurs
// Debugger::$strictMode = E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED;
Debugger::$logDirectory = APP_DEBUG_FILE_PATH;


/*
  ==5==
  Traiter les routes avec Fastroute.
  https://github.com/nikic/FastRoute
  Les routes doivent être spécifiées dans le fichier /src/routes.php
*/
// Lire les routes possibles
$app_routes = include 'routes.php';
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $route) use ($app_routes) {
    foreach ($app_routes as $app_route) {
        $route->addRoute($app_route[0], $app_route[1], $app_route[2]);
    }
});

// récupérer la méthode et l'URL proposée par le client
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = trim(str_replace(APP_ROOT_URL, '', $_SERVER['REQUEST_URI']));
// éliminer les paramètres (?foo=bar)
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

// effectuer l'analyse de la commande
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
// traiter la commande (si c'est possible)
switch ($routeInfo[0]) {
    // la route est connue
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        // analyser la route pour détecter une écriture en contrôleur@méthode
        $params = explode('@', $handler);
        // cas où il faut appeller simplement une fonction anonyme
        if (count($params) <= 1) {
            call_user_func_array($handler, $vars);
            exit();
        }
        // cas où il faut traiter la méthode du contrôleur
        // les contrôleurs utilisent un espace de nom
        // post@index  doit appeler Controller\PostController méthode index
        $controller = "App\Controller\\" . ucFirst($params[0]) . 'Controller';
        try {
            // la classe du contrôleur n'existe pas ...
            if (class_exists($controller) === false) {
                // historiser
                Debugger::log("500 - le contrôleur $controller n'existe pas");
                // présenter une page d'erreur
                die();
            }
            $controller = new $controller();
            $method = $params[1];
            // la méthode du contrôleur n'existe pas ...
            if (method_exists($controller, $method) === false) {
                // historiser
                Debugger::log("500 - la méthode $method n'existe pas");
                // présenter une page d'erreur
           
                die();
            }
            // appeler la méthode du contrôleur
            $controller->{$method}(...array_values($vars));
            exit();
        } catch (Exception $exception) {
            Debugger::log('404 - erreur dans le processus de traitement du routage');
        }

        break;

        // la commande n'existe pas
    case FastRoute\Dispatcher::NOT_FOUND:
        Debugger::log("404 - commande non existante :: $uri");

        break;

        // la commande existe mais la méthode est incorrecte
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        Debugger::log('405 - méthode incorrecte :: ' . $allowedMethods);

        break;
}