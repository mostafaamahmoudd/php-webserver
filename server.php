#!/usr/bin/env php

<?php

use PHPWebserver\Server;
use PHPWebserver\Request;
use PHPWebserver\Response;

array_shift($argv);

empty($argv) ? $port = 80 : $port = array_shift($argv);

require_once __DIR__ . '/vendor/autoload.php';

$server = new Server();

$server->listen(function (Request $request){
    return new Response('Hello there');
});