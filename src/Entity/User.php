<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\UserRepository;
use App\Enum\UserTier;
use App\State\UserProcessor;
use App\Controller\UserController;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity('email')]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/users',
            normalizationContext: ['groups' => ['user:read']],
        ),
        new Get(
            uriTemplate: '/user/{id}',
            uriVariables: ['id' => 'id'],
            normalizationContext: ['groups' => ['user:read']],
            security: "is_granted('ROLE_ADMIN') or object == user",
        ),
        new Post(
            uriTemplate: '/user',
            normalizationContext: ['groups' => ['user:read']],
            denormalizationContext: ['groups' => ['user:create']],
        ),
        new Patch(
            uriTemplate: '/user/{id}',
            uriVariables: ['id' => 'id'],
            normalizationContext: ['groups' => ['user:read']],
            denormalizationContext: ['groups' => ['user:update']],
            security: "is_granted('ROLE_ADMIN') or object == user",
        ),
        new Delete(
            uriTemplate: '/user/{id}',
            uriVariables: ['id' => 'id'],
            security: "is_granted('ROLE_ADMIN') or object == user",
        ),
    ],
    processor: UserProcessor::class,
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Regex('/[^@ \t\r\n]+@[^@ \t\r\n]+\.[^@ \t\r\n]+/', message: 'Email Invalide')]
    #[Assert\NotBlank]
    #[Groups(['user:create', 'user:update', 'user:read:self'])]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(['user:create'])]
    private ?string $password = null;

    #[ORM\Column]
    #[Groups(['user:read'])]
    private ?float $balance = null;

    #[ORM\Column(length: 20)]
    #[Groups(['user:read'])]
    private ?UserTier $tier = UserTier::BASIC;

    #[ORM\Column(length: 255)]
    #[Groups(['user:create', 'user:update', 'user:read'])]
    private ?string $pseudo = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

     /**
     * @var Collection<int, InventoryItem>
     */
    #[ORM\OneToMany(targetEntity: InventoryItem::class, mappedBy: 'owner')]
    #[Groups(['user:read:self'])]
    private Collection $inventoryItems;

    public function __construct()
    {
        $this->inventoryItems = new ArrayCollection();
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
    #[Ignore]
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

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): static
    {
        $this->balance = $balance;

        return $this;
    }

    public function getTier(): ?UserTier
    {
        return $this->tier;
    }

    public function setTier(UserTier $tier): static
    {
        $this->tier = $tier;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection<int, InventoryItem>
     */
    public function getInventoryItems(): Collection
    {
        return $this->inventoryItems;
    }

    public function addInventoryItem(InventoryItem $inventoryItem): static
    {
        if (!$this->inventoryItems->contains($inventoryItem)) {
            $this->inventoryItems->add($inventoryItem);
            $inventoryItem->setOwner($this);
        }

        return $this;
    }

    public function removeInventoryItem(InventoryItem $inventoryItem): static
    {
        if ($this->inventoryItems->removeElement($inventoryItem)) {
            // set the owning side to null (unless already changed)
            if ($inventoryItem->getOwner() === $this) {
                $inventoryItem->setOwner(null);
            }
        }

        return $this;
    }
}
