<?php

namespace App\Entity;

use App\Enum\DelivrerablesStatusEnum;
use App\Repository\DeliverablesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeliverablesRepository::class)]
class Deliverables
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $delivery_date = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Projects $id_project = null;

    #[ORM\Column(enumType: DelivrerablesStatusEnum::class)]
    private ?DelivrerablesStatusEnum $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDeliveryDate(): ?\DateTimeInterface
    {
        return $this->delivery_date;
    }

    public function setDeliveryDate(\DateTimeInterface $delivery_date): static
    {
        $this->delivery_date = $delivery_date;

        return $this;
    }

    public function getIdProject(): ?Projects
    {
        return $this->id_project;
    }

    public function setIdProject(?Projects $id_project): static
    {
        $this->id_project = $id_project;

        return $this;
    }

    public function getStatus(): ?DelivrerablesStatusEnum
    {
        return $this->status;
    }

    public function setStatus(DelivrerablesStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }
}
