<?php

/**
 * Class site
 *
 * This is where the application is run. It is called by $site->run() in the index.php
 *
 */
class Routes {

    function __construct() {
        //$this->run($app);
    }

    function run($app) {

//        var_dump(site::getApp());
//        die;

        $app = $this->root($app);

        $app = $this->skills($app);

        $app = $this->sites($app);

        $app = $this->images($app);

        $app = $this->json($app);

        $app = $this->register($app);

        $app = $this->login($app);

//        $app->get('/tables', function ($request, $response, $args) {
//
//            $tables = new Skills_Migrations();
//            $tables->up();
//
//            return "";
//        });


        return $app;
    }

    function root($app) {

        $app->get('/', function ($request, $response, $args) {
            return $this->view->render($response, 'index.html', [
                'auth' => $this->auth,
                'user' => $this->user
            ]);
        })->setName('');

        return $app;
    }

    function skills($app) {

        $app->get('/skills', function ($request, $response, $args) {

            $skills = new Skills_Controller();

            $return = [
                'auth' => $this->auth,
                'user' => $this->user,
                'skills' => $skills->getAll()
            ];

            return $this->view->render($response, 'skills.html', $return);
        });

        $app->post('/skills/add', function ($request, $response, $args) {

            $skills = new Skills_Controller();

            $ret = $skills->add($request, $response, $args);

            // maybe I need to use flash messages
            //http://help.slimframework.com/discussions/problems/12059-how-to-display-flash-message-in-twig-view
            // https://github.com/slimphp/Slim-Flash

            // only works in v2
            //$app->redirect('/skills');

            return $response->withStatus(302)->withHeader('Location', '/skills');



//            return $this->view->render($response->withStatus(301)->withHeader('Location', '/skills'), 'skills.html', [
//                'auth'      => $this->auth,
//                'user'      => $this->user,
//                'skills'    => $ret['skills'],
//                'error'     => $ret['error'],
//                'success'   => $ret['success']
//            ]);
        });

        $app->post('/skills/delete', function ($request, $response, $args) {

            $skills = new Skills_Controller();

            $ret = $skills->delete($request, $response, $args);

            return $response->withStatus(302)->withHeader('Location', '/skills');
//            return $this->view->render($response, 'skills.html', [
//                'auth'      => $this->auth,
//                'user'      => $this->user,
//                'skills'    => $ret['skills'],
//                'error'     => $ret['error'],
//                'success'   => $ret['success']
//            ]);
        });

        return $app;
    }

    function sites($app) {

        $app->get('/sites', function ($request, $response, $args) {

            $sites = new Sites_Controller();
            $skills = new Skills_Controller();
            $images = new Images_Controller();

            $return = [
                'auth' => $this->auth,
                'user' => $this->user,
                'sites' => $sites->getAll(),
                'skills' => $skills->getAll(),
                'images' => $images->getAll()
            ];

            return $this->view->render($response, 'sites.html', $return);
        });

        $app->post('/sites/add', function ($request, $response, $args) {

            $sites = new Sites_Controller();

            $sites->add($request, $response, $args);

            // maybe I need to use flash messages
            //http://help.slimframework.com/discussions/problems/12059-how-to-display-flash-message-in-twig-view
            // https://github.com/slimphp/Slim-Flash

            // only works in v2
            //$app->redirect('/skills');

            return $response->withStatus(302)->withHeader('Location', '/sites');
        });

        $app->post('/sites/delete', function ($request, $response, $args) {

            $sites = new Sites_Controller();

            $sites->delete($request, $response, $args);

            return $response->withStatus(302)->withHeader('Location', '/sites');

        });

        return $app;
    }

    function images($app) {

        $app->get('/images', function ($request, $response, $args) {

            $images = new Images_Controller();

            $return = [
                'auth' => $this->auth,
                'user' => $this->user,
                'images' => $images->getAll()
            ];

            return $this->view->render($response, 'images.html', $return);
        });

        $app->post('/images/add', function ($request, $response, $args) {

            $images = new Images_Controller();

            $images->add($request, $response, $args);

            return $response->withStatus(302)->withHeader('Location', '/images');
        });

        $app->post('/images/delete', function ($request, $response, $args) {

            $images = new Images_Controller();

            $images->delete($request, $response, $args);

            return $response->withStatus(302)->withHeader('Location', '/images');

        });

        return $app;
    }

    function json($app) {

        $app->get('/json', function ($request, $response, $args) {

            $json = new JSON_Controller($request, $response, $args);
            $json->skills();

            return $json->response;
        });

        return $app;
    }

    function register($app) {

        $app->get('/register', function ($request, $response, $args) {

            if($this->auth){

                return $response->withStatus(200)->withHeader("Location", "/");

            } else {
                return $this->view->render($response, 'register.html', [
                    'auth' => $this->auth,
                    'user' => $this->user,
                    'regOpen' => true
                ]);
            }

        })->setName('register');

        $app->post('/register', function ($request, $response, $args) {

            //var_dump($this->auth);
            if($this->auth){

                return $response->withStatus(200)->withHeader("Location", "/");

            } else {
                $user = new User_Controller();
                $ret = $user->register($request, $response, $args);

                //var_dump($ret);

                return $this->view->render($response, 'register.html', [
                    'auth' => $this->auth,
                    'user' => $this->user,
                    'regOpen' => true,
                    'error' => $ret['error'],
                    'success' => $ret['success']
                ]);
            }

        })->setName('register');

        return $app;
    }

    function login($app) {

        $app->post('/login', function ($request, $response, $args) {

            $user = new User_Controller();
            $ret = $user->login($request, $response, $args);

            return $response->withStatus(200)->withHeader("Location", "/");

        })->setName('login');

        $app->get('/logout', function ($request, $response, $args) {

            unset($_SESSION['userID']);
            $this->auth = false;

            return $this->view->render($response, 'index.html', [
                'auth' => $this->auth,
                'user' => $this->user
            ]);
            //return $response->withStatus(307)->withHeader("Location", "/");

        })->setName('login');

        return $app;
    }

}