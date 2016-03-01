<?php

class Controller {

    function __construct() {

        //echo "skills";

    }

    function formatDate($date) {

        if($date){
            return DateTime::createFromFormat('d/m/Y', $date)->format('Y-m-d');
        }else{
            return false;
        }
    }

}
