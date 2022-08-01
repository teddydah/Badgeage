<?php

namespace App\Entity;

use App\Repository\IlotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=IlotRepository::class)
 * @UniqueEntity("nomAX")
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
     * @Assert\NotBlank()
     * @Assert\Positive()
     */
    private $nomAX;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     */
    private $nomIRL;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank()
     */
    private $nomURL;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank()
     */
    private $initiales;

    /**
     * @ORM\ManyToOne(targetEntity=Printer::class, inversedBy="ilots")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $printer;

    /**
     * @ORM\OneToMany(targetEntity=Badgeage::class, mappedBy="ilot")
     */
    private $badgeages;

    public function __construct()
    {
        $this->badgeages = new ArrayCollection();
    }

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

    public function getInitiales(): ?string
    {
        return $this->initiales;
    }

    public function setInitiales(?string $initiales): self
    {
        $this->initiales = $initiales;

        return $this;
    }

    public function getPrinter(): ?Printer
    {
        return $this->printer;
    }

    public function setPrinter(?Printer $printer): self
    {
        $this->printer = $printer;

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
            $badgeage->setIlot($this);
        }

        return $this;
    }

    public function removeBadgeage(Badgeage $badgeage): self
    {
        if ($this->badgeages->removeElement($badgeage)) {
            // set the owning side to null (unless already changed)
            if ($badgeage->getIlot() === $this) {
                $badgeage->setIlot(null);
            }
        }

        return $this;
    }
}
