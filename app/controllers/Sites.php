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

        $body['dateStarted'] = DateTime::createFromFormat('d/m/Y', $body['dateStarted'])->format('Y-m-d');
        if($body['dateFinished']){
            $body['dateFinished'] = DateTime::createFromFormat('d/m/Y', $body['dateFinished'])->format('Y-m-d');
        }

        $stillUsing = false;

        if(isset($body['stillUsing'])) {
            $stillUsing = $body['stillUsing'];
        }

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

            $site->stillUsing = $stillUsing;

            //$site->skills()->attach($body['skills']);

            $site->save();

//            var_dump($site->images());
//            die;
            //$result = $site::where('title', $body['title'])->first();

            foreach($body['skills'] as $skill) {

                //echo "----";
                //var_dump($result);
                //var_dump($skill);
                $association = [$skill];
                //var_dump($association);
                $site->skills()->attach($association);

            }

            foreach($body['images'] as $image) {

                //echo "----";
                //var_dump($result);
                //var_dump($image);
                $association = [$image];
                //var_dump($association);
                $site->images()->attach($association);

            }

        }
    }

    function edit($request, $response, $args) {

        $body = $request->getQueryParams();

        $sites = new Sites();

        $sites = $sites::where('ID', '=', $body['id'])->get();

        $siteArr = $sites->toArray();

        $ind = 0;

        foreach($sites as $site) {

            $siteArr[$ind]['skills'] = $site->skills->toArray() ?: [];

            $siteArr[$ind]['images'] = $site->images->toArray() ?: [];

            $ind ++;
        }

        return $siteArr[0];
    }

    function delete($request, $response, $args) {

        $body = $request->getParsedBody();

        $sites = new Sites();
        $sites::where('ID', '=', $body['id'])->delete();

    }

    public function getAll() {

        $sites = new Sites();

        $allSites = $sites::all();

        $siteArr = $allSites->toArray();

        $ind = 0;

        foreach($allSites as $site) {

            $siteArr[$ind]['skills'] = $site->skills->toArray() ?: [];

            $siteArr[$ind]['images'] = $site->images->toArray() ?: [];

            $ind ++;
        }

        return $siteArr;
    }
}
