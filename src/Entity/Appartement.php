<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AppartementRepository")
 */
class Appartement
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
     * @ORM\Column(type="string", length=255)
     */
    private $arrondissement;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(min = 0)
     */
    private $etage;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeAppart;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(min = 0)
     */
    private $prixLocation;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(min = 0)
     */
    private $prixCharge;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ascenseur;

    /**
     * @ORM\Column(type="boolean")
     */
    private $preavis;

    /**
     * @ORM\Column(type="integer")
     */
    private $montantCotisation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Proprietaire", inversedBy="appartements", cascade={"persist"})
     * @ORM\JoinColumn(name="proprietaire_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $proprietaire;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Client", mappedBy="visite")
     */
    private $clients;

    /**
     * @ORM\Column(type="integer")
     */
    private $prixTotal;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
    }


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

    public function getArrondissement(): ?string
    {
        return $this->arrondissement;
    }

    public function setArrondissement(string $arrondissement): self
    {
        $this->arrondissement = $arrondissement;

        return $this;
    }

    public function getEtage(): ?int
    {
        return $this->etage;
    }

    public function setEtage(int $etage): self
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

    public function getAscenseur(): ?bool
    {
        return $this->ascenseur;
    }

    public function setAscenseur(bool $ascenseur): self
    {
        $this->ascenseur = $ascenseur;

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

    public function getProprietaire(): ?proprietaire
    {
        return $this->proprietaire;
    }

    public function setProprietaire(?proprietaire $proprietaire): self
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }

    /**
     * @return Collection|Client[]
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients[] = $client;
            $client->addVisite($this);
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        if ($this->clients->contains($client)) {
            $this->clients->removeElement($client);
            $client->removeVisite($this);
        }

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
