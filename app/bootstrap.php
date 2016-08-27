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
    public $app;

    function __construct() {
        //$this->routes = new Routes();
    }

    public function getApp(){
        return $this->app;
    }

    function run() {

        $config = parse_ini_file('../.config');

        $this->setUpDb();

        $showErrors = isset($config['showErrors']) ? $config['showErrors'] : false;

        $configuration = [
            'settings' => [
                'displayErrorDetails' => $showErrors,
            ],
        ];

        $c = new \Slim\Container($configuration);

        // create new Slim instance
        $app = new \Slim\App($c);

        // create new Slim instance
        //$app = new \Slim\App();

        $app->db = $this->database;

        $this->flashDB(false);

        $app->auth = false;
        $app->user = '';
        $app->register = $config['registerActive'];

        $app->add(function ($request, $response, $next) use (&$app){

            if (isset($_SESSION['userID'])) {
                $app->auth = true;
                $app->user = $_SESSION['username'];
            }

            $response = $next($request, $response);

            return $response;

        });

        $container = $app->getContainer();

        $container['view'] = function ($c) {

            // templates location and a settings array
            $view = new \Slim\Views\Twig(
                '../templates', [
                    'cache' => '../cache',
                    'auto_reload' => true,
                    'debug' => true
                ]
            );

            // Instantiate and add Slim specific extension
            $view->addExtension(new Slim\Views\TwigExtension(
                $c['router'],
                $c['request']->getUri()
            ));

            return $view;
        };

        $route = new Routes($app);

        $app = $route->run($app);

        $this->app = $app;

        // Run app
        $this->app->run();
    }

    function setUpDb() {

        $this->database = new database();
        //$this->database->createUsers();
        //$this->db = new NotORM($database->pdo);
    }

    function flashDB($check) {

        if($check){

            $this->app->db->createSkills();
            $this->app->db->createSites();
            $this->app->db->createUsers();
            $this->app->db->createImages();

            echo "new database";
            die;
        }
    }
}
