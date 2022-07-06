<?php

namespace App\Entity;

use App\Repository\BadgeageRepository;
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
}
