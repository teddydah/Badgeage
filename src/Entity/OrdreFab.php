<?php

namespace App\Entity;

use App\Repository\OrdreFabRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrdreFabRepository::class)
 */
class OrdreFab
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $numero;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateEcheance;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getDateEcheance(): ?\DateTimeInterface
    {
        return $this->dateEcheance;
    }

    public function setDateEcheance(\DateTimeInterface $dateEcheance): self
    {
        $this->dateEcheance = $dateEcheance;

        return $this;
    }
}
