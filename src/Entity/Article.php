<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
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
    private $codeArticle;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $infoSupplementaire;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $symbole;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $forme;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $dimension1;

    /**
     * @ORM\Column(type="integer")
     */
    private $dimension2;

    /**
     * @ORM\OneToMany(targetEntity=LigneOF::class, mappedBy="article")
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

    public function getCodeArticle(): ?string
    {
        return $this->codeArticle;
    }

    public function setCodeArticle(string $codeArticle): self
    {
        $this->codeArticle = $codeArticle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getInfoSupplementaire(): ?string
    {
        return $this->infoSupplementaire;
    }

    public function setInfoSupplementaire(string $infoSupplementaire): self
    {
        $this->infoSupplementaire = $infoSupplementaire;

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
            $ligneOF->setArticle($this);
        }

        return $this;
    }

    public function removeLigneOF(LigneOF $ligneOF): self
    {
        if ($this->ligneOFs->removeElement($ligneOF)) {
            // set the owning side to null (unless already changed)
            if ($ligneOF->getArticle() === $this) {
                $ligneOF->setArticle(null);
            }
        }

        return $this;
    }

    public function getSymbole(): ?string
    {
        return $this->symbole;
    }

    public function setSymbole(string $symbole): self
    {
        $this->symbole = $symbole;

        return $this;
    }

    public function getForme(): ?string
    {
        return $this->forme;
    }

    public function setForme(string $forme): self
    {
        $this->forme = $forme;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDimension1(): ?int
    {
        return $this->dimension1;
    }

    public function setDimension1(int $dimension1): self
    {
        $this->dimension1 = $dimension1;

        return $this;
    }

    public function getDimension2(): ?int
    {
        return $this->dimension2;
    }

    public function setDimension2(int $dimension2): self
    {
        $this->dimension2 = $dimension2;

        return $this;
    }
}
