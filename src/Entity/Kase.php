<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Enum\UserTier;
use App\Repository\KaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: KaseRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['case:read']]
        ),
        new Get(
            uriTemplate: '/case/{id}',
            uriVariables: ['id' => 'id'],
            normalizationContext: ['groups' => ['case:read']],
        ),
        new Post(
            uriTemplate: '/case',
            normalizationContext: ['groups' => ['case:read']],
            denormalizationContext: ['groups' => ['case:write']],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Patch(
            uriTemplate: '/case/{id}',
            uriVariables: ['id' => 'id'],
            normalizationContext: ['groups' => ['case:read']],
            denormalizationContext: ['groups' => ['case:write']],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Delete(
            uriTemplate: '/case/{id}',
            uriVariables: ['id' => 'id'],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Post(
            uriTemplate: '/case/{id}/open',
            uriVariables: ['id' => 'id'],
            security: "is_granted('ROLE_USER')"
            // custom processor
        ),
    ],
)]
class Kase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('case:read')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['case:write', 'case:read'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Assert\NotBlank(groups: ['case:write'])]
    #[Assert\PositiveOrZero(groups: ['case:write'])]
    #[Groups(['case:write', 'case:read'])]
    private ?float $price = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['case:write', 'case:read'])]
    private ?string $imageUrl = null;

    #[ORM\Column(length: 255)]
    #[Groups(['case:write', 'case:read'])]
    private ?UserTier $requiredTier = UserTier::BASIC;

    /**
     * @var Collection<int, KaseItem>
     */
    #[ORM\OneToMany(targetEntity: KaseItem::class, mappedBy: 'case')]
    #[Groups('case:read')]
    private Collection $caseItems;

    public function __construct()
    {
        $this->caseItems = new ArrayCollection();
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

    public function getRequiredTier(): ?UserTier
    {
        return $this->requiredTier;
    }

    public function setRequiredTier(?UserTier $requiredTier): static
    {
        $this->requiredTier = $requiredTier;

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
            $caseItem->setCase($this);
        }

        return $this;
    }

    public function removeCaseItem(KaseItem $caseItem): static
    {
        if ($this->caseItems->removeElement($caseItem)) {
            // set the owning side to null (unless already changed)
            if ($caseItem->getCase() === $this) {
                $caseItem->setCase(null);
            }
        }

        return $this;
    }
}
