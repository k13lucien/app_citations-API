<?php

namespace App_citations\Controllers;

use App_citations\Entities\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;

class VerificationController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function verifyEmail($params)
    {
        $token = $params['token'] ?? null;

        if (!$token) {
            http_response_code(400);
            echo json_encode(['error' => 'Token manquant']);
            return;
        }

        $userRepo = $this->em->getRepository(Utilisateur::class);
        $user = $userRepo->findOneBy(['emailVerificationToken' => $token]);

        if (!$user) {
            http_response_code(400);
            echo json_encode(['error' => 'Token invalide']);
            return;
        }

        $user->setIsVerified(true);
        $user->setEmailVerificationToken(null);

        $this->em->flush();
        $this->em->refresh($user);

        echo json_encode(['message' => 'Email vérifié avec succès !']);
    }
}
