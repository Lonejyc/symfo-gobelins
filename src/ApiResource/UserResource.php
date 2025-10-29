<?php

namespace App\ApiResource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\InventoryItem;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ApiResource(
    shortName: 'UserResource',
    operations: [
        new GetCollection(
//            normalizationContext: ['groups' => ['user:read']]
        ),
        new Get(
            uriTemplate: '/user/{id}',
            uriVariables: ['id' => 'id'],
//            normalizationContext: ['groups' => ['user:read', 'user:read:self']]
        ),
        new Post(
            uriTemplate: '/user',
//            denormalizationContext: ['groups' => ['user:write']],
        ),
        new Patch(
            uriTemplate: '/user/{id}',
            uriVariables: ['id' => 'id'],
//            denormalizationContext: ['groups' => ['user:update']]
        ),
        new Delete(
            uriTemplate: '/user/{id}',
            uriVariables: ['id' => 'id'],
        ),
    ],
    stateOptions: new Options(entityClass: User::class)
)]
class UserResource
{
//    #[Groups(['user:read'])]
    private ?int $id = null;

//    #[Groups(['user:read:self', 'user:write'])]
    #[Assert\Regex('/[^@ \t\r\n]+@[^@ \t\r\n]+\.[^@ \t\r\n]+/', message: 'Email Invalide')]
    #[Assert\NotBlank(groups: ['user:write'])]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
//    #[Groups(['user:read:self'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    private ?string $password = null;

//    #[Groups(['user:read:self'])]
    private ?float $balance = null;

//    #[Groups(['user:read', 'user:write', 'user:update'])]
    private ?string $pseudo = null;

//    #[Groups(['user:read:self'])]
    private ?string $tier = null;

//    #[Groups(['user:read'])]
    private ?\DateTimeImmutable $created_at = null;

    /**
     * @var Collection<int, InventoryItem>
     */
//    #[Groups(['user:read:self'])]
    private Collection $inventoryItems;

}
