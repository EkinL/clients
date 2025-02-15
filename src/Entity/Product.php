<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $picture = null;

    #[ORM\Column(length: 255)]
    private ?string $modelObj = null;

    #[ORM\Column(length: 255)]
    private ?string $modelGlb = null;

    #[ORM\Column]
    private ?bool $generate3D = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getModelObj(): ?string
    {
        return $this->modelObj;
    }

    public function setModelObj(string $modelObj): static
    {
        $this->modelObj = $modelObj;

        return $this;
    }

    public function getModelGlb(): ?string
    {
        return $this->modelGlb;
    }

    public function setModelGlb(string $modelGlb): static
    {
        $this->modelGlb = $modelGlb;

        return $this;
    }

    public function isGenerate3D(): ?bool
    {
        return $this->generate3D;
    }

    public function setGenerate3D(bool $generate3D): static
    {
        $this->generate3D = $generate3D;

        return $this;
    }
}
