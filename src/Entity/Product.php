<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    private $Category;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $productName;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $productprice;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $productdescription;

    #[ORM\Column(type: 'date', nullable: true)]
    private $productdate;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $productImage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?Category
    {
        return $this->Category;
    }

    public function setCategory(?Category $Category): self
    {
        $this->Category = $Category;

        return $this;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(?string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }

    public function getProductprice(): ?string
    {
        return $this->productprice;
    }

    public function setProductprice(?string $productprice): self
    {
        $this->productprice = $productprice;

        return $this;
    }

    public function getProductdescription(): ?string
    {
        return $this->productdescription;
    }

    public function setProductdescription(?string $productdescription): self
    {
        $this->productdescription = $productdescription;

        return $this;
    }

    public function getProductdate(): ?\DateTimeInterface
    {
        return $this->productdate;
    }

    public function setProductdate(?\DateTimeInterface $productdate): self
    {
        $this->productdate = $productdate;

        return $this;
    }

    public function getProductImage(): ?string
    {
        return $this->productImage;
    }

    public function setProductImage(?string $productImage): self
    {
        $this->productImage = $productImage;

        return $this;
    }
}
