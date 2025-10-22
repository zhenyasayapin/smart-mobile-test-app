<?php

namespace App\DataFixtures;

use App\Factory\AuthorFactory;
use App\Factory\BookFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $author = AuthorFactory::createOne();

        BookFactory::createMany(100, [
            'author' => $author,
        ]);

        $authors = AuthorFactory::createMany(2);

        BookFactory::createMany(10, function () use ($authors) {
            return [
                'author' => $authors[rand(0, count($authors) - 1)]
            ];
        });

        $manager->flush();
    }
}
