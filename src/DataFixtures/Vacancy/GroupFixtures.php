<?php

namespace App\DataFixtures\Vacancy;

use App\Entity\Vacancy\Group;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\AsciiSlugger;

/**
 * Class GroupFixtures
 *
 * @package App\DataFixtures\Vacancy
 */
class GroupFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $slugger = new AsciiSlugger();

        for ($counter = 0; $counter < 5; $counter++) {
            $title = $faker->text(20);
            $object = (new Group())
                ->setTitle($title)
                ->setSlug($slugger->slug($title))
                ->setDescription($faker->realText);

            $manager->persist($object);
        }

        $manager->flush();
    }
}