<?php

namespace App\Entity;

use App\Repository\BadgeageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BadgeageRepository::class)
 */
class Badgeage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateBadgeage;

    /**
     * @ORM\ManyToOne(targetEntity=OrdreFab::class, inversedBy="badgeages", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ordreFab;

    /**
     * @ORM\ManyToOne(targetEntity=Ilot::class, inversedBy="badgeages", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ilot;

    /**
     * @ORM\Column(type="bigint")
     */
    private $recid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateBadgeage(): ?\DateTimeInterface
    {
        return $this->dateBadgeage;
    }

    public function setDateBadgeage(\DateTimeInterface $dateBadgeage): self
    {
        $this->dateBadgeage = $dateBadgeage;

        return $this;
    }

    public function getOrdreFab(): ?OrdreFab
    {
        return $this->ordreFab;
    }

    public function setOrdreFab(?OrdreFab $ordreFab): self
    {
        $this->ordreFab = $ordreFab;

        return $this;
    }

    public function getIlot(): ?Ilot
    {
        return $this->ilot;
    }

    public function setIlot(?Ilot $ilot): self
    {
        $this->ilot = $ilot;

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
