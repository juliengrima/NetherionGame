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

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $link = null;

    #[ORM\OneToOne(inversedBy: 'webGLDemo', cascade: ['persist', 'remove'])]
    private ?Games $game = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $dataFilePath = null;

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

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): static
    {
        $this->link = $link;

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

    public function getDataFilePath(): ?string
    {
        return $this->dataFilePath;
    }

    public function setDataFilePath(?string $dataFilePath): self
    {
        $this->dataFilePath = $dataFilePath;

        return $this;
    }
}
