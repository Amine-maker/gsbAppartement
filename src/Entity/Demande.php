<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\DemandeRepository")
 */
class Demande
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeAppart;

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero
     */
    private $prixLocation;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Arrondissement", inversedBy="demandes")
     */
    private $arrondissements;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="demandes")
     */
    private $client;

    public function __construct()
    {
        $this->arrondissements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeAppart(): ?string
    {
        return $this->typeAppart;
    }

    public function setTypeAppart(string $typeAppart): self
    {
        $this->typeAppart = $typeAppart;

        return $this;
    }

    public function getPrixLocation(): ?int
    {
        return $this->prixLocation;
    }

    public function setPrixLocation(int $prixLocation): self
    {
        $this->prixLocation = $prixLocation;

        return $this;
    }

    /**
     * @return Collection|Arrondissement[]
     */
    public function getArrondissements(): Collection
    {
        return $this->arrondissements;
    }

    public function addArrondissement(Arrondissement $arrondissement): self
    {
        if (!$this->arrondissements->contains($arrondissement)) {
            $this->arrondissements[] = $arrondissement;
        }

        return $this;
    }

    public function removeArrondissement(Arrondissement $arrondissement): self
    {
        if ($this->arrondissements->contains($arrondissement)) {
            $this->arrondissements->removeElement($arrondissement);
        }

        return $this;
    }

    public function getClient(): ?client
    {
        return $this->client;
    }

    public function setClient(?client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
