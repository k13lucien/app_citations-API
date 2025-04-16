<?php
use AltoRouter;

$router = new AltoRouter();

$router->map('GET', '/', function () {
    echo "Bienvenue sur la page d'accueil !";
});

$router->map('GET', '/citations', [\App\controllers\CitationController::class, 'index']);
$router->map('POST', '/citations', [\App\controllers\CitationController::class, 'store']);

// route dynamique
$router->map('GET', '/citations/[i:id]', [\App\controllers\CitationController::class, 'show']);
