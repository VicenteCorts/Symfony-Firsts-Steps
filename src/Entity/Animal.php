<?php

namespace App\Entity;

use App\Repository\AnimalRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AnimalRepository::class)]
class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'El tipo no puede estar vacío')]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'El tipo debe tener al menos {{ limit }} caracteres',
        maxMessage: 'El tipo no puede tener más de {{ limit }} caracteres'
    )]
    private ?string $tipo = null;

    #[ORM\Column(length: 255, nullable: true)]
     #[Assert\NotBlank(message: 'El color no puede estar vacío')]
    #[Assert\Length(
        max: 50,
        maxMessage: 'El color no puede tener más de {{ limit }} caracteres'
    )]
    private ?string $color = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'La raza no puede estar vacía')]
    #[Assert\Length(
        max: 100,
        maxMessage: 'La raza no puede tener más de {{ limit }} caracteres'
    )]
    private ?string $raza = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotNull(message: 'La cantidad no puede estar vacía')]
    #[Assert\Type(
        type: 'integer',
        message: 'La cantidad debe ser un número entero'
    )]
    #[Assert\Range(
        min: 1,
        max: 1000,
        notInRangeMessage: 'La cantidad debe estar entre {{ min }} y {{ max }}'
        )]
    private ?int $cantidad = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(?string $tipo): static
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getRaza(): ?string
    {
        return $this->raza;
    }

    public function setRaza(?string $raza): static
    {
        $this->raza = $raza;

        return $this;
    }

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(?int $cantidad): static
    {
        $this->cantidad = $cantidad;

        return $this;
    }
}
