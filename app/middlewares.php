<?php

$app->add($container->get('csrf'));
$app->add(new App\Middleware\DisplayInputErrorsMiddleware($container));
$app->add(new App\Middleware\OldInputMiddleware($container));