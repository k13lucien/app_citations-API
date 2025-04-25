<?php

namespace App_citations\Controllers;

use App_citations\Entities\Like;
use App_citations\Entities\Citation;
use App_citations\Entities\Utilisateur;
use App_citations\Services\AuthService;
use Doctrine\ORM\EntityManagerInterface;

class LikeController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function likeCitation(int $citationId)
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

        $existingLike = $this->em->getRepository(Like::class)->findOneBy([
            'utilisateur' => $userId,
            'citation' => $citationId
        ]);

        if ($existingLike) {
            http_response_code(409);
            echo json_encode(['error' => 'Déjà liké.']);
            return;
        }

        $utilisateur = $this->em->find(Utilisateur::class, $userId);
        $like = new Like();
        $like->setUtilisateur($utilisateur);
        $like->setCitation($citation);

        $this->em->persist($like);
        $this->em->flush();

        echo json_encode(['message' => 'Citation likée.']);
    }

    public function unlikeCitation(int $citationId)
    {
        $userId = AuthService::getUserId();

        if (!$userId) {
            http_response_code(401);
            echo json_encode(['error' => 'Utilisateur non authentifié.']);
            return;
        }

        $like = $this->em->getRepository(Like::class)->findOneBy([
            'utilisateur' => $userId,
            'citation' => $citationId
        ]);

        if (!$like) {
            http_response_code(404);
            echo json_encode(['error' => 'Like non trouvé.']);
            return;
        }

        $this->em->remove($like);
        $this->em->flush();

        echo json_encode(['message' => 'Like retiré.']);
    }

    public function getLikes(int $citationId)
    {
        $likeRepo = $this->em->getRepository(Like::class);
        $count = $likeRepo->countLikesByCitation($citationId);

        echo json_encode(['likes' => $count]);
    }
}
