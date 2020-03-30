<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AppartementVideRepository")
 */
class AppartementVide
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
    private $rue;

    /**
     * @ORM\Column(type="integer")
     */
    private $arrondissement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etage;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeAppart;

    /**
     * @ORM\Column(type="integer")
     */
    private $prixLocation;

    /**
     * @ORM\Column(type="integer")
     */
    private $prixCharge;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ascenceur;

    /**
     * @ORM\Column(type="boolean")
     */
    private $preavis;

    /**
     * @ORM\Column(type="integer")
     */
    private $montantCotisation;

    /**
     * @ORM\Column(type="integer")
     */
    private $prixTotal;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): self
    {
        $this->rue = $rue;

        return $this;
    }

    public function getArrondissement(): ?int
    {
        return $this->arrondissement;
    }

    public function setArrondissement(int $arrondissement): self
    {
        $this->arrondissement = $arrondissement;

        return $this;
    }

    public function getEtage(): ?string
    {
        return $this->etage;
    }

    public function setEtage(string $etage): self
    {
        $this->etage = $etage;

        return $this;
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

    public function getPrixCharge(): ?int
    {
        return $this->prixCharge;
    }

    public function setPrixCharge(int $prixCharge): self
    {
        $this->prixCharge = $prixCharge;

        return $this;
    }

    public function getAscenceur(): ?bool
    {
        return $this->ascenceur;
    }

    public function setAscenceur(bool $ascenceur): self
    {
        $this->ascenceur = $ascenceur;

        return $this;
    }

    public function getPreavis(): ?bool
    {
        return $this->preavis;
    }

    public function setPreavis(bool $preavis): self
    {
        $this->preavis = $preavis;

        return $this;
    }

    public function getMontantCotisation(): ?int
    {
        return $this->montantCotisation;
    }

    public function setMontantCotisation(int $montantCotisation): self
    {
        $this->montantCotisation = $montantCotisation;

        return $this;
    }

    public function getPrixTotal(): ?int
    {
        return $this->prixTotal;
    }

    public function setPrixTotal(int $prixTotal): self
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }
}
