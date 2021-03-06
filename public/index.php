<?php

declare(strict_types=1);

use Laminas\Mvc\Application;
use Laminas\Stdlib\ArrayUtils;

//Para executar esta aplicação digite na URL do seu navegador: http://localhost/zf3/helloworld/public/ 
//OU em uma janela de terminal, dentro da pasta da sua aplicação: /var/www/html/zf3/helloworld$ 
//php -S localhost:8000 -t public
//Caso dê algum problema de não execução da página, ou seja, ela ficar totalmente em branco, siga os passs seguintes
//1 - em seu terminal, na pasta da aplicação digite: php -S localhost:8000 -t public
//2 - Acesse os links da página normalmente quando for executada
//3 - Em outra janela do browse de navegação digite: http://localhost/zf3/helloworld/public/ 

/* Versão do bootstrap utilizado nessa versão do LAMINAS: Bootstrap v4.4.1 (https://getbootstrap.com/) */
 

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (is_string($path) && __FILE__ !== $path && is_file($path)) {
        return false;
    }
    unset($path);
}

// Composer autoloading
include __DIR__ . '/../vendor/autoload.php';

if (! class_exists(Application::class)) {
    throw new RuntimeException(
        "Unable to load application.\n"
        . "- Type `composer install` if you are developing locally.\n"
        . "- Type `vagrant ssh -c 'composer install'` if you are using Vagrant.\n"
        . "- Type `docker-compose run laminas composer install` if you are using Docker.\n"
    );
}

// Retrieve configuration
$appConfig = require __DIR__ . '/../config/application.config.php';
if (file_exists(__DIR__ . '/../config/development.config.php')) {
    $appConfig = ArrayUtils::merge($appConfig, require __DIR__ . '/../config/development.config.php');
}

// Run the application!
Application::init($appConfig)->run();
