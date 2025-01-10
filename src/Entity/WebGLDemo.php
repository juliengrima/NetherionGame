<?php

namespace App\Entity;

use App\Repository\WebGLDemoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WebGLDemoRepository::class)]
class WebGLDemo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $lin = null;

    #[ORM\OneToOne(inversedBy: 'webGLDemo', cascade: ['persist', 'remove'])]
    private ?Games $game = null;

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

    public function getLin(): ?string
    {
        return $this->lin;
    }

    public function setLin(string $lin): static
    {
        $this->lin = $lin;

        return $this;
    }

    public function getGame(): ?Games
    {
        return $this->game;
    }

    public function setGame(?Games $game): static
    {
        $this->game = $game;

        return $this;
    }
}
