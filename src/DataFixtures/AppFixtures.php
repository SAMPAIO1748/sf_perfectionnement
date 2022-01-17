<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Writer;
use App\Entity\Article;
use App\Entity\Category;
use App\Repository\WriterRepository;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    private $categoryRepository;

    private $writerRepository;

    public function __construct(CategoryRepository $categoryRepository, WriterRepository $writerRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->writerRepository = $writerRepository;
    }


    public function load(
        ObjectManager $manager
    ): void {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $category = new Category();

            $category->setName($faker->word);
            $category->setDescription($faker->text);

            $manager->persist($category);

            $manager->flush();
        }

        for ($i = 0; $i < 8; $i++) {
            $writer  = new Writer();

            $writer->setName($faker->lastName);
            $writer->setFirstname($faker->firstName);

            $manager->persist($writer);

            $manager->flush();
        }

        for ($i = 0; $i < 15; $i++) {
            $article = new Article();

            $id_category = rand(91, 100);
            $id_writer = rand(57, 64);

            $category = $this->categoryRepository->find($id_category);
            $writer = $this->writerRepository->find($id_writer);

            $article->setTitle($faker->word);
            $article->setContent($faker->text);
            $article->setDate($faker->dateTime);
            $article->setPublished(true);
            $article->setCategory($category);
            $article->setWriter($writer);

            $manager->persist($article);
        }



        $manager->flush();
    }
}
