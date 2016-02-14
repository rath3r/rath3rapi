<?php

class Sites_Controller {

    function __construct() {

        //echo "skills";

    }

    function add($request, $response, $args) {

//        $error = [];
//        $success = false;
        $add = false;

        $body = $request->getParsedBody();

//        var_dump($body);
        var_dump($body['skills']);
//

       $body['dateStarted'] = DateTime::createFromFormat('d/m/Y', $body['dateStarted'])->format('Y-m-d');
        $body['dateFinished'] = DateTime::createFromFormat('d/m/Y', $body['dateFinished'])->format('Y-m-d');

        $site = new Sites();

        $result = $site::where('title', $body['title'])->first();

        if($result) {

            if($body['title'] !== $result->title) {

                $add = true;


            } else {

                $error['duplicate'] = $body['title'] . " is already present";

            }

        } else {

            $add = true;

        }

        if($add) {
            $site->title = $body['title'];
            $site->dateStarted = $body['dateStarted'];
            $site->dateFinished = $body['dateFinished'];

            //$site->skills()->attach($body['skills']);

            $site->save();

            $result = $site::where('title', $body['title'])->first();




            foreach($body['skills'] as $skill) {

                echo "----";
                //var_dump($result);
                //var_dump($skill);
                $association = [$skill];
                //var_dump($association);
                $site->skills()->attach($association);

            }

            //die;
        }

//        $return['skills'] = $skill::all()->toArray();
//        $return['error'] = $error;
//        $return['success'] = $success;

        //return $return;
        //echo "skills add";
    }

    function delete($request, $response, $args) {

//        $error = [];
////        $success = false;
        $body = $request->getParsedBody();

        $sites = new Sites();
        $sites::where('ID', '=', $body['id'])->delete();
//
//        $return['skills'] = $skill::all()->toArray();
//        $return['error'] = $error;
//        $return['success'] = true;
//
//        return $return;
//        //echo "skills add";
    }

    function returnArr() {
        return array(
            "test" => "Niamh"
        );
    }

    public function getAll() {

        $sites = new Sites();
        //echo "asdf";

        return $sites::all()->toArray();

    }

}