<?php

use PHPUnit\Framework\TestCase;
use App_citations\Entities\Citation;
use App_citations\Entities\Utilisateur;
use App_citations\Entities\Categorie;

class CitationTest extends TestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = require __DIR__ . '/../../config/bootstrap.php';
    }

    public function testCreateCitation()
    {
        $categorie = new Categorie();
        $categorie->setName('Inspiration');

        $utilisateur = new Utilisateur();
        $utilisateur->setName('root');
        $utilisateur->setSurname('admin');
        $utilisateur->setEmail('john@example.com');
        $utilisateur->setPassword('secret');

        $citation = new Citation();
        $citation->setName('ohh la vie');
        $citation->setContent('La vie est belle.');
        $citation->setCategorie($categorie);
        $citation->setUtilisateur($utilisateur);
        $citation->setCreatedAt(new \DateTimeImmutable());


        $this->entityManager->persist($categorie);
        $this->entityManager->persist($utilisateur);
        $this->entityManager->persist($citation);
        $this->entityManager->flush();

        $this->assertNotNull($citation->getId());
        $this->assertInstanceOf(\DateTimeImmutable::class, $citation->getCreatedAt());
    }
}
