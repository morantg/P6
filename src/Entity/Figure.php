<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FigureRepository")
 */
class Figure
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $ajout_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modif_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Media", mappedBy="figures")
     */
    private $media;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageUne;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Groupe", inversedBy="figures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $groupe;

    public function __construct()
    {
        $this->media = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAjoutAt(): ?\DateTimeInterface
    {
        return $this->ajout_at;
    }

    public function setAjoutAt(\DateTimeInterface $ajout_at): self
    {
        $this->ajout_at = $ajout_at;

        return $this;
    }

    public function getModifAt(): ?\DateTimeInterface
    {
        return $this->modif_at;
    }

    public function setModifAt(\DateTimeInterface $modif_at): self
    {
        $this->modif_at = $modif_at;

        return $this;
    }

    /**
     * @return Collection|Media[]
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Media $medium): self
    {
        if (!$this->media->contains($medium)) {
            $this->media[] = $medium;
            $medium->setFigures($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): self
    {
        if ($this->media->contains($medium)) {
            $this->media->removeElement($medium);
            // set the owning side to null (unless already changed)
            if ($medium->getFigures() === $this) {
                $medium->setFigures(null);
            }
        }

        return $this;
    }

    public function getImageUne()
    {
        return $this->imageUne;
    }

    public function setImageUne($imageUne)
    {
        $this->imageUne = $imageUne;

        return $this;
    }

    public function getGroupe()
    {
        return $this->groupe;
    }

    public function setGroupe($groupe)
    {
        $this->groupe = $groupe;

        return $this;
    }
}
