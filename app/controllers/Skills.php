<?php

class Skills_Controller {

    function __construct() {

            //echo "skills";

    }

    function add($request, $response, $args) {

        $error = [];
        $success = false;
        $body = $request->getParsedBody();

        $body['dateStarted'] = DateTime::createFromFormat('d/m/Y', $body['dateStarted'])->format('Y-m-d');
        $body['dateFinished'] = DateTime::createFromFormat('d/m/Y', $body['dateFinished'])->format('Y-m-d');

        $skill = new Skills();

        $result = $skill::where('title', $body['title'])->first();

        if($result) {

            if($body['title'] !== $result->title) {

                $skill->title = $body['title'];
                $skill->dateStarted = $body['dateStarted'];
                $skill->dateFinished = $body['dateFinished'];

                $skill->save();
                $success = true;

            } else {

                $error['duplicate'] = $body['title'] . " is already present";

            }

        } else {

            $skill->title = $body['title'];
            $skill->dateStarted = $body['dateStarted'];
            $skill->dateStarted = $body['dateFinished'];

            $skill->save();
            $success = true;

        }


        $return['skills'] = $skill::all()->toArray();
        $return['error'] = $error;
        $return['success'] = $success;

        return $return;
        //echo "skills add";
    }

    function delete($request, $response, $args) {

        $error = [];
//        $success = false;
        $body = $request->getParsedBody();

        $skill = new Skills();
        $skill::where('ID', '=', $body['id'])->delete();

        $return['skills'] = $skill::all()->toArray();
        $return['error'] = $error;
        $return['success'] = true;

        return $return;
        //echo "skills add";
    }

    function returnArr() {
        return array(
            "test" => "Niamh"
        );
    }

    public function getAll() {

        $skill = new Skills();
        //echo "asdf";

        return $skill::all()->toArray();

    }

}