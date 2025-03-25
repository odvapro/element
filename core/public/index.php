<?php

// echo '<pre>' . htmlentities(print_r($_SERVER, true)) . '</pre>';exit();
use Element\Exceptions\Handler as ExceptionHandler;
use Element\Kernel;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Dotenv\Dotenv;

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load($_SERVER['DOCUMENT_ROOT'] . '/.env');

$hdlr = new ExceptionHandler();

set_exception_handler([$hdlr, 'handle']);

$kernel = new Kernel();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
