<?php

namespace App\Entity;

use App\Repository\OrdonnancementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrdonnancementRepository::class)
 */
class Ordonnancement
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
    private $ordre;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datePrevi;

    /**
     * @ORM\Column(type="integer")
     */
    private $ilotOrigine;

    /**
     * @ORM\Column(type="integer")
     */
    private $ilotDest;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getDatePrevi(): ?\DateTimeInterface
    {
        return $this->datePrevi;
    }

    public function setDatePrevi(\DateTimeInterface $datePrevi): self
    {
        $this->datePrevi = $datePrevi;

        return $this;
    }

    public function getIlotOrigine(): ?int
    {
        return $this->ilotOrigine;
    }

    public function setIlotOrigine(int $ilotOrigine): self
    {
        $this->ilotOrigine = $ilotOrigine;

        return $this;
    }

    public function getIlotDest(): ?int
    {
        return $this->ilotDest;
    }

    public function setIlotDest(int $ilotDest): self
    {
        $this->ilotDest = $ilotDest;

        return $this;
    }
}
