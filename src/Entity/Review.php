<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: ReviewRepository::class)]
#[ORM\Table(name: '`review`')]
class Review
{
    #[ORM\Id()]
    #[ORM\GeneratedValue()]
    #[ORM\Column(type:"integer")]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "reviews")]
    #[ORM\JoinColumn(name: 'user_id', nullable: false, referencedColumnName: 'id')]
    private $user;

    #[ORM\ManyToOne(targetEntity: Vehicle::class, inversedBy: "reviews")]
    #[ORM\JoinColumn(name: 'vehicle_id', nullable: false, referencedColumnName: 'id')]
    private $vehicle;

    #[ORM\Column(type:"text")]
    private $content;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): self
    {
        $this->vehicle = $vehicle;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
