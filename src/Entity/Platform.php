<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\PlatformRepository;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\Delete;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: PlatformRepository::class)]
#[ApiResource
(
    normalizationContext: ['groups' => ['platform:read']],
    denormalizationContext: ['groups' => ['platform:write']],
)
]

#[Get]
#[GetCollection]
#[Delete(security: 'is_granted("ROLE_ADMIN")')]
class Platform
{
    #[Groups(['platform:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['platform:read'], ['platform:write'])]
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[Groups(['platform:read'], ['platform:write'])]
    #[ORM\OneToMany(mappedBy: 'platform', targetEntity: Film::class)]
    private Collection $Film;

    public function __construct()
    {
        $this->Film = new ArrayCollection();
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

    /**
     * @return Collection<int, Film>
     */
    public function getFilm(): Collection
    {
        return $this->Film;
    }

    public function addFilm(Film $film): self
    {
        if (!$this->Film->contains($film)) {
            $this->Film->add($film);
            $film->setPlatform($this);
        }

        return $this;
    }

    public function removeFilm(Film $film): self
    {
        if ($this->Film->removeElement($film)) {
            // set the owning side to null (unless already changed)
            if ($film->getPlatform() === $this) {
                $film->setPlatform(null);
            }
        }

        return $this;
    }
}
