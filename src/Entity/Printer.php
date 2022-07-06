<?php

namespace App\Entity;

use App\Repository\PrinterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PrinterRepository::class)
 */
class Printer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $ip;

    /**
     * @ORM\Column(type="integer")
     */
    private $port;

    /**
     * @ORM\OneToMany(targetEntity=Ilot::class, mappedBy="printer")
     */
    private $ilots;

    public function __construct()
    {
        $this->ilots = new ArrayCollection();
        $this->port = 9100;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function setPort(int $port): self
    {
        $this->port = $port;

        return $this;
    }

    /**
     * @return Collection<int, Ilot>
     */
    public function getIlots(): Collection
    {
        return $this->ilots;
    }

    public function addIlot(Ilot $ilot): self
    {
        if (!$this->ilots->contains($ilot)) {
            $this->ilots[] = $ilot;
            $ilot->setPrinter($this);
        }

        return $this;
    }

    public function removeIlot(Ilot $ilot): self
    {
        if ($this->ilots->removeElement($ilot)) {
            // set the owning side to null (unless already changed)
            if ($ilot->getPrinter() === $this) {
                $ilot->setPrinter(null);
            }
        }

        return $this;
    }
}
