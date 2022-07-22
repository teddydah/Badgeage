<?php

namespace App\Entity;

use App\Repository\StatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatusRepository::class)
 */
class Status
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
    private $code;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=LigneOF::class, mappedBy="status")
     */
    private $ligneOFs;

    public function __construct()
    {
        $this->ligneOFs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
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

    /**
     * @return Collection<int, LigneOF>
     */
    public function getLigneOFs(): Collection
    {
        return $this->ligneOFs;
    }

    public function addLigneOF(LigneOF $ligneOF): self
    {
        if (!$this->ligneOFs->contains($ligneOF)) {
            $this->ligneOFs[] = $ligneOF;
            $ligneOF->setStatus($this);
        }

        return $this;
    }

    public function removeLigneOF(LigneOF $ligneOF): self
    {
        if ($this->ligneOFs->removeElement($ligneOF)) {
            // set the owning side to null (unless already changed)
            if ($ligneOF->getStatus() === $this) {
                $ligneOF->setStatus(null);
            }
        }

        return $this;
    }
}
