<?php

namespace App_citations\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'categories')]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $name;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Citation::class, cascade: ['remove'], orphanRemoval: true)]
    private Collection $citations;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Preference::class, cascade: ['remove'], orphanRemoval: true)]
    private Collection $preferences;

    public function __construct()
    {
        $this->citations = new ArrayCollection();
        $this->preferences = new ArrayCollection();
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

    /** @return Collection<int, Citation> */
    public function getCitations(): Collection
    {
        return $this->citations;
    }

    public function addCitation(Citation $citation): self
    {
        if (!$this->citations->contains($citation)) {
            $this->citations[] = $citation;
            $citation->setCategorie($this);
        }
        return $this;
    }

    public function removeCitation(Citation $citation): self
    {
        if ($this->citations->removeElement($citation)) {
            if ($citation->getCategorie() === $this) {
                $citation->setCategorie(null);
            }
        }
        return $this;
    }

    /** @return Collection<int, Preference> */
    public function getPreferences(): Collection
    {
        return $this->preferences;
    }

    public function addPreference(Preference $preference): self
    {
        if (!$this->preferences->contains($preference)) {
            $this->preferences[] = $preference;
            $preference->setCategorie($this);
        }
        return $this;
    }

    public function removePreference(Preference $preference): self
    {
        if ($this->preferences->removeElement($preference)) {
            if ($preference->getCategorie() === $this) {
                $preference->setCategorie(null);
            }
        }
        return $this;
    }

}
