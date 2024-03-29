<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ProduitRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
class Produit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128)
     * @Assert\Length(
     *  min=4,
     *  max=13
     * )
     */
    private $productName;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     *  min=10,
     *  max=4000
     * )
     */
    private $productDescription;

    /**
     * @ORM\Column(type="decimal", precision=9, scale=2)
     * @Assert\Type(type="float")
     * @Assert\Range(
     *  min=0,
     *  max=9999999.99
     * )
     */
    private $price;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbViews;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *  min=4,
     *  max=128
     * )
     */
    private $imageName;

    /**
     * @var File
     * @Vich\UploadableField(mapping="Image_du_produit", fileNameProperty="imageName")
     */
    private $imageFile;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="produits", )
     */
    private $tags;

    /**
     * @ORM\Column(type="string", length=128, nullable=true, unique=true)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $publisher;


    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->nbViews = 0;
    }

    /**
     * On définie cette méthode pour afficher le nom de la catégorie dans la liste déroulante du formulaire
     * @return mixed
     */
    public function __toString()
    {
        return $this->productName;
    }

    /**
     * @ORM\PreUpdate()
     */
    public function refreshUpdatedAt()
    {
        $this->updateAt = new \DateTime();
    }

    /**
     * @ORM\PrePersist()
     */
    public function refreshCreateddAt()
    {
        $this->createdAt = new \DateTime();
        $this->updateSlug();
    }

    /**
     * Met a jour le slug par rapport au name
     * @return Produit
     */
    public function updateSlug(): self
    {
        // On récupère le slugger
        $slugify = new Slugify();
        // mise a jour du slug par rapport au name
        $this->slug = $slugify->slugify($this->productName);
        // Pour le chainage
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): self
    {
        $this->productName = $productName;
        $this->updateSlug();

        return $this;
    }

    public function getProductDescription(): ?string
    {
        return $this->productDescription;
    }

    public function setProductDescription(string $productDescription): self
    {
        $this->productDescription = $productDescription;

        return $this;
    }
    
    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getNbViews(): ?int
    {
        return $this->nbViews;
    }

    public function setNbViews(?int $nbViews): self
    {
        $this->nbViews = $nbViews;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPublisher(): ?User
    {
        return $this->publisher;
    }

    public function setPublisher(?User $publisher): self
    {
        $this->publisher = $publisher;

        return $this;
    }

    /**
     * @return File
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;

    }

    /**
     * @param File $imageFile
     * @throws \Exception
     */
    public function setImageFile(?File $imageFile = null): void
    {
        if (!is_null($imageFile)) {
            $this->updateAt = new \DateTimeImmutable();
        }
        $this->imageFile = $imageFile;
    }
}
