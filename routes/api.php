<?php

use App_citations\Controllers\{CitationController, UtilisateurController, CategorieController, PreferenceController, LikeController, VueController};
use App_citations\Middlewares\{AuthMiddleware, JwtMiddleware, CitationOwnerMiddleware};

function registerRoutes($router, $entityManager) {

    $router->map('POST', '/citations', function () use ($entityManager) {
        JwtMiddleware::handle();
        (new CitationController($entityManager))->create();
    });

    $router->map('GET', '/citations', function () use ($entityManager) {
        JwtMiddleware::handle();
        (new CitationController($entityManager))->index();
    });

    $router->map('GET', '/citations/[i:id]', function ($id) use ($entityManager) {
        JwtMiddleware::handle();
        (new CitationController($entityManager))->show($id);
    });
    
    $router->map('PUT', '/citations/[i:id]', function ($id) use ($entityManager) {
        CitationOwnerMiddleware::checkOwnership($entityManager, $id);
        (new CitationController($entityManager))->update($id);
    });
    
    $router->map('DELETE', '/citations/[i:id]', function ($id) use ($entityManager) {
        CitationOwnerMiddleware::checkOwnership($entityManager, $id);
        (new CitationController($entityManager))->delete($id);
    });
    
    $router->map('GET', '/citations/utilisateur/[i:id]', function ($id) use ($entityManager) {
        AuthMiddleware::ensureUser($id);
        (new CitationController($entityManager))->getByUtilisateur($id);
    });
    
    $router->map('GET', '/citations/categorie/[i:id]', function ($id) use ($entityManager) {
        JwtMiddleware::handle();
        (new CitationController($entityManager))->getByCategorie($id);
    });

    $router->map('POST', '/utilisateurs/register', function () use ($entityManager) {
        (new UtilisateurController($entityManager))->register();
    });

    $router->map('POST', '/utilisateurs/login', function () use ($entityManager) {
        (new UtilisateurController($entityManager))->login();
    });

    $router->map('GET', '/utilisateurs/[i:id]', function ($id) use ($entityManager) {
        AuthMiddleware::ensureUser($id);
        (new UtilisateurController($entityManager))->show($id);
    });

    $router->map('PUT', '/utilisateurs/[i:id]', function ($id) use ($entityManager) {
        AuthMiddleware::ensureUser($id);
        (new UtilisateurController($entityManager))->update($id);
    });

    $router->map('DELETE', '/utilisateurs/[i:id]', function ($id) use ($entityManager) {
        AuthMiddleware::ensureUser($id);
        (new UtilisateurController($entityManager))->delete($id);
    });

    $router->map('GET', '/categories', function () use ($entityManager) {
        JwtMiddleware::handle();
        (new CategorieController($entityManager))->index();
    });

    $router->map('GET', '/categories/[i:id]', function ($id) use ($entityManager) {
        JwtMiddleware::handle();
        (new CategorieController($entityManager))->show($id);
    });

    $router->map('POST', '/categories', function () use ($entityManager) {
        JwtMiddleware::handle();
        (new CategorieController($entityManager))->create();
    });

    $router->map('GET', '/utilisateurs/[i:id]/preferences', function ($id) use ($entityManager) {
        AuthMiddleware::ensureUser($id);
        (new PreferenceController($entityManager))->index($id);
    });

    $router->map('POST', '/utilisateurs/[i:id]/preferences', function ($id) use ($entityManager) {
        AuthMiddleware::ensureUser($id);
        (new PreferenceController($entityManager))->add($id);
    });

    $router->map('DELETE', '/utilisateurs/[i:id]/preferences', function ($id) use ($entityManager) {
        AuthMiddleware::ensureUser($id);
        (new PreferenceController($entityManager))->delete($id);
    });

    $router->map('POST', '/citations/[i:id]/like', function ($id) use ($entityManager) {
        JwtMiddleware::handle();
        (new LikeController($entityManager))->likeCitation($id);
    });

    $router->map('DELETE', '/citations/[i:id]/like', function ($id) use ($entityManager) {
        JwtMiddleware::handle();
        (new LikeController($entityManager))->unlikeCitation($id);
    });

    $router->map('GET', '/citations/[i:id]/likes', function ($id) use ($entityManager) {
        JwtMiddleware::handle();
        (new LikeController($entityManager))->getLikes($id);
    });

    $router->map('POST', '/citations/[i:id]/vue', function ($id) use ($entityManager) {
        JwtMiddleware::handle();
        (new VueController($entityManager))->addVue($id);
    });

    $router->map('GET', '/citations/[i:id]/vues', function ($id) use ($entityManager) {
        JwtMiddleware::handle();
        (new VueController($entityManager))->getVues($id);
    });
}
