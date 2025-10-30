<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\InventoryItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: InventoryItemRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['inventory:read']],
            security: "is_granted('ROLE_USER')"
        ),
        new Get(
            uriTemplate: '/inventory_item/{id}',
            uriVariables: ['id' => 'id'],
            normalizationContext: ['groups' => ['inventory:read']],
            security: "object.getOwner() == user or is_granted('ROLE_ADMIN')"
        ),
        new Post(
            uriTemplate: '/inventory_item',
            denormalizationContext: ['groups' => ['inventory:write:admin']],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Patch(
            uriTemplate: '/inventory_item/{id}',
            uriVariables: ['id' => 'id'],
            denormalizationContext: ['groups' => ['inventory:update:admin']],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Delete(
            uriTemplate: '/inventory_item/{id}',
            uriVariables: ['id' => 'id'],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Post(
            uriTemplate: '/inventory_item/{id}/sell',
            security: "object.getOwner() == user",
            read: false,
            write: false,
            name: 'sell_item',
//            processor: App\State\InventorySellProcessor::class
        ),
    ],
    normalizationContext: ['groups' => ['inventory:read']]
)]
class InventoryItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['inventory:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['inventory:read', 'inventory:write:admin', 'inventory:update:admin'])]
    private ?float $float = null;

    #[ORM\Column]
    #[Groups(['inventory:read', 'inventory:write:admin', 'inventory:update:admin'])]
    private ?bool $statTrak = null;

    #[ORM\Column]
    #[Groups(['inventory:read'])]
    private ?\DateTimeImmutable $acquired_at = null;

    #[ORM\ManyToOne(inversedBy: 'inventoryItems')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['inventory:write:admin'])]
    private ?User $owner = null;

    #[ORM\ManyToOne(inversedBy: 'inventoryItems')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['inventory:read', 'inventory:write:admin'])]
    private ?Item $item = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAcquiredAt(): ?\DateTimeImmutable
    {
        return $this->acquired_at;
    }

    public function setAcquiredAt(\DateTimeImmutable $acquired_at): static
    {
        $this->acquired_at = $acquired_at;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): static
    {
        $this->item = $item;

        return $this;
    }

    public function getFloat(): ?float
    {
        return $this->float;
    }

    public function setFloat(float $float): static
    {
        $this->float = $float;

        return $this;
    }

    public function isStatTrak(): ?bool
    {
        return $this->statTrak;
    }

    public function setStatTrak(bool $statTrak): static
    {
        $this->statTrak = $statTrak;

        return $this;
    }

    #[Groups('inventory:read')]
    public function getCalculatedPrice(): float
    {
        if (!$this->item) {
            return 0.0;
        }

        $price = $this->item->getBasePrice();

        if ($this->statTrak) {
            $price *= 1.8;
        }

        switch ($this->getWearTierName()) {
            case 'Factory New':
                $price *= 1.0; // Prix de base
                break;
            case 'Minimal Wear':
                $price *= 0.85; // -15%
                break;
            case 'Field-Tested':
                $price *= 0.70; // -30%
                break;
            case 'Well-Worn':
                $price *= 0.55; // -45%
                break;
            case 'Battle-Scarred':
                $price *= 0.40; // -60%
                break;
        }

        return round($price, 2);
    }

    #[Groups('inventory:read')]
    public function getWearTierName(): string
    {
        if ($this->float === null) {
            return 'Unknown';
        }

        if ($this->float < 0.07) {
            return 'Factory New';
        }
        if ($this->float < 0.15) {
            return 'Minimal Wear';
        }
        if ($this->float < 0.38) {
            return 'Field-Tested';
        }
        if ($this->float < 0.45) {
            return 'Well-Worn';
        }

        return 'Battle-Scarred';
    }
}
