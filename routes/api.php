<?php

use App_citations\Controllers\CitationController;
use App_citations\Controllers\UtilisateurController;

function registerRoutes($router, $entityManager) {

    $router->map('POST', '/citations', function () use ($entityManager) {
        (new CitationController($entityManager))->create();
    });

    $router->map('GET', '/citations', function () use ($entityManager) {
        (new CitationController($entityManager))->index();
    });

    $router->map('GET', '/citations/[i:id]', function ($id) use ($entityManager) {
        (new CitationController($entityManager))->show($id);
    });
    
    $router->map('PUT', '/citations/[i:id]', function ($id) use ($entityManager) {
        (new CitationController($entityManager))->update($id);
    });
    
    $router->map('DELETE', '/citations/[i:id]', function ($id) use ($entityManager) {
        (new CitationController($entityManager))->delete($id);
    });
    
    $router->map('GET', '/citations/utilisateur/[i:id]', function ($id) use ($entityManager) {
        (new CitationController($entityManager))->getByUtilisateur($id);
    });
    
    $router->map('GET', '/citations/categorie/[i:id]', function ($id) use ($entityManager) {
        (new CitationController($entityManager))->getByCategorie($id);
    });
    
    $router->map('POST', '/citations/[i:id]/like', function ($id) use ($entityManager) {
        (new CitationController($entityManager))->addLike($id);
    });
    
    $router->map('POST', '/citations/[i:id]/vue', function ($id) use ($entityManager) {
        (new CitationController($entityManager))->addVue($id);
    });

    // Enregistrement d’un utilisateur
    $router->map('POST', '/utilisateurs/register', function () use ($entityManager) {
        (new UtilisateurController($entityManager))->register();
    });

    // Connexion d’un utilisateur
    $router->map('POST', '/utilisateurs/login', function () use ($entityManager) {
        (new UtilisateurController($entityManager))->login();
    });

    // Récupération des infos d’un utilisateur
    $router->map('GET', '/utilisateurs/[i:id]', function ($id) use ($entityManager) {
        (new UtilisateurController($entityManager))->show($id);
    });

    // Mise à jour des infos d’un utilisateur
    $router->map('PUT', '/utilisateurs/[i:id]', function ($id) use ($entityManager) {
        (new UtilisateurController($entityManager))->update($id);
    });

    // Suppression d’un utilisateur
    $router->map('DELETE', '/utilisateurs/[i:id]', function ($id) use ($entityManager) {
        (new UtilisateurController($entityManager))->delete($id);
    });
}
