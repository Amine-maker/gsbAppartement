<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProprietaireRepository")
 */
class Proprietaire extends Utilisateur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Appartement", mappedBy="proprietaire",cascade={"persist", "remove"}, orphanRemoval=false)
     */
    private $appartements;

    public function __construct()
    {
        $this->appartements = new ArrayCollection();
    }

    public function getIdP(): ?int
    {
        return $this->id;
    }


    /**
     * @return Collection|Appartement[]
     */
    public function getAppartements(): Collection
    {
        return $this->appartements;
    }

    public function addAppartement(Appartement $appartement): self
    {
        if (!$this->appartements->contains($appartement)) {
            $this->appartements[] = $appartement;
            $appartement->setProprietaire($this);
        }

        return $this;
    }

    public function removeAppartement(Appartement $appartement): self
    {
        if ($this->appartements->contains($appartement)) {
            $this->appartements->removeElement($appartement);
            // set the owning side to null (unless already changed)
            if ($appartement->getProprietaire() === $this) {
                $appartement->setProprietaire(null);
            }
        }

        return $this;
    }
}
