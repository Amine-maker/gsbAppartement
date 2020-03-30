<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocataireRepository")
 */
class Locataire extends Utilisateur
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
    private $rib;

    /**
     * @ORM\Column(type="integer")
     */
    private $telBanque;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Appartement", cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="cascade")
     */
    private $appartement;

    public function getIdL(): ?int
    {
        return $this->id;
    }
    

    public function getRib(): ?string
    {
        return $this->rib;
    }

    public function setRib(string $rib): self
    {
        $this->rib = $rib;

        return $this;
    }

    public function getTelBanque(): ?int
    {
        return $this->telBanque;
    }

    public function setTelBanque(int $telBanque): self
    {
        $this->telBanque = $telBanque;

        return $this;
    }

    public function getAppartement(): ?Appartement
    {
        return $this->appartement;
    }

    public function setAppartement(?Appartement $appartement): self
    {
        $this->appartement = $appartement;

        return $this;
    }
}
