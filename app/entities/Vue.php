<?php

namespace App_citations\Entities;

use App_citations\Repositories\VueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VueRepository::class)]
#[ORM\Table(name: 'vues')]
class Vue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'vues')]
    #[ORM\JoinColumn(name: "utilisateur_id", referencedColumnName: "id", nullable: false)]
    private Utilisateur $utilisateur;
    
    #[ORM\ManyToOne(targetEntity: Citation::class, inversedBy: 'vues')]
    #[ORM\JoinColumn(name: "citation_id", referencedColumnName: "id", nullable: false)]
    private Citation $citation;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateur(): Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    public function getCitation(): Citation
    {
        return $this->citation;
    }

    public function setCitation(Citation $citation): self
    {
        $this->citation = $citation;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }
}
