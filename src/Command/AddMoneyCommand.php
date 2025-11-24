<?php

namespace App\Command;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:add-money',
    description: 'Ajoute de l\'argent au solde d\'un utilisateur',
)]
class AddMoneyCommand extends Command
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'L\'email de l\'utilisateur')
            ->addArgument('amount', InputArgument::REQUIRED, 'Le montant à ajouter (ex: 100.50)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $amount = (float) $input->getArgument('amount');

        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            $io->error(sprintf('Aucun utilisateur trouvé avec l\'email : %s', $email));
            return Command::FAILURE;
        }

        $oldBalance = $user->getBalance();
        $newBalance = $oldBalance + $amount;

        $user->setBalance($newBalance);
        $this->entityManager->flush();

        $io->success(sprintf(
            'Succès ! Solde de %s mis à jour : %s € -> %s € (+%s €)',
            $user->getPseudo() ?? $email,
            $oldBalance,
            $newBalance,
            $amount
        ));

        return Command::SUCCESS;
    }
}
