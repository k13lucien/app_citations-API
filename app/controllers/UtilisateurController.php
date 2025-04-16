<?php

namespace App_citations\Controllers;

use App_citations\Entities\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;

class UtilisateurController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function register()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['email']) || empty($data['password']) || empty($data['name'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Champs requis manquants.']);
            return;
        }

        $utilisateur = new Utilisateur();
        $utilisateur->setEmail($data['email']);
        $utilisateur->setName($data['name']);
        $utilisateur->setSurname($data['surname']);
        $utilisateur->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));

        $this->em->persist($utilisateur);
        $this->em->flush();

        http_response_code(201);
        echo json_encode(['message' => 'Utilisateur créé avec succès.']);
    }

    public function login()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $repo = $this->em->getRepository(Utilisateur::class);
        $user = $repo->findOneBy(['email' => $data['email'] ?? '']);

        if (!$user || !password_verify($data['password'], $user->getPassword())) {
            http_response_code(401);
            echo json_encode(['error' => 'Email ou mot de passe invalide.']);
            return;
        }

        // Token JWT sera généré ici plus tard
        http_response_code(200);
        echo json_encode(['message' => 'Connexion réussie']);
    }

    public function show($id)
    {
        $user = $this->em->find(Utilisateur::class, $id);

        if (!$user) {
            http_response_code(404);
            echo json_encode(['error' => 'Utilisateur non trouvé']);
            return;
        }

        echo json_encode([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'surname' => $user->getSurname(),
            'countCitations' => $user->getCountCitations(),
            'createdAt' => $user->getCreatedAt(),
            'updatedAt' => $user->getUpdatedAt()
        ]);
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $user = $this->em->find(Utilisateur::class, $id);

        if (!$user) {
            http_response_code(404);
            echo json_encode(['error' => 'Utilisateur non trouvé']);
            return;
        }

        if (!empty($data['email'])) $user->setEmail($data['email']);
        if (!empty($data['nom'])) $user->setNom($data['name']);
        if (!empty($data['surname'])) $user->setSurname($data['surname']);
        if (!empty($data['password'])) $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));

        $user->setUpdatedAt(new \DateTimeImmutable());

        $this->em->flush();
        echo json_encode(['message' => 'Utilisateur mis à jour avec succès']);
    }

    public function delete($id)
    {
        $user = $this->em->find(Utilisateur::class, $id);

        if (!$user) {
            http_response_code(404);
            echo json_encode(['error' => 'Utilisateur non trouvé']);
            return;
        }

        $this->em->remove($user);
        $this->em->flush();

        echo json_encode(['message' => 'Utilisateur supprimé avec succès']);
    }
}
