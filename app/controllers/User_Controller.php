<?php

class User_Controller {


    function __construct() {

            //var_dump($app);

    }

    function register($request, $response, $args)
    {
        $body = $request->getParsedBody();

        $user = new User();

        $success = false;

        $return = [];
        $error = [];

        if ($body['username'] !== "") {
            $user->username = $body['username'];

            $result = $user->where('username', '=', $body['username'])->first();
            var_dump($result);
            if($result){
                $error['duplicate'] = true;
            }
        } else {
            $error['username'] = "Please enter a Username";
        }

        if ($body['email'] !== "") {
            $user->email = $body['email'];
        } else {
            $error['email'] = "Please enter an Email";
        }

        if ($body['firstname'] !== "") {
            $user->first_name = $body['firstname'];
        } else {
            $error['firstname'] = "Please enter a Firstname";
        }

        if ($body['surname'] !== "") {
            $user->last_name = $body['surname'];
        } else {
            $error['surname'] = "Please enter a Surname";
        }

        if ($body['password'] !== "" || $body['passwordConfirm'] !== "") {

            if ($body['password'] === $body['passwordConfirm']) {
                $hash = password_hash($body['password'], PASSWORD_BCRYPT, array("cost" => 10));

                $user->password = $hash;
            } else {
                $error['passwordConfirm'] = "Passwords must match";
            }
        } else {
            $error['password'] = "Please enter a Password";
        }

        //var_dump($error);

        if(empty($error)) {
            //var_dump("save");
            $user->save();
            $success = true;
        }

        $return['error'] = $error;
        $return['success'] = $success;

        return $return;

    }

    function login($request, $response, $args)
    {
        $return = [];

        $auth = false;

        $body = $request->getParsedBody();

        if($body['username'] != "") {
            $user = new User();

            $result = $user->where('username', '=', $body['username'])->first();

            $password = $body['password'];
            $hash = $result->password;

            if(password_verify ($password, $hash)){

                $_SESSION['userID'] = $result->id;
                $_SESSION['username'] = $result->username;

                $auth = true;
            }
        }

        $return['auth'] = $auth;

        return $return;
    }


}