<?php

use Element\Kernel;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/../vendor/autoload.php';

use Element\Exceptions\Handler as ExceptionHandler;

$hdlr = new ExceptionHandler();

set_exception_handler([$hdlr, 'handle']);
set_error_handler([$hdlr, 'handleError']);

$kernel = new Kernel();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
