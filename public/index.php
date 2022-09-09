<?php

use App\Kernel;
header('Access-Control-Allow-Origin: https://localhost:4200');
header("Access-Control-Allow-Headers: Authorization , X-AUTH-TOKEN , Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Credentials: true ");
$method = $_SERVER['REQUEST_METHOD'];
require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
