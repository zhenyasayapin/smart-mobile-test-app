<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:generate-admin',
    description: 'Add an admin user',
)]
class GenerateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $admin = new User();

        $admin->setUsername('admin');
        $admin->setPassword('password');
        $admin->setRoles(['ROLE_ADMIN']);

        $this->em->persist($admin);
        $this->em->flush();

        $io->success('Done.');

        return Command::SUCCESS;
    }
}
