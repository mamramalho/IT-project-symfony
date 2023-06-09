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

    #[ORM\ManyToOne(targetEntity:"App\Entity\User")]
    #[ORM\JoinColumn(nullable:false)]
    private $user;

    #[ORM\ManyToOne(targetEntity:"App\Entity\Vehicle", inversedBy:"reviews")]
    #[ORM\JoinColumn(nullable:false)]
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
