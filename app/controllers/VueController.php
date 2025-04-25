<?php

namespace App_citations\Controllers;

use App_citations\Entities\Vue;
use App_citations\Entities\Citation;
use App_citations\Entities\Utilisateur;
use App_citations\Services\AuthService;
use Doctrine\ORM\EntityManagerInterface;

class VueController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function addVue(int $citationId)
    {
        $userId = AuthService::getUserId();

        if (!$userId) {
            http_response_code(401);
            echo json_encode(['error' => 'Utilisateur non authentifié.']);
            return;
        }

        $citation = $this->em->find(Citation::class, $citationId);

        if (!$citation) {
            http_response_code(404);
            echo json_encode(['error' => 'Citation non trouvée.']);
            return;
        }

        $utilisateur = $this->em->find(Utilisateur::class, $userId);
        $vue = new Vue();
        $vue->setUtilisateur($utilisateur);
        $vue->setCitation($citation);

        $this->em->persist($vue);
        $this->em->flush();

        echo json_encode(['message' => 'Vue ajoutée.']);
    }

    public function getVues(int $citationId)
    {
        $vueRepo = $this->em->getRepository(Vue::class);
        $count = $vueRepo->countVuesByCitation($citationId);

        echo json_encode(['vues' => $count]);
    }
}
