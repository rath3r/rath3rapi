
# Rath3r API

A simple application focused on the presentation of a portfolio site.

The authentication is based on a Authentication tutorial using Slim 2 - [PHP Authentication][1].

## Installation

    composer install

There after use.

    composer update

## Configuration

There is a `.config` file which is parsed for the connection details to the database.

## Database

The database is accessed using the [illuminate][2] ORM. Illuminate currently at version 5.1-dev.

## Usage

After adding new file run:

    composer dump-autoload

to regenerate the `composer` autoload facility. Clear instructions can be found here - [Autoload composer][3]

## Test data

    INSERT INTO `users` (`id`, `username`, `email`, `first_name`, `last_name`, `password`, `active`, `active_hash`, `recover_hash`, `remember_identifier`, `remember_token`, `created_at`, `updated_at`) VALUES
    (13, 'test', 'test', 'test', 'test', '$2y$10$Jz67u9i96octcHAEM/7SLuGOQZrpmDmdqvGhvoxTCNPVcyoC6l6Ye', 0, NULL, NULL, NULL, NULL, '2015-12-02 22:38:01', '2015-12-02 22:38:01');


[1]: https://www.youtube.com/watch?v=YXKCNgfdAAM
[2]: https://github.com/illuminate/database
[3]: http://blog.bobbyallen.me/2013/03/23/using-composer-in-your-own-php-projects-with-your-own-git-packageslibraries/

