<?php

require_once __DIR__ . '/../vendor/autoload.php';

$entityManager = require __DIR__ . '/../config/bootstrap.php';

$router = new AltoRouter();
$router->setBasePath('/api');

require_once __DIR__ . '/../routes/api.php';
registerRoutes($router, $entityManager);

$match = $router->match();

if ($match && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Page non trouvée']);
}