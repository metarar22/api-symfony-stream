<?php

namespace App\Entity;

use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ApiResource
(
    normalizationContext: ['groups' => ['cat:read']],
    denormalizationContext: ['groups' => ['cat:write']],
)
]
#[Post(security: 'is_granted("ROLE_ADMIN")')]
#[Patch(security: 'is_granted("ROLE_ADMIN")')]
#[Get]
#[GetCollection]
class Category
{
    #[Groups(['cat:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['cat:read'], ['cat:write'])]
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[Groups(['cat:read'], ['cat:write'])]
    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Film::class)]
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
            $film->setCategory($this);
        }

        return $this;
    }

    public function removeFilm(Film $film): self
    {
        if ($this->Film->removeElement($film)) {
            // set the owning side to null (unless already changed)
            if ($film->getCategory() === $this) {
                $film->setCategory(null);
            }
        }

        return $this;
    }
}
