<?php

namespace App_citations\Controllers;

use App_citations\Entities\Preference;
use App_citations\Entities\Utilisateur;
use App_citations\Entities\Categorie;
use Doctrine\ORM\EntityManagerInterface;

class PreferenceController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function index($utilisateurId)
    {
        $preferences = $this->entityManager->getRepository(Preference::class)->findBy([
            'utilisateur' => $utilisateurId
        ]);

        $data = array_map(fn($pref) => [
            'categorie_id' => $pref->getCategorie()->getId(),
            'categorie_name' => $pref->getCategorie()->getName()
        ], $preferences);

        echo json_encode($data);
    }

    public function add($utilisateurId)
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['categorie_id'])) {
            http_response_code(400);
            echo json_encode(['message' => 'categorie_id requis.']);
            return;
        }

        $utilisateur = $this->entityManager->getRepository(Utilisateur::class)->find($utilisateurId);
        $categorie = $this->entityManager->getRepository(Categorie::class)->find($data['categorie_id']);

        if (!$utilisateur || !$categorie) {
            http_response_code(404);
            echo json_encode(['message' => 'Utilisateur ou catégorie introuvable.']);
            return;
        }

        $existing = $this->entityManager->getRepository(Preference::class)->findOneBy([
            'utilisateur' => $utilisateur,
            'categorie' => $categorie
        ]);

        if ($existing) {
            http_response_code(409);
            echo json_encode(['message' => 'Préférence déjà ajoutée.']);
            return;
        }

        $preference = new Preference();
        $preference->setUtilisateur($utilisateur);
        $preference->setCategorie($categorie);

        $this->entityManager->persist($preference);
        $this->entityManager->flush();

        echo json_encode(['message' => 'Préférence ajoutée.']);
    }

    public function delete($utilisateurId)
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['categorie_id'])) {
            http_response_code(400);
            echo json_encode(['message' => 'categorie_id requis.']);
            return;
        }

        $utilisateur = $this->entityManager->getRepository(Utilisateur::class)->find($utilisateurId);
        $categorie = $this->entityManager->getRepository(Categorie::class)->find($data['categorie_id']);

        if (!$utilisateur || !$categorie) {
            http_response_code(404);
            echo json_encode(['message' => 'Utilisateur ou catégorie introuvable.']);
            return;
        }

        $preference = $this->entityManager->getRepository(Preference::class)->findOneBy([
            'utilisateur' => $utilisateur,
            'categorie' => $categorie
        ]);

        if (!$preference) {
            http_response_code(404);
            echo json_encode(['message' => 'Préférence non trouvée.']);
            return;
        }

        $this->entityManager->remove($preference);
        $this->entityManager->flush();

        echo json_encode(['message' => 'Préférence supprimée.']);
    }
}
