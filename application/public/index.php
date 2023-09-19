<?php

use App\Kernel;
use Symfony\Component\HttpFoundation\Request;

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';

return function (array $context) {
    #https://symfony.com/doc/current/reference/configuration/framework.html#http-method-override
    Request::enableHttpMethodParameterOverride(); // <-- add this line
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
