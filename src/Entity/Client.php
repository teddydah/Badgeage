<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 */
class Client
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $numero;

    /**
     * @ORM\OneToMany(targetEntity=OrdreFab::class, mappedBy="client")
     */
    private $ordreFabs;

    /**
     * @ORM\Column(type="bigint")
     */
    private $recid;

    public function __construct()
    {
        $this->ordreFabs = new ArrayCollection();
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

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * @return Collection<int, OrdreFab>
     */
    public function getOrdreFabs(): Collection
    {
        return $this->ordreFabs;
    }

    public function addOrdreFab(OrdreFab $ordreFab): self
    {
        if (!$this->ordreFabs->contains($ordreFab)) {
            $this->ordreFabs[] = $ordreFab;
            $ordreFab->setClient($this);
        }

        return $this;
    }

    public function removeOrdreFab(OrdreFab $ordreFab): self
    {
        if ($this->ordreFabs->removeElement($ordreFab)) {
            // set the owning side to null (unless already changed)
            if ($ordreFab->getClient() === $this) {
                $ordreFab->setClient(null);
            }
        }

        return $this;
    }

    public function getRecid(): ?string
    {
        return $this->recid;
    }

    public function setRecid(string $recid): self
    {
        $this->recid = $recid;

        return $this;
    }
}
