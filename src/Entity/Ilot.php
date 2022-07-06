<?php

namespace App\Entity;

use App\Repository\IlotRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IlotRepository::class)
 */
class Ilot
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $nomAX;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomIRL;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $nomURL;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $codeImprimante;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomAX(): ?int
    {
        return $this->nomAX;
    }

    public function setNomAX(int $nomAX): self
    {
        $this->nomAX = $nomAX;

        return $this;
    }

    public function getNomIRL(): ?string
    {
        return $this->nomIRL;
    }

    public function setNomIRL(string $nomIRL): self
    {
        $this->nomIRL = $nomIRL;

        return $this;
    }

    public function getNomURL(): ?string
    {
        return $this->nomURL;
    }

    public function setNomURL(string $nomURL): self
    {
        $this->nomURL = $nomURL;

        return $this;
    }

    public function getCodeImprimante(): ?string
    {
        return $this->codeImprimante;
    }

    public function setCodeImprimante(string $codeImprimante): self
    {
        $this->codeImprimante = $codeImprimante;

        return $this;
    }
}
