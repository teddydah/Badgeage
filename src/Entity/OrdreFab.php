<?php

namespace App\Entity;

use App\Repository\OrdreFabRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="ordreFabs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\OneToMany(targetEntity=LigneOF::class, mappedBy="ordreFab")
     */
    private $ligneOFs;

    /**
     * @ORM\OneToMany(targetEntity=Badgeage::class, mappedBy="ordreFab", cascade={"persist"})
     */
    private $badgeages;

    /**
     * @ORM\ManyToOne(targetEntity=Adresse::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $adresseLivraison;

    public function __construct()
    {
        $this->ligneOFs = new ArrayCollection();
        $this->badgeages = new ArrayCollection();
    }

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

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

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
            $ligneOF->setOrdreFab($this);
        }

        return $this;
    }

    public function removeLigneOF(LigneOF $ligneOF): self
    {
        if ($this->ligneOFs->removeElement($ligneOF)) {
            // set the owning side to null (unless already changed)
            if ($ligneOF->getOrdreFab() === $this) {
                $ligneOF->setOrdreFab(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Badgeage>
     */
    public function getBadgeages(): Collection
    {
        return $this->badgeages;
    }

    public function addBadgeage(Badgeage $badgeage): self
    {
        if (!$this->badgeages->contains($badgeage)) {
            $this->badgeages[] = $badgeage;
            $badgeage->setOrdreFab($this);
        }

        return $this;
    }

    public function removeBadgeage(Badgeage $badgeage): self
    {
        if ($this->badgeages->removeElement($badgeage)) {
            // set the owning side to null (unless already changed)
            if ($badgeage->getOrdreFab() === $this) {
                $badgeage->setOrdreFab(null);
            }
        }

        return $this;
    }

    public function getAdresseLivraison(): ?Adresse
    {
        return $this->adresseLivraison;
    }

    public function setAdresseLivraison(?Adresse $adresseLivraison): self
    {
        $this->adresseLivraison = $adresseLivraison;

        return $this;
    }
}
