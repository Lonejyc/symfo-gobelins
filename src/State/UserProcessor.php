<?php

namespace App\State;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Doctrine\Common\State\RemoveProcessor;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Repository\UserRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: PersistProcessor::class)] private ProcessorInterface $persistProcessor,
        #[Autowire(service: RemoveProcessor::class)] private ProcessorInterface $removeProcessor,
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if ($operation instanceof Post) {

            $data->setRoles(['ROLE_USER']);
            $data->setBalance(5.0);
            $data->setCreatedAt(new \DateTimeImmutable());

            if (empty($data->getPassword())) {
                throw new \InvalidArgumentException("Le mot de passe ne peut pas être vide.");
            }

            $hashedPassword = $this->passwordHasher->hashPassword(
                $data,
                $data->getPassword()
            );
            $data->setPassword($hashedPassword);

            return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        }

        if ($operation instanceof Patch) {
            $userEntity = $this->userRepository->find($uriVariables['id']);

            if (!$userEntity) {
                throw new \InvalidArgumentException("Utilisateur non trouvé.");
            }

            $payload = $context['request']->toArray();

            if (array_key_exists('email', $payload)) {
                $userEntity->setEmail($data->getEmail());
            }

            if (array_key_exists('pseudo', $payload)) {
                $userEntity->setPseudo($data->getPseudo());
            }

            return $this->persistProcessor->process($userEntity, $operation, $uriVariables, $context);
        }

        if ($operation instanceof Delete) {
            return $this->removeProcessor->process($data, $operation, $uriVariables, $context);
        }

        return $data;
    }
}
