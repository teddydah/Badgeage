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
     * @ORM\Column(type="string", length=10)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $infoSupplementaire;

    /**
     * @ORM\OneToMany(targetEntity=Symbole::class, mappedBy="article")
     * @ORM\Column(nullable=true)
     */
    private $symbole;

    /**
     * @ORM\OneToMany(targetEntity=LigneOF::class, mappedBy="article")
     */
    private $ligneOFs;

    public function __construct()
    {
        $this->symbole = new ArrayCollection();
        $this->ligneOFs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

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
     * @return Collection<int, Symbole>
     */
    public function getSymbole(): Collection
    {
        return $this->symbole;
    }

    public function addSymbole(Symbole $symbole): self
    {
        if (!$this->symbole->contains($symbole)) {
            $this->symbole[] = $symbole;
            $symbole->setArticle($this);
        }

        return $this;
    }

    public function removeSymbole(Symbole $symbole): self
    {
        if ($this->symbole->removeElement($symbole)) {
            // set the owning side to null (unless already changed)
            if ($symbole->getArticle() === $this) {
                $symbole->setArticle(null);
            }
        }

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
}
