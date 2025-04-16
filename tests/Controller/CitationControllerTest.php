<?php

use PHPUnit\Framework\TestCase;
use App_citations\Controllers\CitationController;
use Doctrine\ORM\EntityManagerInterface;
use App_citations\Entities\Utilisateur;
use App_citations\Entities\Categorie;
use App_citations\Entities\Citation;

class CitationControllerTest extends TestCase
{
    private $emMock;
    private $controller;

    protected function setUp(): void
    {
        $this->emMock = $this->createMock(EntityManagerInterface::class);
        $this->controller = new CitationController($this->emMock);
    }

    public function testCreateCitationWithMissingFields()
    {
        $this->expectOutputString(json_encode(['error' => 'Champs manquants.']));

        $input = json_encode(['name' => 'Citation sans contenu']);
        
        $_POST['php://input'] = $input;

        $this->controller->create();
        $this->assertEquals(400, http_response_code());
    }
}
