<?php

namespace App_citations\Middlewares;

use App_citations\Core\Auth;
use App_citations\Entities\Citation;

class CitationOwnerMiddleware
{
    public static function checkOwnership($entityManager, $citationId)
    {
        $payload = JwtMiddleware::handle();
        $userId = $payload->user_id;

        $citation = $entityManager->getRepository(Citation::class)->find($citationId);

        if (!$citation) {
            http_response_code(404);
            echo json_encode(['message' => 'Citation introuvable']);
            exit;
        }

        if ($citation->getUtilisateur()->getId() !== $userId) {
            http_response_code(403);
            echo json_encode(['message' => 'Vous ne pouvez pas manipuler cette citation']);
            exit;
        }

        return $citation;
    }
}
