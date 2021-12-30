<?php

namespace App\DataFixtures;

use App\Entity\Catalog\Tag;
use App\Entity\Context;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\AsciiSlugger;

/**
 * Class TagFixtures
 *
 * @package App\DataFixtures
 */
class TagsFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $slugger = new AsciiSlugger();
        $contexts = $manager->getRepository(Context::class)->findAll();

        foreach ($contexts as $context) {
            for ($counter = 0; $counter < 5; $counter++) {
                $title = $faker->text(30);
                $tag = (new Tag())
                    ->setTitle($title)
                    ->setContext($context)
                    ->setIsPublished(true)
                    ->setSlug($slugger->slug($title));

                $manager->persist($tag);
            }
        }


        $manager->flush();
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            ContextFixtures::class
        ];
    }
}