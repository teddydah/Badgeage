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
     * @ORM\Column(type="string", length="50")
     */
    private $recid;

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

    /**
     * @ORM\Column(type="integer")
     */
    private $numLigne;

    /**
     * @ORM\Column(type="integer")
     */
    private $qteCommandee;

    /**
     * @ORM\Column(type="integer")
     */
    private $qteRestante;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datePrevue;

    /**
     * @ORM\OneToOne(targetEntity=Ordonnancement::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $ordo;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class, inversedBy="ligneOFs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

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

    public function getNumLigne(): ?int
    {
        return $this->numLigne;
    }

    public function setNumLigne(int $numLigne): self
    {
        $this->numLigne = $numLigne;

        return $this;
    }

    public function getQteCommandee(): ?int
    {
        return $this->qteCommandee;
    }

    public function setQteCommandee(int $qteCommandee): self
    {
        $this->qteCommandee = $qteCommandee;

        return $this;
    }

    public function getQteRestante(): ?int
    {
        return $this->qteRestante;
    }

    public function setQteRestante(int $qteRestante): self
    {
        $this->qteRestante = $qteRestante;

        return $this;
    }

    public function getDatePrevue(): ?\DateTimeInterface
    {
        return $this->datePrevue;
    }

    public function setDatePrevue(\DateTimeInterface $datePrevue): self
    {
        $this->datePrevue = $datePrevue;

        return $this;
    }

    public function getOrdo(): ?Ordonnancement
    {
        return $this->ordo;
    }

    public function setOrdo(Ordonnancement $ordo): self
    {
        $this->ordo = $ordo;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }
}
