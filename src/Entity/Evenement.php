<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $lieu = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_deb = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_fin = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'evenements')]
    private Collection $user_event;

    #[ORM\ManyToOne(inversedBy: 'category')]
    private ?Category $category = null;

    #[ORM\Column(type: Types::BLOB)]
    private $picture = null;

    #[ORM\OneToMany(mappedBy: 'event_comment', targetEntity: Comments::class)]
    private Collection $comments;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'eventpkUser')]
    private ?User $pk_user = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'event_user')]
    private Collection $users;

    public function __construct()
    {
        $this->user_event = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getDateDeb(): ?\DateTimeInterface
    {
        return $this->date_deb;
    }

    public function setDateDeb(\DateTimeInterface $date_deb): static
    {
        $this->date_deb = $date_deb;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): static
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUserEvent(): Collection
    {
        return $this->user_event;
    }

    public function addUserEvent(User $userEvent): static
    {
        if (!$this->user_event->contains($userEvent)) {
            $this->user_event->add($userEvent);
        }

        return $this;
    }

    public function removeUserEvent(User $userEvent): static
    {
        $this->user_event->removeElement($userEvent);

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getPicture()
    {
        return $this->picture;
    }

    public function setPicture($picture = null): static
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setEventComment($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getEventComment() === $this) {
                $comment->setEventComment(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPkUser(): ?User
    {
        return $this->pk_user;
    }

    public function setPkUser(?User $pk_user): static
    {
        $this->pk_user = $pk_user;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addEventUser($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeEventUser($this);
        }

        return $this;
    }
}
