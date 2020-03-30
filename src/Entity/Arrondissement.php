<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArrondissementRepository")
 */
class Arrondissement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(max=20,
     *               maxMessage = "L'arrondissement ne peux depasser 20"
     * )
     * 
     * 
     */
    private $arrondissementDemande;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Demande", mappedBy="arrondissements")
     */
    private $demandes;

    public function __construct()
    {
        $this->demandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArrondissementDemande(): ?int
    {
        return $this->arrondissementDemande;
    }

    public function setArrondissementDemande(int $arrondissementDemande): self
    {
        $this->arrondissementDemande = $arrondissementDemande;

        return $this;
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
            $demande->addArrondissement($this);
        }

        return $this;
    }

    public function removeDemande(Demande $demande): self
    {
        if ($this->demandes->contains($demande)) {
            $this->demandes->removeElement($demande);
            $demande->removeArrondissement($this);
        }

        return $this;
    }
}
