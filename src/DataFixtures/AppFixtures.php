<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $fridge = (new Product())
            ->setName('Fridge')
            ->setPrice(1000);
        $manager->persist($fridge);

        $tv = (new Product())
            ->setName('TV')
            ->setPrice(500);
        $manager->persist($tv);

        $smartphone = (new Product())
            ->setName('Smartphone')
            ->setPrice(300);
        $manager->persist($smartphone);

        $manager->flush();
    }
}
