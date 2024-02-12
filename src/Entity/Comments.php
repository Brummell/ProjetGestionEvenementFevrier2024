<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentsRepository::class)]
class Comments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type:'text')]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_comment = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    private ?User $user_comment = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    private ?Evenement $event_comment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $name): static
    {
        $this->description = $name;

        return $this;
    }

    public function getDateComment(): ?\DateTimeInterface
    {
        return $this->date_comment;
    }

    public function setDateComment(\DateTimeInterface $date_comment): static
    {
        $this->date_comment = $date_comment;

        return $this;
    }

    public function getUserComment(): ?User
    {
        return $this->user_comment;
    }

    public function setUserComment(?User $user_comment): static
    {
        $this->user_comment = $user_comment;

        return $this;
    }

    public function getEventComment(): ?Evenement
    {
        return $this->event_comment;
    }

    public function setEventComment(?Evenement $event_comment): static
    {
        $this->event_comment = $event_comment;

        return $this;
    }
}
