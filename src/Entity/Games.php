<?php

namespace App\Entity;

use App\Repository\GamesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GamesRepository::class)]
class Games
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Images>
     */
    #[ORM\OneToMany(targetEntity: Images::class, mappedBy: 'game')]
    private Collection $images;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, PlateForms>
     */
    #[ORM\ManyToMany(targetEntity: PlateForms::class, inversedBy: 'games')]
    private Collection $platform;

    #[ORM\OneToOne(mappedBy: 'game', cascade: ['persist', 'remove'])]
    private ?WebGLDemo $webGLDemo = null;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->platform = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Images>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setGame($this);
        }

        return $this;
    }

    public function removeImage(Images $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getGame() === $this) {
                $image->setGame(null);
            }
        }

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

    /**
     * @return Collection<int, PlateForms>
     */
    public function getPlatform(): Collection
    {
        return $this->platform;
    }

    public function addPlatform(PlateForms $platform): static
    {
        if (!$this->platform->contains($platform)) {
            $this->platform->add($platform);
        }

        return $this;
    }

    public function removePlatform(PlateForms $platform): static
    {
        $this->platform->removeElement($platform);

        return $this;
    }

    public function getWebGLDemo(): ?WebGLDemo
    {
        return $this->webGLDemo;
    }

    public function setWebGLDemo(?WebGLDemo $webGLDemo): static
    {
        // unset the owning side of the relation if necessary
        if ($webGLDemo === null && $this->webGLDemo !== null) {
            $this->webGLDemo->setGame(null);
        }

        // set the owning side of the relation if necessary
        if ($webGLDemo !== null && $webGLDemo->getGame() !== $this) {
            $webGLDemo->setGame($this);
        }

        $this->webGLDemo = $webGLDemo;

        return $this;
    }
}
