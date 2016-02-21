<?php

class Images_Controller {

    function __construct() {

        //echo "skills";

    }

    function add($request, $response, $args) {

        $body = $request->getParsedBody();

        if (!isset($_FILES['image'])) {
            echo "No files uploaded!!";
            return;
        }

        $name = $_FILES['image']['name'];

        if (move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $name) === true) {

            $url = 'uploads/' . $name;
        }

        $image = new Images();
        $image->title = $body['title'];
        $image->url = $url;
        $image->save();

    }

    function delete($request, $response, $args) {

        $body = $request->getParsedBody();

        $sites = new Sites();
        $sites::where('ID', '=', $body['id'])->delete();

    }

    function returnArr() {
        return array(
            "test" => "Niamh"
        );
    }

    public function getAll() {

        $images = new Images();

        $allImages = $images::all();

        $imageArr = $allImages->toArray();

        return $imageArr;

    }

}