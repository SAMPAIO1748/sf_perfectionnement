<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $category = new Category();

            $category->setName($faker->word);
            $category->setDescription($faker->text);

            $manager->persist($category);
        }

        $manager->flush();
    }
}
