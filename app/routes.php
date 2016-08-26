<?php

/**
 * Class site
 *
 * This is where the application is run. It is called by $site->run() in the index.php
 *
 */
class Routes {

    public $config;

    function __construct() {
        //$this->run($app);
    }

    function run($app) {

        $this->config = parse_ini_file('../.config');

        $app = $this->root($app);

        $app = $this->skills($app);

        $app = $this->sites($app);

        $app = $this->about($app);

        $app = $this->images($app);

        $app = $this->json($app);

        $app = $this->register($app);

        $app = $this->login($app);

        return $app;
    }

    function root($app) {

        $app->get('/', function ($request, $response, $args) {

            return $this->view->render($response, 'index.html', [
                'auth'      => $this->auth,
                'user'      => $this->user,
                'register'  => $this->register
            ]);

        })->setName('');

        return $app;
    }

    function skills($app) {

        $app->get('/skills', function ($request, $response, $args) {

            $skills = new Skills_Controller();
            $images = new Images_Controller();

            $return = [
                'auth' => $this->auth,
                'user' => $this->user,
                'skills' => $skills->getAll(),
                'images' => $images->getAll()
            ];

            return $this->view->render($response, 'skills.html', $return);
        });

        $app->post('/skills/add', function ($request, $response, $args) {

            $skills = new Skills_Controller();

            $ret = $skills->add($request, $response, $args);

            // maybe I need to use flash messages
            //http://help.slimframework.com/discussions/problems/12059-how-to-display-flash-message-in-twig-view
            // https://github.com/slimphp/Slim-Flash

            return $response->withStatus(302)->withHeader('Location', '/skills');

        });

        $app->get('/skills/edit', function ($request, $response, $args) {

            $skills = new Skills_Controller();
            $skill = $skills->getSkill($request, $response, $args);

            $return = [
                'auth' => $this->auth,
                'user' => $this->user,
                'skill' => $skill
            ];

            return $this->view->render($response, '/forms/editSkills.html', $return);
        });

        $app->post('/skills/edit', function ($request, $response, $args) {

            $skills = new Skills_Controller();
            $site = $skills->edit($request, $response, $args);

            return $response->withStatus(302)->withHeader('Location', '/skills');

        });

        $app->post('/skills/delete', function ($request, $response, $args) {

            $skills = new Skills_Controller();

            $ret = $skills->delete($request, $response, $args);

            return $response->withStatus(302)->withHeader('Location', '/skills');

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

        $app->get('/sites/edit', function ($request, $response, $args) {

            $sites = new Sites_Controller();
            $site = $sites->getSite($request, $response, $args);

            $skills = new Skills_Controller();
            $skills = $skills->getAll();

            $images = new Images_Controller();
            $images = $images->getAll();

            foreach($skills as &$skill) {
                foreach($site['skills'] as $siteSkill) {
                    if($siteSkill['id'] == $skill['id']){
                        $skill['inUse'] = true;
                    }
                }
            }

            foreach($images as &$image) {
                foreach($site['images'] as $siteImage) {
                    if($siteImage['id'] == $image['id']){
                        $image['inUse'] = true;
                    }
                }
            }

            $return = [
                'auth' => $this->auth,
                'user' => $this->user,
                'site' => $site,
                'skills' => $skills,
                'images' => $images
            ];

            return $this->view->render($response, '/forms/editSites.html', $return);
        });

        $app->post('/sites/edit', function ($request, $response, $args) {

            $sites = new Sites_Controller();
            $site = $sites->edit($request, $response, $args);

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

        $app->get('/json/skills', function ($request, $response, $args) {

            $json = new JSON_Controller($request, $response, $args);
            $json->skills();

            return $json->response;
        });

        $app->get('/json/sites', function ($request, $response, $args) {

            $json = new JSON_Controller($request, $response, $args);
            $json->sites();

            return $json->response;
        });

        return $app;
    }

    function register($app) {

        if($this->config['registerActive']) {

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

        }

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

    function about($app) {

        $app->get('/about', function ($request, $response, $args) {

            $return = [
                'auth' => $this->auth,
                'user' => $this->user
            ];

            return $this->view->render($response, 'about.html', $return);
        });

        $app->get('/ideas', function ($request, $response, $args) {

            $return = [
                'auth' => $this->auth,
                'user' => $this->user
            ];

            return $this->view->render($response, 'ideas.html', $return);
        });

        return $app;
    }

}
