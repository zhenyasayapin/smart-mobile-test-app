<?php

namespace App\Command;

use App\Entity\Author;
use App\Entity\Book;
use App\Factory\AuthorFactory;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:generate-test-data',
    description: 'Generate test data',
)]
class GenerateTestDataCommand extends Command
{
    private Generator $faker;

    public function __construct(
        private EntityManagerInterface $em,
    )
    {
        $this->faker = Factory::create();
        parent::__construct();
    }

    protected function configure(): void
    {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->generateBooks(AuthorFactory::createOne(), 100000, 500);
        $this->generateBooks(AuthorFactory::createOne(), 30);
        $this->generateBooks(AuthorFactory::createOne(), 20);

        $io->success('Done');

        return Command::SUCCESS;
    }

    private function generateBooks(Author $author, int $totalAmount, ?int $batchSize = null): void
    {
        $batchSize = $batchSize ?? $totalAmount;

        for ($i = 0; $i < $totalAmount; $i = $i + 1) {
            $book = new Book();

            $book->setAuthor($author);
            $book->setTitle($this->faker->sentence(rand(1, 3)));
            $book->setDescription($this->faker->text(255));

            $this->em->persist($book);
            $this->em->persist($author);

            if (($i % $batchSize) === 0) {
                $this->em->flush();
            }
        }

        $this->em->flush();
        $this->em->clear();
    }
}
