<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Patch;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FilmRepository;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\Delete;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FilmRepository::class)]
#[ApiResource
(
    normalizationContext: ['groups' => ['film:read']],
    denormalizationContext: ['groups' => ['film:write']],
)
]
#[Post(security: 'is_granted("ROLE_ADMIN")')]
#[Patch(security: 'is_granted("ROLE_ADMIN")')]
#[Get]
#[GetCollection]
#[Delete(security: 'is_granted("ROLE_ADMIN")')]
#[ApiFilter(SearchFilter::class, properties: ['category' => SearchFilter::STRATEGY_PARTIAL, 'platform' => SearchFilter::STRATEGY_PARTIAL])]
#[ApiFilter(OrderFilter::class, properties: ['releaseDate' => 'DESC'])]

class Film
{
    #[Groups(['film:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['film:read'], ['film:write'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(['film:read'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $releaseDate = null;

    #[Groups(['film:read'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startLicenceDate = null;

    #[Groups(['film:read'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endLicenceDate = null;

    #[Groups(['film:read'])]
    #[ORM\Column]
    private ?bool $isAvailable = null;

    #[Groups(['film:read'], ['film:write'])]
    #[ORM\ManyToOne(inversedBy: 'Film')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[Groups(['film:read'], ['film:write'])]
    #[ORM\ManyToOne(inversedBy: 'Film')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Platform $platform = null;


    public function __construct(){
        $this->isAvailable = false;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getStartLicenceDate(): ?\DateTimeInterface
    {
        return $this->startLicenceDate;
    }

    public function setStartLicenceDate(\DateTimeInterface $startLicenceDate): self
    {
        $this->startLicenceDate = $startLicenceDate;

        return $this;
    }

    public function getEndLicenceDate(): ?\DateTimeInterface
    {
        return $this->endLicenceDate;
    }

    public function setEndLicenceDate(\DateTimeInterface $endLicenceDate): self
    {
        $this->endLicenceDate = $endLicenceDate;

        return $this;
    }

    public function isIsAvailable(): ?bool
    {
        return $this->isAvailable;
    }

    public function setIsAvailable(bool $isAvailable): self
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPlatform(): ?Platform
    {
        return $this->platform;
    }

    public function setPlatform(?Platform $platform): self
    {
        $this->platform = $platform;

        return $this;
    }
}
