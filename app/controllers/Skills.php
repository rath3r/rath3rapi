<?php

class Skills_Controller extends Controller {

    function __construct() {

            //echo "skills";

    }

    function add($request, $response, $args) {

        $error = [];
        $success = false;
        $body = $request->getParsedBody();


        $body['dateStarted'] = $this->formatDate($body['dateStarted']);
        $body['dateFinished'] = $this->formatDate($body['dateFinished']);
        var_dump($body);

        die;

        if (!isset($body['stillUsing'])) {
            $body['stillUsing'] = false;
        }

        $skill = new Skills();

        $result = $skill::where('title', $body['title'])->first();

        if($result) {

            if($body['title'] !== $result->title) {

                $skill->title = $body['title'];
                $skill->dateStarted = $body['dateStarted'];
                $skill->dateFinished = $body['dateFinished'];
                $skill->stillUsing = $body['stillUsing'];

                $skill->save();
                $success = true;

            } else {

                $error['duplicate'] = $body['title'] . " is already present";

            }

        } else {

            $skill->title = $body['title'];
            $skill->dateStarted = $body['dateStarted'];
            $skill->dateFinished = $body['dateFinished'];
            $skill->stillUsing = $body['stillUsing'];

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

        $skills = new Skills();
        //echo "asdf";
        $allskills = $skills::all();

        $skillArr = $skills::all()->toArray();

        $ind = 0;

        foreach($allskills as $skill){

            $skillArr[$ind]['images'] = $skill->image ?: [];

            $ind ++;
        }

        return $skillArr;

    }

    function getSkill($request, $response, $args) {

        $body = $request->getQueryParams();

        $skills = new Skills();

        $skills = $skills::where('ID', '=', $body['id'])->get();

        $skillsArr = $skills->toArray();

        return $skillsArr[0];
    }

    function edit($request, $response, $args) {

        $body = $request->getParsedBody();

        $skills = new Skills();

        $skill = $skills::where('ID', '=', $body['id'])->get();

        $currSkill = $skill[0];

        $currSkill->title = $body['title'];

        if(isset($body['stillUsing'])){
            $currSkill->stillUsing = 1;
            $body['dateFinished'] = false;

        }else{
            $currSkill->stillUsing = 0;
        }

        $currSkill->dateStarted = $this->formatDate($body['dateStarted']);
        $currSkill->dateFinished = $this->formatDate($body['dateFinished']);


        $currSkill->save();

    }
}