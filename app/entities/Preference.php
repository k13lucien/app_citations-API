<?php

namespace App_citations\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'preferences')]
class Preference
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'preferences')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Utilisateur $utilisateur;

    #[ORM\ManyToOne(targetEntity: Categorie::class, inversedBy: 'preferences')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Categorie $categorie;

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
