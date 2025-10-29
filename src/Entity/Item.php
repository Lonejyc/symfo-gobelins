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

#[ORM\Entity(repositoryClass: ItemRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(
            uriTemplate: '/item/{id}',
            uriVariables: ['id' => 'id'],
        ),
        new Post(
            uriTemplate: '/item',
        ),
        new Patch(
            uriTemplate: '/item/{id}',
            uriVariables: ['id' => 'id'],
        ),
        new Delete(
            uriTemplate: '/item/{id}',
            uriVariables: ['id' => 'id'],
        ),
    ],
)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $rarity = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageUrl = null;

    #[ORM\Column]
    private ?float $marketPrice = null;

    /**
     * @var Collection<int, KaseItem>
     */
    #[ORM\OneToMany(targetEntity: KaseItem::class, mappedBy: 'item')]
    private Collection $kaseItems;

    /**
     * @var Collection<int, InventoryItem>
     */
    #[ORM\OneToMany(targetEntity: InventoryItem::class, mappedBy: 'item')]
    private Collection $inventoryItems;

    public function __construct()
    {
        $this->kaseItems = new ArrayCollection();
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

    public function getMarketPrice(): ?float
    {
        return $this->marketPrice;
    }

    public function setMarketPrice(float $marketPrice): static
    {
        $this->marketPrice = $marketPrice;

        return $this;
    }

    /**
     * @return Collection<int, KaseItem>
     */
    public function getKaseItems(): Collection
    {
        return $this->kaseItems;
    }

    public function addKaseItem(KaseItem $kaseItem): static
    {
        if (!$this->kaseItems->contains($kaseItem)) {
            $this->kaseItems->add($kaseItem);
            $kaseItem->setItem($this);
        }

        return $this;
    }

    public function removeKaseItem(KaseItem $kaseItem): static
    {
        if ($this->kaseItems->removeElement($kaseItem)) {
            // set the owning side to null (unless already changed)
            if ($kaseItem->getItem() === $this) {
                $kaseItem->setItem(null);
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
