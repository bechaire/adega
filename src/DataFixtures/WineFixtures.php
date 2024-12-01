<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Wine;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class WineFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['radom_wines'];
    }

    public function load(ObjectManager $manager): void
    {
        $grapes = ['Cabernet Sauvignon', 'Merlot', 'Pinot Noir', 'Chardonnay', 'Sauvignon Blanc'];
        $countries = ['France', 'Italy', 'Spain', 'Australia', 'Chile'];
        $volumes = [500, 600, 750, 900, 1000];

        for ($i = 1; $i <= 10; $i++) {
            $wine = new Wine();
            $grape = $grapes[array_rand($grapes)];
            $wine->setName("Wine {$i} ({$grape})")
                 ->setVolumeMl($volumes[array_rand($volumes)]) // 500ml .. 1000ml
                 ->setWeightKg(random_int(10, 20) / 10) // 1kg .. 2kg
                 ->increaseStock(random_int(2, 8))
                 ->setPrice(random_int(350, 2200) / 10)
                 ->setGrape($grape)
                 ->setCountry($countries[array_rand($countries)])
                 ->setAlcoholPerc(random_int(100, 160) / 10);
            $manager->persist($wine);
        }

        $manager->flush();
    }
}
