<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserSubscriptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: UserSubscriptionRepository::class)]
#[ApiResource(
    security: "is_granted('ROLE_ADMIN') or object.getUser() == user"
)]
class UserSubscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read:self'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userSubscriptions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userSubscriptions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['user:read:self', 'subscription:read'])]
    private ?Subscription $subscription = null;

    #[ORM\Column]
    #[Groups(['user:read:self'])]
    private ?\DateTimeImmutable $startDate = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['user:read:self'])]
    private ?\DateTimeImmutable $endDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getSubscription(): ?Subscription
    {
        return $this->subscription;
    }

    public function setSubscription(?Subscription $subscription): static
    {
        $this->subscription = $subscription;

        return $this;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeImmutable $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeImmutable $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Calcule si l'abonnement est actif.
     */
    #[Groups(['user:read:self'])]
    public function isActive(): bool
    {
        $now = new \DateTimeImmutable();
        return $this->startDate <= $now && ($this->endDate === null || $this->endDate >= $now);
    }
}
