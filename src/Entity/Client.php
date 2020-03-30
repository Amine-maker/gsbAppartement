<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 */
class Client extends Utilisateur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Demande", mappedBy="client", cascade={"persist", "remove"})
     * 
     */
    private $demandes;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Appartement", inversedBy="clients")
     */
    private $visite;


    public function __construct()
    {
        $this->demandes = new ArrayCollection();
        $this->visite = new ArrayCollection();
    }

    public function getIdC(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Demande[]
     */
    public function getDemandes(): Collection
    {
        return $this->demandes;
    }

    public function addDemande(Demande $demande): self
    {
        if (!$this->demandes->contains($demande)) {
            $this->demandes[] = $demande;
            $demande->setClient($this);
        }

        return $this;
    }

    public function removeDemande(Demande $demande): self
    {
        if ($this->demandes->contains($demande)) {
            $this->demandes->removeElement($demande);
            // set the owning side to null (unless already changed)
            if ($demande->getClient() === $this) {
                $demande->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|appartement[]
     */
    public function getVisite(): Collection
    {
        return $this->visite;
    }

    public function addVisite(appartement $visite): self
    {
        if (!$this->visite->contains($visite)) {
            $this->visite[] = $visite;
        }

        return $this;
    }

    public function removeVisite(appartement $visite): self
    {
        if ($this->visite->contains($visite)) {
            $this->visite->removeElement($visite);
        }

        return $this;
    }

}
