<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Enum\UserTier;
use App\Repository\SubscriptionRepository;
use App\State\SubscriptionPurchaseProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SubscriptionRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/subscriptions',
            normalizationContext: ['groups' => ['subscription:read']]
        ),
        new Post(
            uriTemplate: '/subscriptions',
            denormalizationContext: ['groups' => ['subscription:write']],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Post(
            uriTemplate: '/subscriptions/{id}/purchase',
            uriVariables: ['id' => 'id'],
            normalizationContext: ['groups' => ['user:read:self']],
            security: "is_granted('ROLE_USER')",
            name: 'purchase_subscription',
            processor: SubscriptionPurchaseProcessor::class
        ),
    ],
    normalizationContext: ['groups' => ['subscription:read']]
)]
class Subscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['subscription:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['subscription:read', 'subscription:write'])]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['subscription:read', 'subscription:write'])]
    #[Assert\Positive]
    private ?float $price = null;

    #[ORM\Column(length: 50)]
    #[Groups(['subscription:read', 'subscription:write'])]
    #[Assert\NotBlank]
    private ?UserTier $tier = null; // Niveau donné par cet abonnement (e.g., UserTier::GOLD)

    #[ORM\Column]
    #[Groups(['subscription:read', 'subscription:write'])]
    #[Assert\PositiveOrZero]
    private ?int $durationMonths = 1; // Durée par défaut

    /**
     * @var Collection<int, UserSubscription>
     */
    #[ORM\OneToMany(targetEntity: UserSubscription::class, mappedBy: 'subscription')]
    private Collection $userSubscriptions;

    public function __construct()
    {
        $this->userSubscriptions = new ArrayCollection();
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

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

    public function getDurationMonths(): ?int
    {
        return $this->durationMonths;
    }

    public function setDurationMonths(int $durationMonths): static
    {
        $this->durationMonths = $durationMonths;

        return $this;
    }

    /**
     * @return Collection<int, UserSubscription>
     */
    public function getUserSubscriptions(): Collection
    {
        return $this->userSubscriptions;
    }
}
