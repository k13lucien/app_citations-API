<?php

namespace App_citations\Controllers;

use App_citations\Entities\Citation;
use App_citations\Entities\Categorie;
use App_citations\Entities\Utilisateur;
use App_citations\Entities\Preference;
use App_citations\Services\Mailer;
use Doctrine\ORM\EntityManagerInterface;

class CitationController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['name'], $data['content'], $data['utilisateur_id'], $data['categorie_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Champs manquants.']);
            return;
        }

        $utilisateur = $this->em->find(Utilisateur::class, $data['utilisateur_id']);
        $categorie = $this->em->find(Categorie::class, $data['categorie_id']);

        if (!$utilisateur || !$categorie) {
            http_response_code(404);
            echo json_encode(['error' => 'Utilisateur ou catégorie non trouvée.']);
            return;
        }

        $citation = new Citation();
        $citation->setName($data['name'])
                 ->setContent($data['content'])
                 ->setUtilisateur($utilisateur)
                 ->setCategorie($categorie);

        $this->em->persist($citation);
        $this->em->flush();

        http_response_code(201);
        echo json_encode(['message' => 'Citation créée avec succès.']);
        $this->notifierUtilisateurs($categorie, $citation);

    }

    public function index()
    {
        $citations = $this->em->getRepository(Citation::class)->findAll();

        $data = array_map(function (Citation $citation) {
            return [
                'id' => $citation->getId(),
                'name' => $citation->getName(),
                'content' => $citation->getContent(),
                'count_view' => $citation->getVues(),
                'count_like' => $citation->getLikes(),
                'created_at' => $citation->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at' => $citation->getUpdatedAt()?->format('Y-m-d H:i:s'),
                'utilisateur_id' => $citation->getUtilisateur()->getId(),
                'categorie_id' => $citation->getCategorie()->getId()
            ];
        }, $citations);

        echo json_encode($data);
    }

    public function show(int $id)
    {
        $citation = $this->em->find(Citation::class, $id);

        if (!$citation) {
            http_response_code(404);
            echo json_encode(['error' => 'Citation non trouvée.']);
            return;
        }

        echo json_encode([
            'id' => $citation->getId(),
            'name' => $citation->getName(),
            'content' => $citation->getContent(),
            'count_view' => $citation->getVues(),
            'count_like' => $citation->getLikes(),
            'created_at' => $citation->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $citation->getUpdatedAt()?->format('Y-m-d H:i:s'),
            'utilisateur_id' => $citation->getUtilisateur()->getId(),
            'categorie_id' => $citation->getCategorie()->getId()
        ]);
    }

    public function update(int $id)
    {
        $citation = $this->em->find(Citation::class, $id);

        if (!$citation) {
            http_response_code(404);
            echo json_encode(['error' => 'Citation non trouvée.']);
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['name'])) {
            $citation->setName($data['name']);
        }

        if (isset($data['content'])) {
            $citation->setContent($data['content']);
        }

        $citation->setUpdatedAt(new \DateTimeImmutable());

        $this->em->flush();

        echo json_encode(['message' => 'Citation mise à jour avec succès.']);
    }

    public function delete(int $id)
    {
        $citation = $this->em->find(Citation::class, $id);

        if (!$citation) {
            http_response_code(404);
            echo json_encode(['error' => 'Citation non trouvée.']);
            return;
        }

        $this->em->remove($citation);
        $this->em->flush();

        echo json_encode(['message' => 'Citation supprimée avec succès.']);
    }

    public function getByUtilisateur(int $id)
    {
        $citations = $this->em->getRepository(Citation::class)->findBy(['utilisateur' => $id]);

        $data = array_map(function (Citation $citation) {
            return [
                'id' => $citation->getId(),
                'name' => $citation->getName(),
                'content' => $citation->getContent(),
                'categorie' => $citation->getCategorie()->getName(),
                'nombre_vue' => $citation->getVues(),
                'nombre_like' => $citation->getLikes(),
            ];
        }, $citations);

        echo json_encode($data);
    }

    public function getByCategorie(int $id)
    {
        $citations = $this->em->getRepository(Citation::class)->findBy(['categorie' => $id]);

        $data = array_map(function (Citation $citation) {
            return [
                'id' => $citation->getId(),
                'name' => $citation->getName(),
                'content' => $citation->getContent(),
                'utilisateur' => $citation->getUtilisateur()->getEmail(),
                'nombre_vue' => $citation->getVues(),
                'nombre_like' => $citation->getLikes(),
            ];
        }, $citations);

        echo json_encode($data);
    }

    private function notifierUtilisateurs(Categorie $categorie, Citation $citation)
    {
        $preferences = $this->em->getRepository(Preference::class)->findBy(['categorie' => $categorie]);

        foreach ($preferences as $preference) {
            $utilisateur = $preference->getUtilisateur();

            $subject = "Nouvelle citation dans votre catégorie préférée !";
            $body = "<p>Bonjour {$utilisateur->getName()},</p>
                    <p>Une nouvelle citation a été ajoutée dans la catégorie que vous suivez :</p>
                    <blockquote>{$citation->getContent()}</blockquote>
                    <p>À bientôt sur notre plateforme !</p>";

            Mailer::send($utilisateur->getEmail(), $subject, $body);
        }
    }

}