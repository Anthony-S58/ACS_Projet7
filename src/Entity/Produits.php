<?php

namespace App\Entity;

use App\Repository\ProduitsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProduitsRepository::class)
 */
class Produits
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Reference;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Categorie;

    /**
     * @ORM\Column(type="date")
     */
    private $Date_achat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Lieu_achat;

    /**
     * @ORM\Column(type="date")
     */
    private $Date_fin_garantie;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $Prix;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Conseils;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Photo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Manuel;

    /**
     * @ORM\OneToMany(targetEntity=Images::class, mappedBy="produits", orphanRemoval=true, cascade={"persist"})
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity=Fichiers::class, mappedBy="produits", orphanRemoval=true, cascade={"persist"})
     */
    private $fichiers;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->fichiers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->Reference;
    }

    public function setReference(string $Reference): self
    {
        $this->Reference = $Reference;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->Categorie;
    }

    public function setCategorie(string $Categorie): self
    {
        $this->Categorie = $Categorie;

        return $this;
    }

    public function getDateAchat(): ?\DateTimeInterface
    {
        return $this->Date_achat;
    }

    public function setDateAchat(\DateTimeInterface $Date_achat): self
    {
        $this->Date_achat = $Date_achat;

        return $this;
    }

    public function getLieuAchat(): ?string
    {
        return $this->Lieu_achat;
    }

    public function setLieuAchat(string $Lieu_achat): self
    {
        $this->Lieu_achat = $Lieu_achat;

        return $this;
    }

    public function getDateFinGarantie(): ?\DateTimeInterface
    {
        return $this->Date_fin_garantie;
    }

    public function setDateFinGarantie(\DateTimeInterface $Date_fin_garantie): self
    {
        $this->Date_fin_garantie = $Date_fin_garantie;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->Prix;
    }

    public function setPrix(string $Prix): self
    {
        $this->Prix = $Prix;

        return $this;
    }

    public function getConseils(): ?string
    {
        return $this->Conseils;
    }

    public function setConseils(string $Conseils): self
    {
        $this->Conseils = $Conseils;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->Photo;
    }

    public function setPhoto(string $Photo): self
    {
        $this->Photo = $Photo;

        return $this;
    }

    public function getManuel(): ?string
    {
        return $this->Manuel;
    }

    public function setManuel(string $Manuel): self
    {
        $this->Manuel = $Manuel;

        return $this;
    }

    /**
     * @return Collection|Images[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setProduits($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProduits() === $this) {
                $image->setProduits(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Fichiers[]
     */
    public function getFichiers(): Collection
    {
        return $this->fichiers;
    }

    public function addFichier(Fichiers $fichier): self
    {
        if (!$this->fichiers->contains($fichier)) {
            $this->fichiers[] = $fichier;
            $fichier->setProduits($this);
        }

        return $this;
    }

    public function removeFichier(Fichiers $fichier): self
    {
        if ($this->fichiers->removeElement($fichier)) {
            // set the owning side to null (unless already changed)
            if ($fichier->getProduits() === $this) {
                $fichier->setProduits(null);
            }
        }

        return $this;
    }
}
