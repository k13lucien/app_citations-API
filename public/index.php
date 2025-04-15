<?php

use AltoRouter;

require_once __DIR__ . '/../vendor/autoload.php';

$router = new AltoRouter();

$match = $router->match();

if ($match) {
    call_user_func_array($match['target'], $match['params']);
} else {
    http_response_code(404);
    echo 'Page non trouvée';
}