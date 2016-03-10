<?php
/*
 *
 */

use Illuminate\Database\Capsule\Manager as Capsule;

class database
{
    public $capsule;

    function __construct()
    {
        //Enter your database connection details here.

        $config = parse_ini_file('../.config');

        $host = $config['host']; //HOST NAME.
        $db_name = $config['dbname']; //Database Name
        $db_username = $config['username']; //Database Username
        $db_password = $config['password']; //Database Password

//        try {
//            $pdo = new PDO('mysql:host=' . $host . ';dbname=' . $db_name, $db_username, $db_password);
//
//        } catch (PDOException $e) {
//            exit('Error Connecting To DataBase');
//        }
//
//        $this->pdo = $pdo;

        // Bootstrap Eloquent ORM
        //$connFactory = new \Illuminate\Database\Connectors\ConnectionFactory();

        $this->capsule = new Capsule;

        $this->capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => $host,
            'database'  => $db_name,
            'username'  => $db_username,
            'password'  => $db_password,
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);

        $this->capsule->setAsGlobal();

        $this->capsule->bootEloquent();

        //return this;
    }

    function getCapsule() {
        return $this->capsule;
    }

    function createSkills() {

        $this->capsule->schema()->create('skills', function($table)
        {
            $table->increments('id');
            $table->timestamps();
            $table->string('title')->unique();
            $table->date('dateStarted');
            $table->date('dateFinished');
            $table->boolean('stillUsing');
        });
    }

    public function createSites() {

        $this->capsule->schema()->create('sites', function($table)
        {
            $table->increments('id');
            $table->timestamps();
            $table->string('title')->unique();
            $table->string('url');
            $table->date('dateStarted');
            $table->date('dateFinished');
            $table->boolean('stillUsing');

        });

        $this->capsule->schema()->create('sites_skills', function($table)
        {
            $table->integer('sites_id')->unsigned()->index();
            $table->foreign('sites_id')->references('id')->on('sites')->onDelete('cascade');

            $table->integer('skills_id')->unsigned()->index();
            $table->foreign('skills_id')->references('id')->on('skills')->onDelete('cascade');


        });
    }

    public function createImages() {

        $this->capsule->schema()->create('images', function($table) {

            $table->increments('id');
            $table->timestamps();
            $table->string('title');
            $table->string('url');
            $table->integer('skills_id')->unsigned();

        });

        $this->capsule->schema()->create('images_sites', function($table) {

            $table->integer('sites_id')->unsigned()->index();
            $table->foreign('sites_id')->references('id')->on('sites')->onDelete('cascade');

            $table->integer('images_id')->unsigned()->index();
            $table->foreign('images_id')->references('id')->on('images')->onDelete('cascade');

        });
    }

    function createUsers() {

        $this->capsule->schema()->create('users', function($table)
        {
            $table->increments('id');
            $table->string('username', 20)->unique();
            $table->string('email', 255)->unique();
            $table->string('first_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->string('password', 255);
            $table->boolean('active');
            $table->string('active_hash', 255)->nullable();
            $table->string('recover_hash', 255)->nullable();
            $table->string('remember_identifier', 255)->nullable();
            $table->string('remember_token', 255)->nullable();
            $table->timestamps();
        });

        echo "create Users";
    }
}