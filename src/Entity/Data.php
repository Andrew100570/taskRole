<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\DataRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DataRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'data:item']),
        new GetCollection(normalizationContext: ['groups' => 'data:list'])
    ],
    paginationEnabled: false,
)]
class Data
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['data:list', 'data:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['data:list', 'data:item'])]
    private ?string $Name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['data:list', 'data:item'])]
    private ?string $Data = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['data:list', 'data:item'])]
    private ?\DateTimeInterface $RecordingDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['data:list', 'data:item'])]
    private ?string $file = null;

    #[ORM\ManyToOne(inversedBy: 'data',targetEntity: User::class)]
    #[ORM\JoinColumn(nullable:true,  onDelete: "CASCADE")]
    #[Groups(['data:list', 'data:item'])]
    private ?User $user = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): static
    {
        $this->Name = $Name;

        return $this;
    }

    public function getData(): ?string
    {
        return $this->Data;
    }

    public function setData(string $Data): static
    {
        $this->Data = $Data;

        return $this;
    }

    public function getRecordingDate(): ?\DateTimeInterface
    {
        return $this->RecordingDate;
    }

    public function setRecordingDate(?\DateTimeInterface $RecordingDate): static
    {
        $this->RecordingDate = $RecordingDate;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): static
    {
        $this->file = $file;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }


}
