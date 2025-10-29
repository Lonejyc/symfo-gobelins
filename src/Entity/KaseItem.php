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

#[ORM\Entity(repositoryClass: KaseItemRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/case_items',
        ),
        new Get(
            uriTemplate: '/case_item/{id}',
            uriVariables: ['id' => 'id'],
        ),
        new Post(
            uriTemplate: '/case_item',
        ),
        new Patch(
            uriTemplate: '/case_item/{id}',
            uriVariables: ['id' => 'id'],
        ),
        new Delete(
            uriTemplate: '/case_item/{id}',
            uriVariables: ['id' => 'id'],
        ),
    ],
)]
class KaseItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $dropRate = null;

    #[ORM\ManyToOne(inversedBy: 'kaseItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Kase $kase = null;

    #[ORM\ManyToOne(inversedBy: 'kaseItems')]
    #[ORM\JoinColumn(nullable: false)]
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

    public function getKase(): ?Kase
    {
        return $this->kase;
    }

    public function setKase(?Kase $kase): static
    {
        $this->kase = $kase;

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
