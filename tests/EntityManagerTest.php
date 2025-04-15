<?php

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class EntityManagerTest extends TestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = require __DIR__ . '/../config/bootstrap.php';
    }

    public function testEntityManagerConnection(): void
    {
        $this->assertInstanceOf(EntityManagerInterface::class, $this->entityManager);
        $this->assertTrue($this->entityManager->isOpen());
    }
}
