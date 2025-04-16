<?php

namespace App_citations\Entities;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

#[ORM\Entity]
#[ORM\Table(name: 'citations')]
class Citation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'text')]
    private string $content;

    #[ORM\Column(type: 'integer')]
    private int $countView = 0;

    #[ORM\Column(type: 'integer')]
    private int $countLike = 0;

    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(name: 'updated_at', type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'citations')]
    #[ORM\JoinColumn(name: 'utilisateur_id', referencedColumnName: 'id', nullable: false)]
    private Utilisateur $utilisateur;

    #[ORM\ManyToOne(targetEntity: Categorie::class, inversedBy: 'citations')]
    #[ORM\JoinColumn(name: 'categorie_id', referencedColumnName: 'id', nullable: false)]
    private Categorie $categorie;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getCountView(): int
    {
        return $this->countView;
    }

    public function setCountView(int $countView): self
    {
        $this->countView = $countView;
        return $this;
    }

    public function incrementView(): self
    {
        $this->countView++;
        return $this;
    }

    public function getCountLike(): int
    {
        return $this->countLike;
    }

    public function setCountLike(int $countLike): self
    {
        $this->countLike = $countLike;
        return $this;
    }

    public function addLike(): self
    {
        $this->countLike++;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
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

    public function getCategorie(): Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(Categorie $categorie): self
    {
        $this->categorie = $categorie;
        return $this;
    }

}
