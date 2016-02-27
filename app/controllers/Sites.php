<?php

class Sites_Controller {

    function __construct() {

        //echo "skills";

    }

    function formatDate($date) {
        var_dump($date);
        var_dump(DateTime::createFromFormat('d/m/Y', $date));
        return DateTime::createFromFormat('d/m/Y', $date)->format('Y-m-d');
    }

    function add($request, $response, $args) {

        $add = false;

        $body = $request->getParsedBody();

        $body['dateStarted'] = $this->formatDate($body['dateStarted']);
        if($body['dateFinished']){
            $body['dateFinished'] = $this->formatDate($body['dateFinished']);
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

            $site->save();

            foreach($body['skills'] as $skill) {

                $association = [$skill];
                $site->skills()->attach($association);

            }

            foreach($body['images'] as $image) {

                $association = [$image];
                $site->images()->attach($association);

            }

        }
    }

    function getSite($request, $response, $args) {

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

    function edit($request, $response, $args) {

        $body = $request->getParsedBody();

        $sites = new Sites();

        $sites = $sites::where('ID', '=', $body['id'])->get();

        $currSite = $sites[0];

        $currSite->title = $body['title'];
        $currSite->dateStarted = $this->formatDate($body['dateStarted']);
        $currSite->dateFinished = $this->formatDate($body['dateFinished']);
        if(isset($body['stillUsing'])){
            $currSite->stillUsing = 1;
        }else{
            $currSite->stillUsing = 0;
            $currSite->stillUsing = 0;
        }
        $currSite->save();

        $currSkillsIds = $currSite->skills->pluck('id')->toArray();

        // check for skill removals
        foreach($currSkillsIds as $currSkillsId) {

            if(!in_array($currSkillsId, $body['skills'])){

                $association = [$currSkillsId];
                $currSite->skills()->detach($association);
            }
        }

        // check for skill additions
        // foreach of the selected skills check if they are alreay present
        foreach($body['skills'] as $skill) {

            // if selected skill is not in current skills ids array add the association
            if(!in_array($skill, $currSkillsIds)) {

                $association = [$skill];
                $currSite->skills()->attach($association);
            }
        }

        $currImagesIds = $currSite->images->pluck('id')->toArray();

        // check for skill removals
        foreach($currImagesIds as $currImagesId) {

            if(!in_array($currImagesId, $body['images'])){

                $association = [$currImagesId];
                $currSite->images()->detach($association);
            }
        }

        // check for image additions
        foreach($body['images'] as $image) {

            if(!in_array($image, $currImagesIds)) {

                $association = [$image];
                $currSite->images()->attach($association);
            }
        }
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
