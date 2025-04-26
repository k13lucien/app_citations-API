<?php

namespace App_citations\Controllers;

use App_citations\Entities\Utilisateur;
use App_citations\Services\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use App_citations\Core\Auth;

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

        if (empty($data['email']) || empty($data['password']) || empty($data['name']) || empty($data['surname'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Champs requis manquants.']);
            return;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['error' => 'Format de l\'email invalide.']);
            return;
        }

        $existingUser = $this->em->getRepository(Utilisateur::class)->findOneBy(['email' => $data['email']]);

        if ($existingUser) {
            http_response_code(409);
            echo json_encode(['error' => 'Cet email est déjà utilisé.']);
            return;
        }

        $utilisateur = new Utilisateur();
        $utilisateur->setEmail($data['email']);
        $utilisateur->setName($data['name']);
        $utilisateur->setSurname($data['surname']);
        $utilisateur->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));
        $verificationToken = bin2hex(random_bytes(32));
        $utilisateur->setEmailVerificationToken($verificationToken);
        $utilisateur->setIsVerified(false);

        $this->em->persist($utilisateur);
        $this->em->flush();

        $verificationLink = "http://localhost:8000/api/verify-email?token=" . urlencode($verificationToken);
    
        $subject = "Confirme ton email";
        $body = "Clique sur ce lien pour confirmer ton compte : <a href='$verificationLink'>$verificationLink</a>";
    
        (new Mailer())->send($utilisateur->getEmail(), $subject, $body);

        http_response_code(201);
        echo json_encode(['message' => "Compte créé. Un email de confirmation a été envoyé."]);
    }

    public function login(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;
    
        $utilisateur = $this->em
            ->getRepository(Utilisateur::class)
            ->findOneBy(['email' => $email]);
    
        if (!$utilisateur || !password_verify($password, $utilisateur->getPassword())) {
            http_response_code(401);
            echo json_encode(['message' => 'Email ou mot de passe incorrect']);
            return;
        }

        if (!$utilisateur->isVerified()) {
            http_response_code(403);
            echo json_encode(['error' => "Confirme ton email avant de te connecter.", "code" => $utilisateur->isVerified()]);
            return;
        }
    
        $jwt = Auth::generateJWT($utilisateur->getId(), $utilisateur->getEmail());

        echo json_encode(['token' => $jwt]);
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
