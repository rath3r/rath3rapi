<?php

class JSON_Controller {

    public $request;
    public $response;
    public $args;

    function __construct($request, $response, $args) {

        $this->request = $request;
        //$this->response= $response;
        $this->args = $args;

        $this->response = $response->withHeader('Access-Control-Allow-Origin', '*');
        //$this->response = $this->response->withHeader('Content-type', 'application/json');

    }

    function add($request, $response, $args) {

        $body = $request->getParsedBody();

        //var_dump($body);

        $skill = new Skills();

        $skill->title = $body['title'];
        $skill->started = $body['date'];

        $skill->save();
        //echo "skills add";
    }

    function returnArr() {
        return array(
            "test" => "Niamh"
        );
    }

    public function getAll() {

        $skill = new Skills();

        return $skill::all()->toArray();

    }

    public function test() {

        $body = $this->response->getBody();

        $body->write("hello");

//        return array(
//            "things" => "asdfasdf"
//        );

    }

    public function skills() {

        $body = $this->response->getBody();

        $skills = new Skills();

        $body->write(json_encode($skills::all()->toArray()));

    }

    public function sites() {

        $body = $this->response->getBody();

        $sites = new Sites_Controller();

        $body->write(json_encode($sites->getAll()));

    }
}
