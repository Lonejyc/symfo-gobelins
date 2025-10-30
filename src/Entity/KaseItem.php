<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\KaseItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: KaseItemRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/case_items',
            normalizationContext: ['groups' => ['case_item:read']]
        ),
        new Get(
            uriTemplate: '/case_item/{id}',
            uriVariables: ['id' => 'id'],
            normalizationContext: ['groups' => ['case_item:read']]
        ),
        new Post(
            uriTemplate: '/case_item',
            denormalizationContext: ['groups' => ['case_item:write']],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Patch(
            uriTemplate: '/case_item/{id}',
            uriVariables: ['id' => 'id'],
            denormalizationContext: ['groups' => ['case_item:write']],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Delete(
            uriTemplate: '/case_item/{id}',
            uriVariables: ['id' => 'id'],
            security: "is_granted('ROLE_ADMIN')"
        ),
    ],
)]
class KaseItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['case_item:read', 'case:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank(groups: ['case_item:write'])]
    #[Groups(['case_item:read', 'case_item:write', 'case:read'])]
    private ?float $dropRate = null;

    #[ORM\ManyToOne(inversedBy: 'caseItems')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(groups: ['case_item:write'])]
    #[Groups(['case_item:read', 'case_item:write'])]
    private ?Kase $case = null;

    #[ORM\ManyToOne(inversedBy: 'caseItems')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(groups: ['case_item:write'])]
    #[Groups(['case_item:read', 'case_item:write', 'case:read'])]
    private ?Item $item = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDropRate(): ?float
    {
        return $this->dropRate;
    }

    public function setDropRate(float $dropRate): static
    {
        $this->dropRate = $dropRate;

        return $this;
    }

    public function getCase(): ?Kase
    {
        return $this->case;
    }

    public function setCase(?Kase $case): static
    {
        $this->case = $case;

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
}
