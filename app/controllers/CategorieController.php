<?php

namespace App_citations\Controllers;

use App_citations\Entities\Categorie;
use Doctrine\ORM\EntityManager;

class CategorieController
{
    private $entityManager;

    public function __construct(EntityManager $em)
    {
        $this->entityManager = $em;
    }

    public function index()
    {
        $categories = $this->entityManager->getRepository(Categorie::class)->findAll();
        $data = array_map(fn($cat) => [
            'id' => $cat->getId(),
            'name' => $cat->getName()
        ], $categories);

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function show($id)
    {
        $categorie = $this->entityManager->getRepository(Categorie::class)->find($id);

        if (!$categorie) {
            http_response_code(404);
            echo json_encode(['message' => 'Catégorie non trouvée']);
            return;
        }

        echo json_encode([
            'id' => $categorie->getId(),
            'name' => $categorie->getName()
        ]);
    }

    public function create()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['name'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Le nom de la catégorie est requis']);
            return;
        }

        $categorie = new Categorie();
        $categorie->setName($data['name']);

        $this->entityManager->persist($categorie);
        $this->entityManager->flush();

        http_response_code(201);
        echo json_encode(['message' => 'Catégorie créée', 'id' => $categorie->getId()]);
    }

}
