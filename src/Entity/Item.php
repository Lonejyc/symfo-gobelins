<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['item:read']]
        ),
        new Get(
            uriTemplate: '/item/{id}',
            uriVariables: ['id' => 'id'],
            normalizationContext: ['groups' => ['item:read']],
        ),
        new Post(
            uriTemplate: '/item',
            denormalizationContext: ['groups' => ['item:write']],
            security: "is_granted('ROLE_ADMIN')",
        ),
        new Patch(
            uriTemplate: '/item/{id}',
            uriVariables: ['id' => 'id'],
            denormalizationContext: ['groups' => ['item:update']],
            security: "is_granted('ROLE_ADMIN')",
        ),
        new Delete(
            uriTemplate: '/item/{id}',
            uriVariables: ['id' => 'id'],
            security: "is_granted('ROLE_ADMIN')",
        ),
    ],
)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['item:read', 'case:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(groups: ['item:write'])]
    #[Groups(['item:read', 'item:write', 'item:update', 'case:read', 'inventory:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(groups: ['item:write'])]
    #[Groups(['item:read', 'item:write', 'case:read', 'inventory:read'])]
    private ?string $rarity = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['item:read', 'item:write', 'item:update', 'case:read', 'inventory:read'])]
    private ?string $imageUrl = null;

    #[ORM\Column]
    #[Assert\NotBlank(groups: ['item:write'])]
    #[Assert\PositiveOrZero(groups: ['item:write', 'item:update'])]
    #[Groups(['item:read', 'item:write', 'item:update', 'case:read'])]
    private ?float $basePrice = null;

    /**
     * @var Collection<int, KaseItem>
     */
    #[ORM\OneToMany(targetEntity: KaseItem::class, mappedBy: 'item')]
    private Collection $caseItems;

    /**
     * @var Collection<int, InventoryItem>
     */
    #[ORM\OneToMany(targetEntity: InventoryItem::class, mappedBy: 'item')]
    private Collection $inventoryItems;

    public function __construct()
    {
        $this->caseItems = new ArrayCollection();
        $this->inventoryItems = new ArrayCollection();
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

    public function getRarity(): ?string
    {
        return $this->rarity;
    }

    public function setRarity(string $rarity): static
    {
        $this->rarity = $rarity;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getBasePrice(): ?float
    {
        return $this->basePrice;
    }

    public function setBasePrice(float $basePrice): static
    {
        $this->basePrice = $basePrice;

        return $this;
    }

    /**
     * @return Collection<int, KaseItem>
     */
    public function getCaseItems(): Collection
    {
        return $this->caseItems;
    }

    public function addCaseItem(KaseItem $caseItem): static
    {
        if (!$this->caseItems->contains($caseItem)) {
            $this->caseItems->add($caseItem);
            $caseItem->setItem($this);
        }

        return $this;
    }

    public function removeCaseItem(KaseItem $caseItem): static
    {
        if ($this->caseItems->removeElement($caseItem)) {
            // set the owning side to null (unless already changed)
            if ($caseItem->getItem() === $this) {
                $caseItem->setItem(null);
            }
        }

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
            $inventoryItem->setItem($this);
        }

        return $this;
    }

    public function removeInventoryItem(InventoryItem $inventoryItem): static
    {
        if ($this->inventoryItems->removeElement($inventoryItem)) {
            // set the owning side to null (unless already changed)
            if ($inventoryItem->getItem() === $this) {
                $inventoryItem->setItem(null);
            }
        }

        return $this;
    }
}
