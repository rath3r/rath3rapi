<?php


session_cache_limiter(false);
session_start();

require '../vendor/autoload.php';

/**
 * Class site
 *
 * This is where the application is run. It is called by $site->run() in the index.php
 *
 */
class site {

    public $container;
    public $database;
    public $routes;

    function __construct() {
        //$this->routes = new Routes();
    }

    function run() {

        $this->setUpDb();

        // create new Slim instance
        $app = new \Slim\App();

        $app->db = $this->database;

//        $app->db->createSkills();
//        $app->db->createSites();
//        $app->db->createUsers();
//
//        echo "hello";
//        die;

        $app->auth = false;
        $app->user = '';

        $app->add(function ($request, $response, $next) use (&$app) {

            //var_dump($_SESSION);
            //var_dump($request);
            //var_dump($app);
            if (isset($_SESSION['userID'])) {
                $app->auth = true;
                $app->user = $_SESSION['username'];
            }

//            $response->getBody()->write('BEFORE??');
//            $response->getBody()->write('"before me"');
            $response = $next($request, $response);
//            $response->getBody()->write('AFTER');
            //echo "after me";
            return $response;
        });

        $container = $app->getContainer();

        $container['view'] = function ($c) {
            $view = new \Slim\Views\Twig(
                '../templates', [
                    'cache' => '../cache',
                ]
            );

//            $twig = new Twig_Environment($loader, array(
//                'debug' => true,
//                // ...
//            ));
//            $twig->addExtension(new Twig_Extension_Debug());

            // Instantiate and add Slim specific extension
            $view->addExtension(new Slim\Views\TwigExtension(
                $c['router'],
                $c['request']->getUri()
            ));

            return $view;
        };

        $route = new Routes($app);

        $app = $route->run($app);

        // Run app
        $app->run();
    }

    function setUpDb() {

        $this->database = new database();
        //$this->database->createUsers();
        //$this->db = new NotORM($database->pdo);
    }
}
