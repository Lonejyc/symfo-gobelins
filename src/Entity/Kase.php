<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\KaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KaseRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(
            uriTemplate: '/kase/{id}',
            uriVariables: ['id' => 'id'],
        ),
        new Post(
            uriTemplate: '/kase',
        ),
        new Patch(
            uriTemplate: '/kase/{id}',
            uriVariables: ['id' => 'id'],
        ),
        new Delete(
            uriTemplate: '/kase/{id}',
            uriVariables: ['id' => 'id'],
        ),
        new Post(
            uriTemplate: '/kase/{id}/open',
            uriVariables: ['id' => 'id'],
            // custom processor
        ),
    ],
)]
class Kase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageUrl = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $requiredTier = null;

    /**
     * @var Collection<int, KaseItem>
     */
    #[ORM\OneToMany(targetEntity: KaseItem::class, mappedBy: 'kase')]
    private Collection $kaseItems;

    public function __construct()
    {
        $this->kaseItems = new ArrayCollection();
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

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getRequiredTier(): ?string
    {
        return $this->requiredTier;
    }

    public function setRequiredTier(?string $requiredTier): static
    {
        $this->requiredTier = $requiredTier;

        return $this;
    }

    /**
     * @return Collection<int, KaseItem>
     */
    public function getItem(): Collection
    {
        return $this->kaseItems;
    }

    public function addItem(KaseItem $item): static
    {
        if (!$this->kaseItems->contains($item)) {
            $this->kaseItems->add($item);
            $item->setKase($this);
        }

        return $this;
    }

    public function removeItem(KaseItem $item): static
    {
        if ($this->kaseItems->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getKase() === $this) {
                $item->setKase(null);
            }
        }

        return $this;
    }
}
