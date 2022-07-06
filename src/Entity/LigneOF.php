<?php

namespace App\Entity;

use App\Repository\LigneOFRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LigneOFRepository::class)
 */
class LigneOF
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint")
     */
    private $recid;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantite;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateEcheance;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateExpedition;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isBlocked;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="ligneOFs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

    /**
     * @ORM\ManyToOne(targetEntity=OrdreFab::class, inversedBy="ligneOFs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ordreFab;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

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

    public function getDateExpedition(): ?\DateTimeInterface
    {
        return $this->dateExpedition;
    }

    public function setDateExpedition(\DateTimeInterface $dateExpedition): self
    {
        $this->dateExpedition = $dateExpedition;

        return $this;
    }

    public function isIsBlocked(): ?bool
    {
        return $this->isBlocked;
    }

    public function setIsBlocked(bool $isBlocked): self
    {
        $this->isBlocked = $isBlocked;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

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
}
