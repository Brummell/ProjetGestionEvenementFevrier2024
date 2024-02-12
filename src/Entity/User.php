<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\ManyToMany(targetEntity: Evenement::class, mappedBy: 'user_event')]
    private Collection $evenements;

    #[ORM\Column]
    private ?bool $canAssignRoles = null;

    #[ORM\OneToMany(mappedBy: 'user_comment', targetEntity: Comments::class)]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'pk_user', targetEntity: Evenement::class)]
    private Collection $eventpkUser;

    #[ORM\ManyToMany(targetEntity: Evenement::class, inversedBy: 'users')]
    private Collection $event_user;

    public function __construct()
    {
        $this->evenements = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->eventpkUser = new ArrayCollection();
        $this->event_user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Evenement>
     */
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }

    public function addEvenement(Evenement $evenement): static
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements->add($evenement);
            $evenement->addUserEvent($this);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): static
    {
        if ($this->evenements->removeElement($evenement)) {
            $evenement->removeUserEvent($this);
        }

        return $this;
    }

    public function isCanAssignRoles(): ?bool
    {
        return $this->canAssignRoles;
    }

    public function setCanAssignRoles(bool $canAssignRoles): static
    {
        $this->canAssignRoles = $canAssignRoles;

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
            $comment->setUserComment($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUserComment() === $this) {
                $comment->setUserComment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Evenement>
     */
    public function getEventpkUser(): Collection
    {
        return $this->eventpkUser;
    }

    public function addEventpkUser(Evenement $eventpkUser): static
    {
        if (!$this->eventpkUser->contains($eventpkUser)) {
            $this->eventpkUser->add($eventpkUser);
            $eventpkUser->setPkUser($this);
        }

        return $this;
    }

    public function removeEventpkUser(Evenement $eventpkUser): static
    {
        if ($this->eventpkUser->removeElement($eventpkUser)) {
            // set the owning side to null (unless already changed)
            if ($eventpkUser->getPkUser() === $this) {
                $eventpkUser->setPkUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Evenement>
     */
    public function getEventUser(): Collection
    {
        return $this->event_user;
    }

    public function addEventUser(Evenement $eventUser): static
    {
        if (!$this->event_user->contains($eventUser)) {
            $this->event_user->add($eventUser);
        }

        return $this;
    }

    public function removeEventUser(Evenement $eventUser): static
    {
        $this->event_user->removeElement($eventUser);

        return $this;
    }
}
