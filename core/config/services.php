<?php

use Element\Controllers\IndexController;
use Element\Validation\RequestValidator;


// Init Request validator ::class
$this->container->register(RequestValidator::class, RequestValidator::class);

// Init controller ::class
$this->container->register(IndexController::class, IndexController::class);
