<?php

namespace App\Entity;

use App\Repository\SymboleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SymboleRepository::class)
 */
class Symbole
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\OneToOne(targetEntity=Fichier::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $fichier;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="symbole")
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

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

    public function getFichier(): ?Fichier
    {
        return $this->fichier;
    }

    public function setFichier(Fichier $fichier): self
    {
        $this->fichier = $fichier;

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
}
