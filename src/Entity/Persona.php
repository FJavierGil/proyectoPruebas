<?php

namespace App\Entity;

use App\Repository\PersonaRepository;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;

#[ORM\Entity(repositoryClass: PersonaRepository::class)]
class Persona implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 128)]
    private string $nombre;

    #[ORM\Column(type: 'string', length: 128)]
    private string $apellidos;

    #[ORM\Column(type: 'string', length: 255)]
    private string $email;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellidos(): string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): self
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    #[ArrayShape([
        'id' => "int",
        'nombre' => "string",
        'apellidos' => "string",
        'email' => "string"
    ])]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'nombre' => $this->getNombre(),
            'apellidos' => $this->getApellidos(),
            'email' => $this->getEmail(),
        ];
    }
}
