<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $filename = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $resultFilename = null;

    #[Assert\File(maxSize: "1024k", mimeTypes: ["image/jpeg", "image/png"])]

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): static
    {
        $this->filename = $filename;

        return $this;
    }

    public function getResultFilename(): ?string
    {
        return $this->resultFilename;
    }

    public function setResultFilename(?string $resultFilename): static
    {
        $this->resultFilename = $resultFilename;

        return $this;
    }
}
